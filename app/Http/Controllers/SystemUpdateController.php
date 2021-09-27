<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemImage;
use App\Util\ImageHandler;
use App\Util\Utility;
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


    public function performUpdate(){

        echo "Create symbolic link for the storage...<br>";
        Artisan::call('storage:link');
        echo "Created Completely!<br>";

        echo "Start to store all binary image into storage image...<br>";
        $this->convertBinaryResourceToLocal();
        echo "Start to store all binary image into storage image...<br>";

        die('All Tasks completed.');
    }

    private function convertBinaryResourceToLocal(){
        echo "Start converting item general images...<br>";

        $itemImages = DB::table('item_images')->get()->toArray();
        foreach ($itemImages as $row){
            if (substr($row->image, 0, strlen('http')) == 'http'){
                // This will ignore, since the binary cannot do string comparing, and this function is seeking for binary string
            } else {
                echo "Converting item image id " . $row->id . "<br>";

                try {
                    $this->httpToLocal($row->image, 'item_images', $row->id);
                } catch (NotReadableException $e){
                    echo "Item image id " . $row->id . " cannot be converted!<br>";
                }
            }
        }

        echo "Item general images converted completed!<br>";

        echo "Start converting item variation images...<br>";

        $variations = DB::table('variations')->get()->toArray();
        foreach ($variations as $row){
            if (substr($row->image, 0, strlen('http')) == 'http'){
                // This will ignore, since the binary cannot do string comparing, and this function is seeking for binary string
            } else {
                if ($row->image != ""){ // Skip empty image column
                    echo "Converting variation image id " . $row->id . "<br>";

                    try {
                        $this->httpToLocal($row->image, 'variations', $row->id);
                    } catch (NotReadableException $e){
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
        DB::table($table)->where('id', '=', $id)->update(['image'=>$path]);
    }

    private function convertResourceFromProductionToBinary(){
        $failList = [];

        $itemImages = DB::table('item_images')->get()->toArray();
        foreach ($itemImages as $row){
            if (substr($row->image, 0, strlen('http')) == 'http'){
                echo "Converting item image id " . $row->id . "<br>";

                try {
                    $this->localToBinary($row->image, 'item_images', $row->id);
                } catch (NotReadableException $e){
                    echo "Item image id " . $row->id . " cannot be converted!<br>";
                    $failList['item_images'][] = $row->id;
                }
            }
        }

        $variations = DB::table('variations')->get()->toArray();
        foreach ($variations as $row){
            if (substr($row->image, 0, strlen('http')) == 'http'){
                if ($row->image != ""){ // Skip empty image column
                    echo "Converting variation image id " . $row->id . "<br>";

                    try {
                        $this->localToBinary($row->image, 'variations', $row->id);
                    } catch (NotReadableException $e){
                        echo "Variation image id " . $row->id . " from " . $row->item_id . " cannot be converted!<br>";
                        $failList['variations'][] = $row->id;
                    }
                }
            }
        }

        dd($failList);
    }

    private function localToBinary($data, $table, $id){
        $base64 = ImageHandler::localToBase64($data);
        $binary = ImageHandler::base64ToBinary($base64);
        DB::table($table)->where('id', '=', $id)->update(['image'=>$binary]);
    }

}
