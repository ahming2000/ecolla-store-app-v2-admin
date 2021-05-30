<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;

class AccountsController extends Controller
{

    /**
     * AccountsController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access:admin', 'access:status_check']);
    }

    public function index()
    {
        $users = User::where('role', '=', 'employee')
            ->where('status', '!=', 'deleted')
            ->get();

        return view('account.index', compact('users'));
    }

    public function store()
    {
        $userData = request()->validate([
            'email' => ['email', 'required', 'unique:users'],
            'name' => 'required',
            'password' => ['required', 'confirmed']
        ]);

        $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);

        $permissions = request('permissions');

        $user = new User();
        foreach ($userData as $key => $value) {
            $user->setAttribute($key, $value);
        }
        $user->save();

        $userPermission = new UserPermission();
        foreach ($permissions as $key => $value) {
            $userPermission->setAttribute($key, '1');
        }
        $user->permission()->save($userPermission);

        return redirect('/account')->with('message', "账号 " . $user->name . " 创建成功！");
    }

    public function update(User $user)
    {
        $userData = request()->validate([
            'name' => 'required',
            'password' => ['required', 'confirmed']
        ]);

        $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);

        $permissions = request('permissions');

        $permissionAttributes = UserPermission::getAllAttributes();

        foreach ($permissionAttributes as $attr) {
            if (array_key_exists($attr, $permissions)) {
                $permissions[$attr] = '1';
            } else {
                $permissions[$attr] = '0';
            }
        }

        $user->update($userData);
        $user->permission()->update($permissions);

        return redirect('/account')->with('message', "账号 " . $user->name . " 属性修改成功！");
    }

    public function manage(User $user)
    {
        $action = request('action');

        switch ($action) {
            case 'activate':
                $user->update(['status' => 'enabled']);
                $message = "账号 " . $user->name . " 已激活！";
                break;
            case 'deactivate':
                $user->update(['status' => 'disabled']);
                $message = "账号 " . $user->name . " 已停用！";
                break;
            case 'delete':
                $user->update(['status' => 'deleted']);
                $message = "账号 " . $user->name . " 已删除！";
                break;
            default:
        }

        return response()->json(['user_status' => $user->status, 'message' => $message]);
    }
}
