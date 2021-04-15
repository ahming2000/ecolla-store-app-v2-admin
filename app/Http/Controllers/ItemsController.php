<?php

namespace App\Http\Controllers;

use App\Models\Category;
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

    public function create(){
        return view('item.create');
    }

    public function save(){
        $data = request()->validate([
           'name' => ['required', 'unique:items']
        ]);

        $item = new Item();
        $item->setAttribute('name', $data['name']);
        $item->save();

        return redirect('/item/edit/' . $item->id);
    }

    public function edit(Item $item){
        $categories = Category::all();
        return view('item.edit', compact('item', 'categories'));
    }

    public function update(){

        dd(request()->get('variations'));
    }

}
