<?php


namespace App\Util;


use Intervention\Image\Facades\Image;

class ImageHandler
{

    public function getEncodeDataURL($image, $mode): string
    {
        return $this->processImage($image->getPathName(), $mode, true);
    }

    public function convertToDataURL($binary): string
    {
        if ((substr($binary, 0, 4) == 'http')){ // Deprecated
            return $this->processImage($binary, 'frame', true);
        } else {
            return 'data:image/jpeg;base64,' . base64_encode($binary);
        }
    }

    public function convertToBinary($base64)
    {
        return base64_decode(str_replace('data:image/jpeg;base64,', '', $base64));
    }

    private function processImage(string $path, string $mode = 'frame', bool $encodeDataURL = false): \Intervention\Image\Image
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
