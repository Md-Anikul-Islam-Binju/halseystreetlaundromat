<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DryOrder;
use App\Models\LoginLog;
use App\Models\News;
use App\Models\Order;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\Showcase;
use App\Models\Team;
use App\Models\Training;
use App\Models\Venue;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
       //dd('admin');
       $loginLog = LoginLog::orderBy('last_login','desc')->limit(10)->get();

       //Dry Order
       $dryOrders = DryOrder::with('user', 'dryOrderItems')->where('status','=','Pending')->latest()->get();
       $orders = Order::with('user', 'orderItems')->where('status','=','Pending')->latest()->get();
       return view('admin.dashboard', compact('loginLog', 'dryOrders' ,'orders' ));
    }

    public function unauthorized()
    {
        return view('admin.unauthorized');
    }





}
