<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{

    public function __construct()
    {
//        $this->middleware('auth');
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

    public function changingLog()
    {

        $currentVersion = 'v1.3.10 Beta';
        $updateDate = '2021/05/17';
        $color = 'orange'; // Beta: orange, Release: green

        $logs = [

//            [
//                'version' => 'v1.4.0 Beta',
//                'date' => '2021/05/11',
//                'desc' => [
//                    '功能：',
//                    '添加了商品折扣功能',
//                    '优化：',
//                    '优化了手机版商品编辑、商品管理主页、订单管理主页界面',
//                    '',
//                ]
//            ],

            [
                'version' => 'v1.3.10 Beta',
                'date' => '2021/05/17',
                'desc' => [
                    '顾客主页添加了广告图片',
                    '订单管理页面添加了订单收据下载功能'
                ]
            ],

            [
                'version' => 'v1.3.9 Beta',
                'date' => '2021/05/15',
                'desc' => [
                    '修复了商品保存后没有进行上架的问题',
                    '修复了商品保存后没有显示保存报告的问题',
                ]
            ],

            [
                'version' => 'v1.3.8 Beta',
                'date' => '2021/05/15',
                'desc' => [
                    '修复了商品类别设定在添加新的类别之后无法删除的问题',
                    '商品管理页面添加了类别分类过滤功能',
                    '商品类别过滤现在不显示0个商品的类别',
                    '顾客商品主页排序更改为逆序'
                ]
            ],

            [
                'version' => 'v1.3.7 Beta',
                'date' => '2021/05/14',
                'desc' => [
                    '优化了图片加载的时间',
                    '调整了主页显示界面的位置',
                    '修复了在商品界面，没有图片的商品，点击后，没有办法进入商品界面的问题'
                ]
            ],

            [
                'version' => 'v1.3.6 Beta',
                'date' => '2021/05/14',
                'desc' => [
                    '更改了顾客界面的颜色（部分不正常的界面显示需要清除浏览器缓存来修复错误）',
                    '优化了主页的显示界面'
                ]
            ],

            [
                'version' => 'v1.3.5 Beta',
                'date' => '2021/05/14',
                'desc' => [
                    '修复了商品规格输入与其他商品或同商品有相同的货号时，出现内部错误的问题',
                    '商品管理主页：添加了商品搜索功能'
                ]
            ],

            [
                'version' => 'v1.3.4 Beta',
                'date' => '2021/05/13',
                'desc' => [
                    '修复了商品类别在其他商品资料验证失败之后，之前的类别选项没有保留的问题（商品编辑）',
                    '添加了新的主页展示图',
                    '优化了装饰代码（部分不正常的装饰需要清除浏览器缓存来修复错误）'
                ]
            ],

            [
                'version' => 'v1.3.3 Beta',
                'date' => '2021/05/13',
                'desc' => [
                    '修复了规格图片上传之后无法删除的问题'
                ]
            ],

            [
                'version' => 'v1.3.2 Beta',
                'date' => '2021/05/13',
                'desc' => [
                    '更新付款方式',
                    '顾客主页页面更新，移除无关联的事物'
                ]
            ],

            [
                'version' => 'v1.3.1 Beta',
                'date' => '2021/05/12',
                'desc' => [
                    '优化商品管理搜索页面',
                    '修复商品编辑，出现验证错误时，商品英文名字无法正常显示的问题',
                    '修复订单状态更新界面，订单取消和订单退款倒反的问题'
                ]
            ],

            [
                'version' => 'v1.3.0 Beta',
                'date' => '2021/05/09',
                'desc' => [
                    '功能：',
                    '顾客商品界面添加了商品描述',
                    '顾客商品界面添加了商品推荐（随机和同类别各10个）',
                    '优化：',
                    '修复顾客主页功能条在滑下之后变透明的问题'
                ]
            ],

            [
                'version' => 'v1.2.3 Beta',
                'date' => '2021/05/08',
                'desc' => [
                    '修复了商品界面在库存只有1的时候可以添加超过1件商品进购物车的问题',
                    '修复了手机版顾客主页功能建显示不正常的问题',
                    '修复仪表板无法正常显示问题',
                    '仪表板界面翻译'
                ]
            ],

            [
                'version' => 'v1.2.2 Beta',
                'date' => '2021/05/07',
                'desc' => [
                    '移除商品品牌',
                    '合拼商品规格名称'
                ]
            ],

            [
                'version' => 'v1.2.1 Beta',
                'date' => '2021/05/06',
                'desc' => [
                    '修复使用外送服务时，顾客无法保存资料的问题',
                    '修复邮费显示不正常问题',
                    '添加了外送详情'
                ]
            ],

            [
                'version' => 'v1.2.0 Beta',
                'date' => '2021/05/05',
                'desc' => [
                    '功能：',
                    '商品删除功能已添加',
                    '商品类别管理功能已添加',
                    '优化：',
                    '优化顾客商品界面：商品图片在数量不足的情况下，已可以正常显示',
                    '其他：',
                    '商品样本移除'
                ]
            ],

            [
                'version' => 'v1.1.0 Beta',
                'date' => '2021/05/02',
                'desc' => [
                    '功能：',
                    '商品创建与属性更改界面已完善',
                    '优化：',
                    '优化库存储存方式',
                    '商品列表手机界面优化',
                    '商品、订单管理页面优化'
                ]
            ],

            [
                'version' => 'v1.0.2 Beta',
                'date' => '2021/04/25',
                'desc' => [
                    '后台：',
                    '修复订单查看界面的订单时间不准确的问题'
                ]
            ],

            [
                'version' => 'v1.0.1 Beta',
                'date' => '2021/04/23',
                'desc' => [
                    '订单管理更新：可以查看订单详情并可以调整订单状态',
                    '用户：批发显示功能优化'
                ]
            ],
        ];

        return view('changingLog', compact('currentVersion', 'updateDate', 'color', 'logs'));
    }
}
