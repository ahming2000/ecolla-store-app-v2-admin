<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemImage;
use App\Models\OrderItem;
use App\Models\Variation;
use App\Util\ImageHandler;
use App\Util\Utility;
use DirectoryIterator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;

class SystemUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'access:admin']);
    }


    public function performUpdate()
    {
        $this->removeResidualImages();

        die('<br/>All Tasks completed.');
    }

    private function removeResidualImages()
    {
        $action = request('action') ?? false;
        $imagesPath = __DIR__ . '/../../../public/storage/uploads/';
        $webPath = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/storage/uploads/';

        $itemImages = array_column(
            ItemImage::query()
                ->whereRaw("item_images.image LIKE 'http%'")
                ->get(['image'])
                ->toArray(),
            'image'
        );

        $variationImages = array_column(
            Variation::query()
                ->whereRaw("variations.image LIKE 'http%'")
                ->get(['image'])
                ->toArray(),
            'image'
        );

        $images = array_merge($itemImages, $variationImages);

        // Remove path from image column
        for ($i = 0; $i < sizeof($images); $i++) {
            $images[$i] = substr($images[$i], strlen($webPath));
        }

        $dir = new DirectoryIterator($imagesPath);
        $files = [];
        foreach ($dir as $fileInfo) {
            if (!$fileInfo->isDot()) {
                $files[] = $fileInfo->getFilename();
            }
        }

        $filesToDelete = [];
        foreach ($files as $file) {
            $deleteRequired = true;

            foreach ($images as $image) {
                if ($file == $image) {
                    $deleteRequired = false;
                    break;
                }
            }

            if ($deleteRequired) {
                $filesToDelete[] = $file;
            }
        }

        if (!$action) {
            echo 'File to be saved:<br/>';
            dump($images);
        }

        if (!$action) {
            echo 'All files in the folder:<br/>';
            dump($files);
        }

        if (!$action) {
            echo 'File to be deleted:<br/>';
            dump($filesToDelete);
        }

        $fileSizeToBeRemove = 0;
        foreach ($filesToDelete as $image) {
            $filePath = __DIR__ . '/../../../public/storage/uploads/' . $image;
            if (file_exists($filePath)) {
                $fileSizeToBeRemove += filesize($filePath);
                if ($action) {
                    unlink($filePath);
                    echo "Deleted $image!<br/>";
                }
            } else {
                echo $image . ' not found!<br>';
            }
        }

        echo '<br/>File size to be removed: ' . $fileSizeToBeRemove / 1000 / 1000 . 'MB';
    }

    private function convertBinaryResourceToLocal()
    {
        echo "Start converting item general images...<br>";

        $itemImages = DB::table('item_images')->get()->toArray();
        foreach ($itemImages as $row) {
            if (substr($row->image, 0, strlen('http')) == 'http') {
                // This will ignore, since the binary cannot do string comparing, and this function is seeking for binary string
            } else {
                echo "Converting item image id " . $row->id . "<br>";

                try {
                    $this->httpToLocal($row->image, 'item_images', $row->id);
                } catch (NotReadableException $e) {
                    echo "Item image id " . $row->id . " cannot be converted!<br>";
                }
            }
        }

        echo "Item general images converted completed!<br>";

        echo "Start converting item variation images...<br>";

        $variations = DB::table('variations')->get()->toArray();
        foreach ($variations as $row) {
            if (substr($row->image, 0, strlen('http')) == 'http') {
                // This will ignore, since the binary cannot do string comparing, and this function is seeking for binary string
            } else {
                if ($row->image != "") { // Skip empty image column
                    echo "Converting variation image id " . $row->id . "<br>";

                    try {
                        $this->httpToLocal($row->image, 'variations', $row->id);
                    } catch (NotReadableException $e) {
                        echo "Variation image id " . $row->id . " from " . $row->item_id . " cannot be converted!<br>";
                    }
                }
            }
        }

        echo "Item variation images converted completed!<br>";
    }

    private function httpToLocal($data, $table, $id)
    {
        $base64 = ImageHandler::getDisplayableImage($data);
        $path = ImageHandler::convertToLocal($base64);
        DB::table($table)->where('id', '=', $id)->update(['image' => $path]);
    }

    private function convertResourceFromProductionToBinary()
    {
        $failList = [];

        $itemImages = DB::table('item_images')->get()->toArray();
        foreach ($itemImages as $row) {
            if (substr($row->image, 0, strlen('http')) == 'http') {
                echo "Converting item image id " . $row->id . "<br>";

                try {
                    $this->localToBinary($row->image, 'item_images', $row->id);
                } catch (NotReadableException $e) {
                    echo "Item image id " . $row->id . " cannot be converted!<br>";
                    $failList['item_images'][] = $row->id;
                }
            }
        }

        $variations = DB::table('variations')->get()->toArray();
        foreach ($variations as $row) {
            if (substr($row->image, 0, strlen('http')) == 'http') {
                if ($row->image != "") { // Skip empty image column
                    echo "Converting variation image id " . $row->id . "<br>";

                    try {
                        $this->localToBinary($row->image, 'variations', $row->id);
                    } catch (NotReadableException $e) {
                        echo "Variation image id " . $row->id . " from " . $row->item_id . " cannot be converted!<br>";
                        $failList['variations'][] = $row->id;
                    }
                }
            }
        }

        dd($failList);
    }

    private function localToBinary($data, $table, $id)
    {
        $base64 = ImageHandler::localToBase64($data);
        $binary = ImageHandler::base64ToBinary($base64);
        DB::table($table)->where('id', '=', $id)->update(['image' => $binary]);
    }

}
