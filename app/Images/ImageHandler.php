<?php


namespace App\Images;


use Intervention\Image\Facades\Image;

class ImageHandler
{

    public function getProcessedImage($image): string
    {
        return $this->processImage($image->getPathName(), 'frame', true);
    }

    public function storeClientReceipt(){
        // TODO - Refactor from EcollaClient Project
    }

    public function storeItemImage($image, string $itemId): string
    {
        $imagePath = $image->store('items/' . $itemId . '');
        $this->processImage(public_path("img/$imagePath"));
        return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . "/img/" . $imagePath;
    }

    private function processImage(string $path, string $mode = 'frame', bool $encodeDataURL = false)
    {
        // $mode can be 'crop'(fit) or 'frame'(resizeCanvas)

        $image = Image::make($path);
        $min = $image->getWidth() < $image->getHeight() ? $image->getWidth() : $image->getHeight();
        $max = $image->getWidth() > $image->getHeight() ? $image->getWidth() : $image->getHeight();

        if ($mode == 'crop') {
            $image->fit($min);
        } else {
            if ($image->width() > $max) {
                $image->resize($max, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            if ($image->height() > $max) {
                $image->resize(null, $max, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $image->resizeCanvas($max, $max, 'center', false, '#ffffff');
        }

        return $encodeDataURL == true ? $image->encode('data-url') : $image->save();
    }


}
