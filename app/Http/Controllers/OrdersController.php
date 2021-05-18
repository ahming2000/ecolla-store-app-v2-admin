<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use PDF;

class OrdersController extends Controller
{
    /**
     * OrdersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->paginate(5);
        return view('order.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('order.edit', compact('order'));
    }

    public function edit(Order $order){

    }

    public function update(Order $order)
    {

        $status = request('status');

        if ($order->mode == 'delivery' && $status != 'pending') {
            $data = request()->validate([
                'delivery_id' => 'required'
            ]);
            $order->update(['status' => $status, 'delivery_id' => $data['delivery_id']]);
        } else {
            $order->update(['status' => $status]);
        }

        session()->flash('message', '保存成功！');
        header('refresh: 0');
    }

    // Generate PDF
    public function createPDF(Order $order)
    {
        // retreive all records from db
        $data = $order;

        // share data to view
        view()->share('order', $data);
        $pdf = PDF::loadView('pdf.order', $data);

        // download PDF file with download method
        return $pdf->download('ecolla-order-' . $order->id . '.pdf');
    }
}
