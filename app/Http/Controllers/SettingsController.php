<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'access:status_check']);
    }

    public function index()
    {
        $categories = Category::all();
        $defaultCategoryCount = 4;
        return view('setting', compact('categories', 'defaultCategoryCount'));
    }

    public function updateCategory(){
        $data = request()->validate([
           'category.*.id' => '',
           'category.*.name' => 'required',
           'category.*.name_en' => 'required',
        ]);

        $old = Category::where('id', '>', '10')->get()->toArray();
        $new = $data['category'];

        $old = $this->generateArrayKeyFromElement($old, 'id');

        $toAdd = [];
        $toDelete = [];
        $toUpdate = [];

        $temp = [];
        while(!empty($new)){
            $arr = array_pop($new);
            if($arr['id'] == null){
                $toAdd[] = $arr;
            } else {
                $temp[] = $arr;
            }
        }
        $temp = $this->generateArrayKeyFromElement($temp, 'id');

        $oldId = array_column($old, 'id');
        $newId = array_column($temp, 'id');

        $toUpdate = array_intersect($oldId, $newId);
        $toDelete = array_diff($oldId, $newId);

        // To add
        foreach ($toAdd as $ta){
            $category = new Category();
            $category->setAttribute('name', $ta['name']);
            $category->setAttribute('name_en', $ta['name_en']);
            $category->save();
        }

        // To update
        foreach ($toUpdate as $tu){
            $category = Category::find($temp[$tu]['id']);
            $category->update([
                'name' => $temp[$tu]['name'],
                'name_en' => $temp[$tu]['name_en'],
            ]);
        }

        // To delete
        foreach ($toDelete as $td){
            DB::table('category_item')->where('category_id', '=', $td)->delete();
            Category::find($old[$td]['id'])->delete();
        }

        return redirect('/setting')->with('message', '保存成功！');
    }

    public function updateItemSettings(string $property){

    }

    public function updateOrderSettings(string $property){

    }

    public function updateAccountSettings(string $property){

    }

    public function UpdatePaginationSettings(string $property){

    }
}
