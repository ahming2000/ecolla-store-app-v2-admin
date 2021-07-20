<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\ItemUtil;
use App\Models\SystemConfig;
use App\Models\Variation;
use App\Models\VariationDiscount;
use App\Util\MessageManager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class ItemsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'access:status_check']);
    }

    public function index()
    {
        // Get request value
        $paginate = request('paginate') ?? 25;
        $search = request('search') ?? "";
        $category = request('category') ?? "";

        // Generate Where Clause for SQL Query
        $searchClause = $this->generateSearchClause($search, $this->ITEM_SEARCH);
        $category_filterClause = $this->generateFilterClause($category, $this->ITEM_FILTER_CATEGORY);
        $whereClause = $this->combineWhereClause([
            $searchClause,
            $category_filterClause,
        ]);

        // Query all related Item ID
        if ($whereClause != "") {
            $result = DB::table('items')
                ->select('items.id')
                ->join('category_item', 'category_item.item_id', '=', 'items.id')
                ->join('categories', 'categories.id', '=', 'category_item.category_id')
                ->join('variations', 'variations.item_id', '=', 'items.id')
                ->whereRaw($whereClause)
                ->distinct('items.id')
                ->get();
        } else {
            $result = DB::table('items')
                ->select('items.id')
                ->get();
        }

        // Retrieve required data
        $ids = array_column($result->toArray(), 'id');
        $items = Item::whereIn('id', $ids)
            ->orderBy('created_at', 'desc')
            ->paginate($paginate);
        $categories = Category::all();

        // Set pagination links parameter
        $items->withPath('/item' . $this->generateParameter(
                [
                    'paginate' => $paginate,
                    'search' => $search,
                    'category' => $category,
                ])
        );

        // Generate parameter for filtering (search, category, paginate)
        $params = [
            'paginate' => $this->generateParameter(['search' => $search, 'category' => $category], true),
            'category' => $this->generateParameter(['paginate' => $paginate, 'search' => $search], true),
        ];

        return view('item.index', compact('items', 'categories', 'params'));
    }

    public function show(Item $item)
    {
        return view('item.show', compact('item'));
    }

    public function create()
    {
        return view('item.create');
    }

    public function add()
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
        // Category
        $DEFAULT_CATEGORY_COUNT = SystemConfig::where('name', '=', 'mgmt_i_defaultCategoryCount')->first()->value;

        $categories = Category::whereNotBetween('id', [$DEFAULT_CATEGORY_COUNT + 1, 10])->get();

        // Item
        $item = Item::with("categories")
            ->with("util")
            ->with("discounts")
            ->with("variations")
            ->with("images")
            ->where("items.id", "=", $item->id)
            ->first();

        return view('item.edit2', compact('item', 'categories'));
    }

    public function list(Item $item): bool
    {
        if ($item->util->is_listed) {
            $item->util()->update(['is_listed' => false]);
            return true;
        } else {
            if ($this->canList($item->id)) {
                $item->util()->update(['is_listed' => true]);
                return true;
            } else {
                return false;
            }
        }
    }

    public function resetUtil(Item $item, string $attr): bool
    {
        $item->util()->update([$attr => 0]);
        return true;
    }

    public function destroy(Item $item)
    {
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

    private function canList(int $item_id, bool $list = false): bool
    {
        $obj = Item::find($item_id);

        $item = $obj->toArray();
        $variationCount = sizeof($obj->variations->toArray());
        $variations = $obj->variations->toArray();

        if (
            $item['name'] == null ||
            $item['name_en'] == null ||
            $item['desc'] == null ||
            $item['origin'] == null ||
            $item['origin_en'] == null || // Make sure item attribute is filled
            $variationCount < 1 // Make sure have at least one variation
        ) {
            if ($list) $obj->util()->update(['is_listed' => '0']);
            return false;
        } else {
            foreach ($variations as $variation) { // Make sure all variation have filled all attribute except image, stock, weight, price
                if (
                    $variation['barcode'] == null ||
                    $variation['name'] == null ||
                    $variation['name_en'] == null
                ) {
                    if ($list) $obj->util()->update(['is_listed' => '0']);
                    return false;
                }
            }
        }

        if ($list) $obj->util()->update(['is_listed' => '1']);
        return true;
    }

    public function update(Item $item, string $type): \Illuminate\Http\JsonResponse
    {
        $action = request('action') ?? "";

        $msgMgr = new MessageManager();

        switch ($type) {

            case "itemBasic":

                $data = request('item_info');

                if ($this->itemNameIsDuplicated($item->id, $data['name'])) {
                    $id = $this->getItem($data['name'], 'id')->id;
                    $msgMgr->pushError("此商品名称已被使用！请到<a href=\"/item/$id/edit\">点击此处</a>添加规格！");
                } else {
                    $item->update($data);
                    $msgMgr->pushInfo("基本资料保存成功！");
                }

                break;

            case "images":

                $data = request('image');

                switch ($action){
                    case 'add':

                        if($this->addItemImage($item, $data)){
                            $msgMgr->pushInfo("商品照片保存成功！");
                        } else {
                            $msgMgr->pushError("保存商品照片失败！请联系技术人员！");
                        }

                        break;

                    case 'delete':

                        if($this->deleteItemImage($data)){
                            $msgMgr->pushInfo("商品照片删除成功！");
                        } else {
                            $msgMgr->pushError("删除商品照片失败！请联系技术人员！");
                        }

                        break;

                    default:
                }

                break;

            case "category":

                $old = array_column($item->categories->toArray(), 'id');
                $new = request('categories');

                $this->processCategories($item, $old, $new);
                $msgMgr->pushInfo("商品分类保存成功！");

                break;

            case "variation":

                $data = request('variation');

                switch ($action) {

                    case "add":

                        if($this->variationBarcodeIsDuplicated($data['barcode'], $item->id)){
                            $msgMgr->pushError("货号 " . $data['barcode'] . " 已存在！");
                        } else {
                            if ($this->addVariation($item, $data)) {
                                $msgMgr->pushInfo("添加规格成功！");
                            } else {
                                $msgMgr->pushError("添加规格失败！请联系技术人员！");
                            }
                        }

                        break;

                    case "update":

                        if($this->variationBarcodeIsDuplicated($data['barcode'], $item->id)){
                            $msgMgr->pushError("货号 " . $data['barcode'] . " 已存在！");
                        } else {
                            if ($this->updateVariation($data)) {
                                $msgMgr->pushInfo("规格保存成功！");
                            } else {
                                $msgMgr->pushError("更新规格失败！请联系技术人员！");
                            }
                        }

                        break;

                    case "delete":

                        if ($this->deleteVariation($item, $data['id'])) {
                            $msgMgr->pushInfo("规格删除成功！");
                        } else {
                            $msgMgr->pushError("删除规格失败！请联系技术人员！");
                        }

                        break;

                    default:
                }

                break;

            case "wholesale":

                break;

            default:
        }

        if($this->canList($item->id, true)){
            $msgMgr->pushInfo("商品已自动上架！");
        } else {
            $msgMgr->pushInfo("商品未上架！");
        }

        return response()->json(["message" => $msgMgr->getAllInfos('string'), "error" => $msgMgr->getAllErrors('string')]);
    }

    private function getItem(string $name, string $select = '*')
    {
        return DB::table('items')
            ->selectRaw($select)
            ->where('name', '=', $name)
            ->first();
    }

    private function addItemImage(Item $item, $data): bool
    {
        $itemImage = new ItemImage();
        $itemImage->setAttribute('image', $data);
        return $item->images()->save($itemImage);
    }

    private function deleteItemImage($id):bool
    {
        return ItemImage::find($id)->delete();
    }

    private function addVariation(Item $item, $data): bool
    {
        $variation = new Variation();
        $variation->setRawAttributes($data['info']);
        if (!$item->variations()->save($variation)) return false;

        if (!empty($data['discount'])) {
            $discount = new VariationDiscount();
            $discount->setRawAttributes($data['discount']);
            if (!$variation->discount()->save($discount)) return false;
        }

        return true;
    }

    private function updateVariation($data): bool
    {
        $variation = Variation::find($data['id']);

        if (!$variation->update($data)) return false;

        if (!empty($data['discount'])) {
            if (!$variation->discount()->update($data['discount'])) return false;
        }

        return true;
    }

    private function deleteVariation(Item $item, $id): bool
    {
        return $item->variations()->find($id)->delete();
    }

    private function itemNameIsDuplicated($id, $name): bool
    {
        $id = DB::table('items')
            ->select('id')
            ->where('name', '=', $name)
            ->where('id', '!=', $id)
            ->first();

        return $id != null;
    }

    private function variationBarcodeIsDuplicated($barcode, $item_id): bool
    {
        $result = DB::table('variations')
            ->select('id')
            ->where('barcode', '=', $barcode)
            ->where('item_id', '!=', $item_id)
            ->get();

        return !empty($result->toArray());
    }

    private function processCategories(Item $item, array $old, array $new)
    {
        for ($i = 0; $i < sizeof($old); $i++) {
            $old[$i] = strval($old[$i]);
        }

        $toAdd = array_diff($new, $old);
        $toRemove = array_diff($old, $new);

        // To add
        foreach ($toAdd as $ta) {
            DB::table('category_item')->insert(['item_id' => $item->id, 'category_id' => $ta]);
        }

        // To remove
        foreach ($toRemove as $tr) {
            $item->categories()->detach($tr);
        }
    }

}
