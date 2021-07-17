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
            return response()->json(['data' => (new ImageHandler())->getEncodeDataURL(request('image'), $mode)]);
        } catch (\Exception $exception){
            return response()->json(['data' => '', 'error' => 'Fail to process image!']);
        }
    }
}
