<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\ItemUtil;
use App\Models\Variation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

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

        $items = Item::orderBy('created_at', 'desc')->paginate(5);

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
        $item->util()->save(new ItemUtil());

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
            'variations.*.name1_en' => 'required',
            'variations.*.name2_en' => 'required',
            'variations.*.barcode' => ['required'],
            'variations.*.price' => 'required',
            'variations.*.weight' => 'required',

            'inventories.*.*.stock' => ''
        ]);

        $imageData = request()->validate([
            'item.images.*.newImage' => 'image',
            'item.images.*.oldImage' => '',
            'item.images.*.isEmpty' => '',

            'variations.*.barcode' => '',
            'variations.*.image' => 'image',
            'variations.*.oldImage' => '',
            'variations.*.isEmpty' => ''
        ]);

        // Item (Completed)
        $item->update($data['item']);

        // Category // TODO - Add default category
        $this->updateCategoryItem($item, array_column($item->categories->toArray(), 'id'), array_column($data['categories'],  'id'));

        // Variation // TODO - Complete the code (now only allow to create new)
//        foreach($data['variations'] as $v){
//            $variation = new Variation();
//            foreach($v as $key => $value){
//                $variation->setAttribute($key, $value);
//            }
//            $item->variations()->save($variation);
//        }


//        for($i = 0; $i < sizeof($item->variations); $i++){
//            $item->variations[$i]->update($data['variations'][$i]);
//        }
        // $this->multipleUpdate('variations', $item->variations->toArray(), $data['variations'], 'barcode', ['item_id' => $item->id]);

        // Inventory // TODO - Complete the code (now only allow to create new)
//        $currentInventory = array_column($data['inventories'], '0');
//        for($i = 0; $i < sizeof($item->variations->toArray()); $i++){
//            $inventory = new Inventory();
//            $inventory->setAttribute('stock', $currentInventory[$i]['stock']);
//            $inventory->setAttribute('expire_date', date('Y-m-d'));
//            $item->variations[$i]->inventories()->save($inventory);
//        }


        // Old inventory
//        $inventories = [];
//        foreach($item->variations as $v){
//            $inventories[] = $v->inventories->toArray();
//        }
        // $this->multipleUpdate('inventories', array_column($inventories, '0'),array_column($data['inventories'], '0'),'variation_id');





        // General Image (completed)
        foreach($imageData['item']['images'] as $img){
            if($img['isEmpty']){
                // Press delete button and left it (Direct delete)
                if(isset($img['oldImage'])){
                    $id = DB::table('item_images')
                        ->select('id')
                        ->where('image', '=', $img['oldImage'])
                        ->first()
                        ->id;
                    DB::table('item_images')->delete($id);
                } // else do nothing to ignore the empty image
            } else{
                if(isset($img['newImage'])){
                    $imagePath = $img['newImage']->store('items/' . $item->id . '');
                    $this->processImage(public_path("img/$imagePath"));

                    $imagePath = "http://" . $_SERVER['SERVER_NAME'] . "/img/" . $imagePath;

                    $itemImage = new ItemImage();
                    $itemImage->setAttribute('image', $imagePath);
                    $item->images()->save($itemImage);

                    // Save new image and delete the old one (replace)
                    if(isset($img['oldImage'])){
                        $id = DB::table('item_images')
                            ->select('id')
                            ->where('image', '=', $img['oldImage'])
                            ->first()
                            ->id;
                        ItemImage::find($id)->delete();
                    }
                } // else do nothing to remain the image
            }
        }

        // Variation Image (completed)
        foreach($imageData['variations'] as $v){
            if(isset($v['image'])){
                $imagePath = $v['image']->store('items/' . $item->id . '');
                $this->processImage(public_path("img/$imagePath"));

                $imagePath = "http://" . $_SERVER['SERVER_NAME'] . "/img/" . $imagePath;

                DB::table('variations')
                    ->where('barcode', '=', $v['barcode'])
                    ->update(['image'=>$imagePath]);
            }
        }
        die();
        session()->flash('message', '商品已保存并成功上架！');
        header("refresh: 0");
    }

    private function processImage(string $path, $mode = 'frame'){
        // $mode can be 'crop'(fit) or 'frame'(resizeCanvas)

        $image = Image::make($path);
        $min = $image->getWidth() < $image->getHeight() ? $image->getWidth() : $image->getHeight();
        $max = $image->getWidth() > $image->getHeight() ? $image->getWidth() : $image->getHeight();

        // Avoid exceed memory limit (128MB)
        $max = $max > 3000 ?  3000 : $max;

        if($mode == 'crop'){
            $image->fit($min);
        } else{
            if ($image->width() > $max) {
                $image->resize($max, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            if ($image->height() > $max) {
                $image->resize(null, $max, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $image->resizeCanvas($max, $max, 'center', false, '#ffffff');
        }
        $image->save();
    }

    private function getFilenameFromLink(string $oldFileName): string
    {
        $count = 0;
        $flag = false;
        for($i = -1; $i > strlen($oldFileName) * -1; $i--){
            if($oldFileName[$i] == '.'){ // Find the point to start the count after extension
                $flag = true;
            }
            if($flag){
                $count++;
            }
            if($oldFileName[$i] == '/'){ // Stop at '/'
                return substr($oldFileName, $i + 1, $count - 2);
            }
        }
        return "";
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
