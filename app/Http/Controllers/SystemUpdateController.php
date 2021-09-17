<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SystemUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'access:admin']);
    }

    public function performUpdate(){
        echo "Start to set all Uncategorized item to 'Uncategorized'<br>";
        $this->updateCategory();
        echo "Set category Completed!<br>";
        die('All Tasks completed.');
    }

    private function updateCategory(){
        $list = DB::table('items')->select('id')->get()->toArray();
        $list = array_column($list, 'id');

        foreach ($list as $i){
            $item = Item::find($i);
            if (empty($item->categories->toArray())){
                DB::table('category_item')->insert(['item_id' => $item->id, 'category_id' => '1']);
            }
        }

    }
}
