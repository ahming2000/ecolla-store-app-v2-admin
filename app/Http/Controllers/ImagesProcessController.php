<?php

namespace App\Http\Controllers;

use App\Util\ImageHandler;
use Illuminate\Http\Request;

class ImagesProcessController extends Controller
{
    public function process(): ?string
    {
        $mode = request('mode') ?? 'frame';
        try{
            return (new ImageHandler())->getEncodeDataURL(request('image'), $mode);
        } catch (\Exception $exception){
            session()->flash("error", "Image fail to process!");
            return null;
        }
    }
}
