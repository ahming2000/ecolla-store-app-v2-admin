<?php

namespace App\Http\Controllers;

use App\Images\ImageHandler;
use Illuminate\Http\Request;

class ImagesProcessController extends Controller
{
    public function process(): string
    {
        $dataURL = (new ImageHandler())->getProcessedImage(request('image'));
        return response()->json(['processed' => $dataURL]);
    }
}
