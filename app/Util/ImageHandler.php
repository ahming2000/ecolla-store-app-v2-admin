<?php


namespace App\Util;


use Exception;
use Intervention\Image\Facades\Image;

class ImageHandler
{
    public static function getDisplayableImage($data): string
    {
        if ($data == '') return '';

        if (substr($data, 0, 4) == 'http') { // Primary display way (Local)
            return $data;
        } else { // Not readable in string, assume it is binary data
            return ImageHandler::binaryToBase64($data);
        }
    }

    public static function binaryToBase64($binary): string
    {
        $base64_temp = base64_encode($binary);
        // For some reason, replace 'A=' to 'C' fix the png image cannot show properly problem
        $base64 = substr($base64_temp, 0, -2) . 'C';

        // Detect image format (fix from last release: data stored did not remove base64 header other than jpg format)
        $mediaType = Utility::getStringBetween($base64, 'data', 'base64');
        switch ($mediaType){
            case 'image/jpeg': return 'data:image/jpeg;base64,' . substr($base64, strlen("dataimage/jpegbase64"));
            case 'image/png': return 'data:image/png;base64,' . substr($base64, strlen("dataimage/pngbase64"));
            case 'image/gif': return 'data:image/gif;base64,' . substr($base64, strlen("dataimage/gifbase64"));
            case 'image/webp': return 'data:image/webp;base64,' . substr($base64, strlen("dataimage/webpbase64"));
            default: return 'data:image/jpeg;base64,' . $base64;
        }
    }

    public static function base64ToBinary($base64)
    {
        return base64_decode($base64); // Auto remove character such like ';', ',' and ':'
    }

    public static function localToBase64($data): string
    {
        return Image::make($data)->encode('data-url')->encoded;
    }

    public static function convertToLocal(string $base64): string
    {
        if ($base64 == "") return "";

        $image = Image::make($base64);

        $mediaType = Utility::getStringBetween($base64, 'data:', ';base64,');

        switch ($mediaType){
            case 'image/jpeg': $extension = '.jpg'; break;
            case 'image/bmp': $extension = '.bmp'; break;
            case 'image/gif': $extension = '.gif'; break;
            case 'image/png': $extension = '.png'; break;
            default: $extension = '';
        }

        $imageName = '';
        $fileLength = 39; // Use length 39 for converted from base64
        try {
            $imageName = substr(bin2hex(random_bytes($fileLength)), 0, $fileLength) . $extension;
        } catch (Exception $ignored) {}

        $image->save(public_path("storage/uploads/$imageName"));
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
