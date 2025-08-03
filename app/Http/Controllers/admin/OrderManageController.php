<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DryOrder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Yoeunes\Toastr\Facades\Toastr;

class OrderManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Gate::allows('order-manage')) {
                return redirect()->route('unauthorized.action');
            }
            return $next($request);
        })->only('index');
    }

    public function index(Request $request)
    {
        // Initialize the query to get orders
        $ordersQuery = Order::with('user', 'orderItems')->latest();
        // Filter by Date Range if both start_date and end_date are provided
        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            // Filter orders by the provided date range (inclusive)
            $ordersQuery->whereBetween('order_date', [$start_date, $end_date]);
        }

        // Filter by Status if status is provided
        if ($request->has('status') && $request->input('status') != '') {
            $status = $request->input('status');
            $ordersQuery->where('status', $status);
        }
        $orders = $ordersQuery->paginate(10);
        return view('admin.pages.order.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'orderItems', 'payment' => function($query) {
            $query->where('order_type', 'wash');
        }])->findOrFail($id);

        return view('admin.pages.order.show', compact('order'));
    }

    public function changeStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id); // better to use findOrFail

        $validated = $request->validate([
            'status' => 'required|string',
        ]);

        $order->status = $validated['status'];
        $order->save();

        Toastr::success('Order status updated successfully', 'Success');
        return redirect()->back();
    }

    public function invoice($id)
    {
        $order = Order::with(['user', 'orderItems', 'payment' => function($query) {
            $query->where('order_type', 'wash');
        }])->findOrFail($id);
        return view('admin.pages.order.invoice', compact('order'));
    }

    public function destroy($id)
    {
        try {
            $order = Order::find($id);
            $order->delete();
            Toastr::success('Order Deleted Successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    public function dryOrder(Request $request)
    {
        // Initialize the query to get orders
        $ordersQuery = DryOrder::with('user', 'dryOrderItems')->latest();
        // Filter by Date Range if both start_date and end_date are provided
        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            // Filter orders by the provided date range (inclusive)
            $ordersQuery->whereBetween('order_date', [$start_date, $end_date]);
        }

        // Filter by Status if status is provided
        if ($request->has('status') && $request->input('status') != '') {
            $status = $request->input('status');
            $ordersQuery->where('status', $status);
        }
        $dryOrders = $ordersQuery->paginate(10);
        return view('admin.pages.order.dryOrder', compact('dryOrders'));
    }

    public function dryOrderShow($id)
    {
        $order = DryOrder::with(['user', 'dryOrderItems.service', 'payment' => function($query) {
            $query->where('order_type', 'dry');
        }])->findOrFail($id);
        return view('admin.pages.order.dryOrderShow', compact('order'));
    }


    public function dryOrderChangeStatus(Request $request, $id)
    {
        $order = DryOrder::findOrFail($id); // better to use findOrFail

        $validated = $request->validate([
            'status' => 'required|string',
        ]);

        $order->status = $validated['status'];
        $order->save();

        Toastr::success('Dry Order status updated successfully', 'Success');
        return redirect()->back();
    }




    public function dryOrderDestroy($id)
    {
        try {
            $order = DryOrder::find($id);
            $order->delete();
            Toastr::success('Dry Order Deleted Successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    public function dryOrderInvoice($id)
    {
        $order = DryOrder::with(['user', 'dryOrderItems', 'payment' => function($query) {
            $query->where('order_type', 'dry');
        }])->findOrFail($id);
        return view('admin.pages.order.dryOrderInvoice', compact('order'));
    }



}
