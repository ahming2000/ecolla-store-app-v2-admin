<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        // Get request value
        $paginate = request('paginate') ?? 5;
        $created_at = request('created_at') ?? "";
        $mode = request('mode') ?? "";

        // Generate Where Clause for SQL Query
        $created_at_filterClause = $this->generateFilterClause($created_at, $this->ORDER_FILTER_CREATED_AT, true);
        $mode_filterClause = $this->generateFilterClause($mode, $this->ORDER_FILTER_MODE);

        $whereClause = $this->combineWhereClause([
            $created_at_filterClause,
            $mode_filterClause,
        ]);

        // Query all related Order
        if ($whereClause != ""){
            $orders = Order::whereRaw($whereClause)
                ->orderBy('created_at', 'desc')
                ->paginate($paginate);
        } else{
            $orders = Order::orderBy('created_at', 'desc')
                ->paginate($paginate);
        }

        // Set pagination links parameter
        $orders->withPath('/order' . $this->generateParameter(
                [
                    'paginate' => $paginate,
                    'created_at' => $created_at,
                    'mode' => $mode,
                ])
        );

        // Generate parameter for filtering (search, category, paginate)
        $params = [
            'paginate' => $this->generateParameter(['created_at' => $created_at, 'mode' => $mode], true),
            'created_at' => $this->generateParameter(['paginate' => $paginate, 'mode' => $mode], true),
            'mode' => $this->generateParameter(['paginate' => $paginate, 'created_at' => $created_at], true),
        ];

        return view('order.index', compact('orders', 'params'));
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
        return $pdf->download('ecolla-order-' . $order->code . '.pdf');

        // $view = \View::make('pdf.order')->with('order', $order);
        // $html = $view->render();
        // $font_directory = public_path('fonts');

        // // convert TTF font to TCPDF format and store it on the fonts folder
        // $fontname = PDF::AddFont($font_directory . '\\MicrosoftYaHei-01.ttf');
        // // use the font
        // PDF::SetFont($fontname, '', 14, '', false);

        // PDF::SetTitle('Ecolla Order ' . $order->code);
        // PDF::AddPage();

        // PDF::writeHTML($html, true, false, true, false, '');
        // PDF::Output('Ecolla_Order_' . $order->code . '.pdf');
    }
}
