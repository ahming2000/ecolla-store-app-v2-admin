<?php

namespace App\Http\Controllers;

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
        echo "Start to convert image data column to blob data type...<br>";
        Artisan::call("migrate");
        echo "Convert Completed!<br>";
        die('All Tasks completed.');
    }
}
