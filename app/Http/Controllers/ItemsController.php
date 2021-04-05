<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemsController extends Controller
{


    /**
     * ItemsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        $items = Item::paginate(5);

        return view('item.index', compact('items'));
    }
}
