<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DryOrder;
use App\Models\Expense;
use App\Models\Order;
use Illuminate\Http\Request;
use Yoeunes\Toastr\Facades\Toastr;

class IncomeExpenseReportController extends Controller
{
//    public function income(Request $request)
//    {
//        $ordersQuery = Order::where('status','=','completed')->with('orderItems')->latest();
//        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
//            $start_date = $request->input('start_date');
//            $end_date = $request->input('end_date');
//            $ordersQuery->whereBetween('order_date', [$start_date, $end_date]);
//        }
//        if ($request->has('pic_spot') && $request->input('pic_spot') != '') {
//            $picSpot = $request->input('pic_spot');
//            $ordersQuery->where('pic_spot', $picSpot);
//        }
//        if ($request->has('delivery_speed_type') && $request->input('delivery_speed_type') != '') {
//            $deliverySpeed = $request->input('delivery_speed_type');
//            $ordersQuery->where('delivery_speed_type', $deliverySpeed);
//        }
//        $orders = $ordersQuery->paginate(100);
//        return view('admin.pages.account.income', compact('orders'));
//    }

    public function income(Request $request)
    {
        // Initialize queries for both order types
        $washOrdersQuery = Order::where('status', 'completed')
            ->with('orderItems');

        $dryOrdersQuery = DryOrder::where('status', 'completed')
            ->with(['dryOrderItems', 'payment' => function($query) {
                $query->where('order_type', 'dry');
            }]);

        // Apply date filter if provided
        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            $washOrdersQuery->whereBetween('order_date', [$start_date, $end_date]);
            $dryOrdersQuery->whereBetween('order_date', [$start_date, $end_date]);
        }

        // Apply order type filter if provided
        if ($request->has('order_type') && $request->order_type) {
            if ($request->order_type === 'wash') {
                $dryOrdersQuery->whereRaw('1 = 0'); // Return no results for dry orders
            } elseif ($request->order_type === 'dry') {
                $washOrdersQuery->whereRaw('1 = 0'); // Return no results for wash orders
            }
        }

        // Get paginated results separately
        $washOrders = $washOrdersQuery->paginate(50); // Half of your desired total
        $dryOrders = $dryOrdersQuery->paginate(50); // Half of your desired total

        $washTotal = $washOrdersQuery->sum('total_amount');
        $dryTotal = $dryOrdersQuery->sum('total_amount');
        $grandTotal = $washTotal + $dryTotal;

        // Create a custom paginator for the merged collection
        $allOrders = new \Illuminate\Pagination\LengthAwarePaginator(
            $washOrders->merge($dryOrders)->sortByDesc('order_date'),
            $washOrders->total() + $dryOrders->total(),
            100, // Your desired per page count
            $request->page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        return view('admin.pages.account.income', compact('allOrders','grandTotal'));
    }

    public function index(Request $request)
    {
        $expenseQuery = Expense::latest();
        // Filter by Date Range if both start_date and end_date are provided
        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            // Filter orders by the provided date range (inclusive)
            $expenseQuery->whereBetween('date', [$start_date, $end_date]);
        }
        // Filter by expense_type if provided
        if ($request->has('expense_type') && $request->expense_type) {
            $expenseQuery->where('expense_type', $request->expense_type);
        }
        $expense = $expenseQuery->get();
        $totalAmount = $expense->sum('amount');
        return view('admin.pages.account.expense', compact('expense', 'totalAmount'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric',
            ]);
            $expense = new Expense();
            $expense->title = $request->title;
            $expense->expense_type = $request->expense_type;
            $expense->date = $request->date;
            $expense->amount = $request->amount;
            $expense->details = $request->details;
            if ($request->image) {
                $file = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images/expense'), $file);
                $expense->image = $file;
            }
            $expense->save();
            Toastr::success('Expense Added Successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric',
            ]);
            $expense = Expense::find($id);
            $expense->title = $request->title;
            $expense->expense_type = $request->expense_type;
            $expense->date = $request->date;
            $expense->amount = $request->amount;
            $expense->details = $request->details;
            if ($request->image) {
                $file = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images/expense'), $file);
                $expense->image = $file;
            }
            $expense->save();
            Toastr::success('Expense Updated Successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $expense = Expense::find($id);
            $expense->delete();
            Toastr::success('Expense Deleted Successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
