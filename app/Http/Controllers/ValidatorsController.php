<?php

namespace App\Http\Controllers;

use App\Util\JsonResponseManager;
use App\Util\ValidationManager;
use Illuminate\Http\Request;

class ValidatorsController extends Controller
{
    public function validateVariation(string $attribute){

        $resMgr = new JsonResponseManager();

        switch ($attribute){
            case 'barcode':

                $item_id = request('item_id');
                $barcode = request('barcode');

                $validator = new ValidationManager();
                $validator->validateUniqueBarcode($item_id, $barcode);

//                $validator->validateUniqueBarcode('126', '9551002817529'); // Example input: Existed for other item
//                $validator->validateUniqueBarcode('126', '3173324112661'); // Example input: Existed for same item
//                $validator->validateUniqueBarcode('126', '123123'); // Example input: Ok

                if ($validator->hasError()){
                    $resMgr->setErrors($validator->getErrorMessage());
                    $resMgr->setOk(false);
                }

                break;

            default:
        }

        return $resMgr->getJsonResponse();
    }
}
