<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * OrdersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $orders = Order::orderBy('created_at', 'desc')->paginate(5);
        return view('order.index', compact('orders'));
    }

    public function edit(Order $order){
        return view('order.edit', compact('order'));
    }

    public function update(Order $order){

        $status = request('status');

        if($order->mode == 'delivery' && $status != 'pending'){
            $data = request()->validate([
               'delivery_id' => 'required'
            ]);
            $order->update(['status' => $status, 'delivery_id' => $data['delivery_id']]);
        } else{
            $order->update(['status' => $status]);
        }

        session()->flash('message', '保存成功！');
        header('refresh: 0');
    }
}
