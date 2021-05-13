<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\ItemUtil;
use App\Models\Variation;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class ItemsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = Item::orderBy('created_at', 'desc')->paginate(5);
        return view('item.index', compact('items'));
    }

    public function show(Item $item){
        return view('item.show', compact('item'));
    }

    public function create()
    {
        return view('item.create');
    }

    public function store()
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

            'categories.*.id' => '',

            'variations.*.name' => 'required',
            'variations.*.name_en' => 'required',
            'variations.*.barcode' => 'required',
            'variations.*.price' => 'required',
            'variations.*.weight' => 'required',
            'variations.*.stock' => 'required'
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

        // Variation
        $this->updateVariation($item, $item->variations->toArray(), $data['variations']);

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

                    $imagePath = "https://" . $_SERVER['SERVER_NAME'] . "/img/" . $imagePath;

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

                $imagePath = "https://" . $_SERVER['SERVER_NAME'] . "/img/" . $imagePath;

                DB::table('variations')
                    ->where('barcode', '=', $v['barcode'])
                    ->update(['image'=>$imagePath]);
            }
        }

        $item->util()->update(['is_listed' => '1']);

        return redirect('/item/' . $item->id . '/edit')->with('message', '商品已保存并成功上架！');
    }

    public function destroy(Item $item){
        DB::beginTransaction();
        try {
            $item->images()->delete();
            $item->util()->delete();
            $item->variations()->delete();
            $item->categories()->detach();
            $item->discounts()->delete();
            $item->userRating()->delete();
            $item->delete();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('message', '商品删除失败！请联系客服！');
        }

        return redirect('/item')->with('message', "成功删除 " . $item->name . "!");
    }

    private function updateVariation(Item $item, array $old, array $new){
        $oldBarcode = array_column($old, 'barcode');
        $newBarcode = array_column($new, 'barcode');

        $toAddBarcode = array_diff($newBarcode, $oldBarcode);
        $toDeleteBarcode = array_diff($oldBarcode, $newBarcode);
        $toUpdateBarcode = array_intersect($newBarcode, $oldBarcode);

        $old = $this->generateArrayKeyFromElement($old, 'barcode');
        $new = $this->generateArrayKeyFromElement($new, 'barcode');

        // To add
        foreach($toAddBarcode as $ta){
            $variation = new Variation();
            foreach($new[$ta] as $key => $value){
                $variation->setAttribute($key, $value);
            }
            if($this->isDuplicated('variations', 'barcode', 'item_id', $variation->barcode, $item->id)){
                // TODO - Handle duplicate barcode exception
            } else{
                $item->variations()->save($variation);
            }

        }

        // To delete
        foreach ($toDeleteBarcode as $td){
            $id = $old[$td]['id'];
            Variation::find($id)->delete();
        }

        // To update
        foreach ($toUpdateBarcode as $tu){
            $id = $old[$tu]['id'];
            // TODO - Handle duplicate barcode exception
            DB::table('variations')
                ->where('id','=',$id)
                ->update($new[$tu]);
        }

        // TODO - Handle duplicate barcode within same item problem (If 3 same barcode save which data?)
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

    private function isDuplicated($tableName, $pk, $fk, $pkValue, $fkValue){
        $result = DB::table($tableName)
            ->where($pk,'=', $pkValue)
            ->where($fk, '!=', $fkValue)
            ->get();
        return !empty($result->toArray());
    }

}
