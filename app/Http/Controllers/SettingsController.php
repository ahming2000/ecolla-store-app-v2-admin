<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
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

        $currentVersion = 'v1.1.1 Beta';
        $updateDate = '2021/05/05';
        $color = 'orange'; // Beta: orange, Release: green

        $logs = [
            [
                'version' => 'v1.1.1 Beta',
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
