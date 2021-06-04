<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SystemConfig;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('setting');
    }

    public function website(){
        $DEFAULT_CATEGORY_COUNT = SystemConfig::where('name', '=', 'mgmt_i_defaultCategoryCount')->first()->value;
        $categories = Category::whereNotBetween('id', [$DEFAULT_CATEGORY_COUNT + 1, 10])->get();

        $order = [
            'clt_o_codePrefix' => SystemConfig::where('name', '=', 'clt_o_codePrefix')->first()->value,
            'clt_o_shippingFeeKampar' => SystemConfig::where('name', '=', 'clt_o_shippingFeeKampar')->first()->value,
        ];

        return view('setting.website', compact('categories', 'DEFAULT_CATEGORY_COUNT', 'order'));
    }

    public function account(){
        return view('setting.account');
    }

    public function updateItemSettings(string $action){
        switch ($action){
            case 'category':
                $data = request()->validate([
                    'category.*.id' => '',
                    'category.*.name' => 'required',
                    'category.*.name_en' => 'required',
                ]);
                $this->updateCategoryType($data);
                break;
            default:
        }

        return redirect('/setting/website?show=item')->with('message', '保存成功！');
    }

    public function updateOrderSettings(string $property){
        $validator = Validator::make(request()->all(), [
            $property => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/setting/website?show=order')
                ->withErrors($validator)
                ->withInput();
        }

        SystemConfig::where('name', '=', $property)->update(['value' => request($property)]);
        return redirect('/setting/website?show=order')->with('message', '保存成功！');
    }

    public function updateAccountSettings(string $action){
        switch ($action){
            case 'name':
                $validator = Validator::make(request()->all(), [
                    'name' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect('/setting/account?show=Name')
                        ->withErrors($validator)
                        ->withInput();
                }

                auth()->user()->update(['name' => request('name')]);

                return redirect('/setting/account')->with('message', '名字更改成功！');
            case 'password':
                $validator = Validator::make(request()->all(), [
                    'old_password' => 'required',
                    'password' => 'required|confirmed',
                ]);

                if ($validator->fails()) {
                    return redirect('/setting/account?show=Password')
                        ->withErrors($validator)
                        ->withInput();
                }
                // Check old password
                if(!password_verify(request('old_password'), auth()->user()->password)){
                    return redirect('/setting/account?show=Password')
                        ->withErrors([
                            'old_password' => '旧密码不正确！请稍后再尝试！',
                        ]);
                }

                // Update new password
                auth()->user()->update(['password' => password_hash(request('password'), PASSWORD_BCRYPT)]);

                return redirect('/setting/account')->with('message', '密码更改成功');
            default:
        }
        return redirect('/setting/account');
    }

    public function UpdatePaginationSettings(string $property){

    }

    private function updateCategoryType($data){
        $old = Category::where('id', '>', '10')->get()->toArray();
        $new = $data['category'] ?? [];

        $old = $this->generateArrayKeyFromElement($old, 'id');

        $toAdd = [];

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
    }
}
