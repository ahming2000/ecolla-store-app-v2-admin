<?php

namespace App\Http\Controllers;

use App\Util\ImageHandler;
use Exception;
use Illuminate\Http\Request;

class ImagesProcessController extends Controller
{
    public function process(): \Illuminate\Http\JsonResponse
    {
        $image = request('image');

        // Check extension format (within jpeg, png, gif and webp)
        $mediaType = $image->getMimeType(null);
        switch ($mediaType){
            case 'image/png':
            case 'image/gif':
            case 'image/webp':
            case 'image/jpeg':
                break;
            default:
                return response()->json([
                    'image' => '',
                    'error' => '请使用照片格式 ".jpg", ".png", ".gif" 或 ".webp"！'
                ]);
        }

        // Start process
        $mode = request('mode') ?? 'frame';

        try{
            return response()->json(['image' => ImageHandler::processImage($image, $mode)]);
        } catch (Exception $exception){
            return response()->json(["image" => "", 'error' => $exception->getMessage()]);
        }
    }
}
