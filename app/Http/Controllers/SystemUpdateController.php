<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'access:admin']);
    }

    public function performUpdate(){
        echo 'Category Item attribute update process starting...<br>';
        DB::table('category_item')->where('category_id', '=', '5')->update(['category_id' => '14']);
        DB::table('category_item')->where('category_id', '=', '6')->update(['category_id' => '17']);
        DB::table('category_item')->where('category_id', '=', '8')->update(['category_id' => '19']);
        DB::table('category_item')->where('category_id', '=', '9')->update(['category_id' => '20']);
        DB::table('category_item')->where('category_id', '=', '10')->update(['category_id' => '21']);
        echo 'Category Item attribute update completed.<br>';

        die('Task completed.');
    }
}
