<?php

namespace App\Http\Controllers;

use App\Images\ImageHandler;
use Illuminate\Http\Request;

class ImagesProcessController extends Controller
{
    public function process(): string
    {
        $mode = request('mode') ?? 'frame';
        $dataURL = (new ImageHandler())->getEncodeDataURL(request('image'), $mode);
        return response()->json(['processed' => $dataURL]);
    }
}
