<?php


namespace App\Util;


use Exception;
use Intervention\Image\Facades\Image;

class ImageHandler
{
    public static function getDisplayableImage($data): string // Pending
    {
        if ($data == '') return '';

        if (substr($data, 0, 4) == 'http') { // Primary display way (Local)
            return $data;
        } else { // Not readable in string, assume it is binary data
            // Detect is that png image (fix from last release: data stored did not remove base64 header other than jpg format)
            $base64 = ImageHandler::binaryToBase64($data);
            $test = Utility::getStringBetween($base64, 'data', 'base64');
            if ($test == '') {
                return 'data:image/png;base64,' . substr($base64, 19);
            } else {
                return $base64;
            }
        }
    }

    public static function binaryToBase64($binary)
    {
        return base64_encode($binary);
    }

    public static function base64ToBinary($base64)
    {
        return base64_decode($base64);
    }

    public static function convertToLocal(string $base64): string // Pending
    {
        if ($base64 == "") return "";

        $image = Image::make($base64);

        $mediaType = Utility::getStringBetween($base64, 'data:', ';base64');

        switch ($mediaType){
            case 'image/jpeg': $extension = '.jpg'; break;
            case 'image/bmp': $extension = '.bmp'; break;
            case 'image/gif': $extension = '.gif'; break;
            case 'image/png': $extension = '.png'; break;
            default: $extension = '';
        }

        $imageName = '';
        try {
            $imageName = substr(bin2hex(random_bytes(39)), 0, 40) . $extension;
        } catch (Exception $ignored) {}

        $image->save("uploads/$imageName");
        return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/storage/uploads/' . $imageName;
    }

    public static function processImage($image, string $mode = 'frame'): string
    {
        // $mode can be 'crop'(fit) or 'frame'(resizeCanvas)

        $path = $image->store('uploads');

        $img = Image::make(public_path("storage/$path"));
        $min = $img->getWidth() < $img->getHeight() ? $img->getWidth() : $img->getHeight();
        $max = $img->getWidth() > $img->getHeight() ? $img->getWidth() : $img->getHeight();

        if ($mode == 'crop') {
            $img->fit($min);
        } else {
            if ($img->width() > $max) {
                $img->resize($max, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            if ($img->height() > $max) {
                $img->resize(null, $max, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $img->resizeCanvas($max, $max, 'center', false, '#ffffff');
        }

        $img->save();

        return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/storage/' . $path;
    }


}
