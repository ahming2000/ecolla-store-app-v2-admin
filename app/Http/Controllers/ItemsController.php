<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Variation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemsController extends Controller
{


    /**
     * ItemsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $items = Item::paginate(5);

        return view('item.index', compact('items'));
    }

    public function create()
    {
        return view('item.create');
    }

    public function save()
    {
        $data = request()->validate([
            'name' => ['required', 'unique:items']
        ]);

        $item = new Item();
        $item->setAttribute('name', $data['name']);
        $item->save();

        return redirect('/item/' . $item->id . '/edit');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('item.edit', compact('item', 'categories'));
    }

    public function update(Item $item)
    {
        $data = request()->validate([
            'item.name' => 'required',
            'item.name_en' => 'required',
            'item.desc' => 'required',
            'item.origin' => 'required',
            'item.origin_en' => 'required',
            'item.brand' => 'required',
            'item.brand_en' => 'required',

            'categories.*.id' => '',

            'variations.*.name1' => 'required',
            'variations.*.name2' => 'required',
            'variations.*.barcode' => ['required', 'unique:variations'],
            'variations.*.price' => 'required',
            'variations.*.weight' => 'required',

            'inventories.*.*.stock' => ''
        ]);

        $imageData = request()->validate([
            'images.*' => 'image',
            'oldImages.*' => '',
            'variations.*.image' => 'image',
            'variations.*.oldImage' => ''
        ]);

        // Item
        $item->update($data['item']);

        // Category
        $this->updateCategoryItem($item, array_column($item->categories->toArray(), 'id'), array_column($data['categories'],  'id'));

        // Variation
        $this->multipleUpdate('variations', $item->variations->toArray(), $data['variations'], 'barcode', ['item_id' => $item->id]);

        // Inventory
        $inventories = [];
        foreach($item->variations as $v){
            $inventories[] = $v->inventories->toArray();
        }
        // $this->multipleUpdate('inventories', array_column($inventories, '0'),array_column($data['inventories'], '0'),'variation_id');

        // General Image

        // Variation Image

        dd($item);


    }

    private function updateCategoryItem(Item $item, array $old, array $new){

        for($i = 0; $i < sizeof($old); $i++){
            $old[$i] = strval($old[$i]);
        }

        $toAdd = array_diff($new, $old);
        $toRemove = array_diff($old, $new);

        // To add
        foreach ($toAdd as $ta){
            DB::table('category_item')->insert(['item_id' => $item->id, 'category_id' => $ta]);
        }

        // To remove
        foreach ($toRemove as $tr){
            $item->categories()->detach($tr);
        }
    }

    private function multipleUpdate(string $tableName, array $old, array $new, string $primaryKey = 'id', array $foreignKey = [])
    {
        // Auto import foreign key to new array
        if(!empty($foreignKey)){
            $temp = [];
            foreach($new as $n){
                $n = array_merge($n, $foreignKey);
                $temp[] = $n;
            }
            $new = $temp; // Replace
        }

        $oldId = array_column($old, $primaryKey);
        $newId = array_column($new, $primaryKey);

        $toAdd = array_diff($newId, $oldId);
        $toDelete = array_diff($oldId, $newId);
        $toUpdate = array_intersect($oldId, $newId);

        // To add
        foreach ($toAdd as $ta) {
            DB::table($tableName)->insert($this->getArrayByKey($new, $primaryKey, $ta));
        }

        // To delete
        foreach ($toDelete as $td) {
            $id = DB::table($tableName)
                ->select('id')
                ->where($primaryKey, '=', $td)
                ->first()
                ->id;
            DB::table($tableName)->delete($id);
        }

        // To update
        foreach ($toUpdate as $tu) {
            $data = $this->getArrayByKey($new, $primaryKey, $tu);
            foreach($data as $d){
                $data = $d;
                break;
            }

            DB::table($tableName)
                ->where($primaryKey, '=', $tu)
                ->update($data);
        }
    }

    private function getArrayByKey($array, $key, $searchFor): array
    {
        return array_filter($array, function ($element) use ($key, $searchFor) {
            return isset($element[$key]) && $element[$key] == $searchFor;
        });
    }

}
