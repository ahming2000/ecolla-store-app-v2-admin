<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\ItemUtil;
use App\Models\SystemConfig;
use App\Models\Variation;
use App\Models\VariationDiscount;
use App\Util\SQLHelper;
use App\Util\ImageHandler;
use App\Util\JsonResponseManager;
use App\Util\ViewHelper;
use Exception;
use Illuminate\Support\Facades\DB;

class ItemsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'access:status_check']);
    }

    public function index()
    {
        // Get request parameter value
        $paginate = ViewHelper::param('paginate', 50, true);
        $search = ViewHelper::param('search');
        $category = ViewHelper::param('category');
        $arrangement = ViewHelper::param('arrangement', 'createdAtDesc');

        $special = ViewHelper::param('special');
        if ($special == 'notListed|noStock'){
            $specialClause = "item_utils.is_listed = 0 OR variations.stock = 0";
        }else if ($special == 'notListed'){
            $specialClause = "item_utils.is_listed = 0";
        } else if ($special == 'noStock'){
            $specialClause = "variations.stock = 0";
        } else {
            $specialClause = "";
        }

        // Generate Where Clause for SQL Query
        $searchClause = SQLHelper::generateWhereClause(
            $search,
            [
                'items' => [
                    'name',
                    'name_en',
                    'origin',
                    'origin_en',
                    'desc',
                ],
                'variations' => [
                    'name',
                    'name_en',
                    'barcode',
                ],
            ]
        );
        $category_filterClause = SQLHelper::generateWhereClause(
            $category,
            [
                'categories' => [
                    'name',
                    'name_en',
                ],
            ],
            'exact'
        );
        $whereClause = SQLHelper::combineWhereClause([$searchClause, $category_filterClause, $specialClause]);

        // Query all related Item ID
        if ($whereClause != "") {
            $result = DB::table('items')
                ->select('items.id')
                ->join('category_item', 'category_item.item_id', '=', 'items.id')
                ->join('categories', 'categories.id', '=', 'category_item.category_id')
                ->join('variations', 'variations.item_id', '=', 'items.id')
                ->join('item_utils', 'item_utils.item_id', '=', 'items.id')
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
            ->join('item_utils', 'item_utils.item_id', '=', 'items.id')
            ->orderByRaw(SQLHelper::generateItemsArrangementClause($arrangement))
            ->paginate($paginate);
        $categories = Category::all();

        // Set pagination links parameter
        $paginationParam = SQLHelper::generateParameter([
            'paginate' => $paginate,
            'search' => $search,
            'category' => $category,
            'arrangement' => $arrangement,
            'special' => $special,
        ]);
        $items->withPath('/item' . $paginationParam);

        return view('item.index', compact('items', 'categories'));
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
            'name' => ['required']
        ]);

        $item = new Item();
        $item->setAttribute('name', $data['name']);
        $item->save();
        $item->util()->save(new ItemUtil());
        $this->processCategories($item, [], ['1']); // Add default category

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

        // Convert binary to base64 for display (if any)
        foreach ($item->images as $image) {
            $image['image'] = ImageHandler::getDisplayableImage($image['image']);
        }
        foreach ($item->variations as $variation) {
            if ($variation['image'] != null) {
                $variation['image'] = ImageHandler::getDisplayableImage($variation['image']);
            }
        }

        foreach($item->variations as $v){
            if($v->discount == null){
                $v['discount'] = null;
            } else {
                $v['discount'] = DB::table('variation_discounts')
                    ->where('variation_id', '=', $v->id)->first();
            }
        }

        return view('item.edit', compact('item', 'categories'));
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
            $item->delete();
            DB::commit();
            session()->flash('message', "成功删除 " . $item->name . "!");
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('message', '商品删除失败！请联系客服！');
        }

        return redirect('/item');
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

        $resMgr = new JsonResponseManager();

        switch ($type) {

            case "itemBasic":

                $data = request('item_info');
                $item->update($data);
                $resMgr->addMessage("基本资料保存成功！");
                $resMgr->setData($data);

                break;

            case "images":

                $data = request('image');
                $action = request('action') ?? '';

                switch ($action) {
                    case 'add':

                        if ($this->addItemImage($item, $data)) {
                            $resMgr->addMessage("商品照片保存成功！");
                        } else {
                            $resMgr->addMessage("保存商品照片失败！请联系技术人员！", false);
                        }

                        $data_new = DB::table('item_images')
                            ->where('item_id', '=', $item->id)
                            ->orderByDesc('created_at')
                            ->first('id')
                            ->id;

                        $resMgr->setData(['id' => $data_new]);

                        break;

                    case 'delete':

                        if ($this->deleteItemImage($data)) {
                            $resMgr->addMessage("商品照片删除成功！");
                        } else {
                            $resMgr->addMessage("删除商品照片失败！请联系技术人员！", false);
                        }

                        break;

                    default:
                }

                break;

            case "category":

                $old = array_column($item->categories->toArray(), 'id');
                $new = request('categories');

                // Use default category for no category selected
                // Remove default category if more than 1 category are selected
                if (empty($new)){
                    $new = ['1'];
                } else if (sizeof($new) > 1){
                    $new = array_filter($new, function ($arr) {
                        return $arr != '1';
                    });
                }

                $this->processCategories($item, $old, $new);
                $resMgr->addMessage("商品分类保存成功！");

                $data_new = Category::all()->whereIn('id', $new)->toArray();
                $resMgr->setData($data_new);

                break;

            case "variation":

                $data = request('variation');
                $action = request('action') ?? '';

                switch ($action) {

                    case "add":

                        if ($this->addVariation($item, $data)) {
                            $resMgr->addMessage("添加规格成功！");
                        } else {
                            $resMgr->addMessage("添加规格失败！请联系技术人员！", false);
                        }

                        $data_new = Variation::all()
                            ->where('barcode', '=', $data['info']['barcode'])
                            ->first()
                            ->toArray();

                        $resMgr->setData($data_new);

                        break;

                    case "update":

                        if ($this->updateVariation($data)) {
                            $resMgr->addMessage("规格保存成功！");
                        } else {
                            $resMgr->addMessage("更新规格失败！请联系技术人员！", false);
                        }

                        $resMgr->setData($data);

                        break;

                    case "delete":

                        if ($this->deleteVariation($item, $data['info']['id'])) {
                            $resMgr->addMessage("规格删除成功！");
                        } else {
                            $resMgr->addMessage("删除规格失败！请联系技术人员！", false);
                        }

                        break;

                    default:
                }

                break;

            case "wholesale":

                $resMgr->addMessage("This feature is under maintenance!");

                break;

            default:
        }

        if ($this->canList($item->id, true)) {
            $resMgr->addMessage("商品已自动上架！");
        } else {
            $resMgr->addMessage("商品未上架！");
        }

        return $resMgr->getJsonResponse();
    }

    private function getItem(string $name, string $select = '*')
    {
        return DB::table('items')
            ->selectRaw($select)
            ->where('name', '=', $name)
            ->first();
    }

    private function addItemImage(Item $item, $data): ?bool
    {
        $itemImage = new ItemImage();
        $itemImage->setAttribute('image', $data);
        return $item->images()->save($itemImage) != false;
    }

    private function deleteItemImage($id): bool
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
        $variation = Variation::find($data['info']['id']);

        if (!$variation->update($data['info'])) return false;

        if (empty($data['discount'])) {

            if ($variation->discount != null){
                $variation->discount()->delete();
            }

        } else {

            if ($variation->discount == null){
                $discount = new VariationDiscount();
                $discount->setRawAttributes($data['discount']);
                if (!$variation->discount()->save($discount)) return false;
            } else {
                if (!$variation->discount()->update($data['discount'])) return false;
            }

        }

        return true;
    }

    private function deleteVariation(Item $item, $id): bool
    {
        return $item->variations()->find($id)->delete();
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
