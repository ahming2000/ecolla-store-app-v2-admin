<?php

namespace App\Http\Controllers;

use App\Util\ImageHandler;
use Illuminate\Http\Request;

class ImagesProcessController extends Controller
{
    public function process(): \Illuminate\Http\JsonResponse
    {
        $mode = request('mode') ?? 'frame';
        try{
            return response()->json(['image' => (new ImageHandler())->getEncodeDataURL(request('image'), $mode)]);
        } catch (\Exception $exception){
            return response()->json(["image" => "", 'error' => 'Fail to process image!']);
        }
    }
}
