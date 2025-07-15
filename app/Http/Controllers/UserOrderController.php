<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DryOrder;
use App\Models\DryOrderItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yoeunes\Toastr\Facades\Toastr;

class UserOrderController extends Controller
{


    public function userThankYou()
    {
        return view('frontend.pages.thankYou');
    }






    public function userDryOrder()
    {
        $categories = Category::where('status', 1)
            ->with(['services' => function($query) {
                $query->where('status', 1);
            }])
            ->get();
        return view('frontend.pages.userDryOrder', compact('categories'));
    }



    public function userDryOrderStore(Request $request)
    {

        //dd($request->all());
        // Validate the request
        $validated = $request->validate([
            'items' => 'required|array',
            'payment_method' => 'required|string',
            'card_no' => 'required_if:payment_method,Card',
            'card_exp_date' => 'required_if:payment_method,Card',
            'card_security_code' => 'required_if:payment_method,Card',
            'zip_code' => 'required_if:payment_method,Card',
        ]);

        // Calculate total amount from items
        $totalAmount = 0;
        $orderItems = [];

//        if ($request->delivery_speed_type == 'Standard') {
//            $delivery_charge = 30;
//        } elseif ($request->delivery_speed_type == 'Express') {
//            $delivery_charge = 50;
//        } else {
//            $delivery_charge = 0;
//        }



        foreach ($request->items as $serviceId => $quantity) {
            if ($quantity > 0) {
                $service = Service::find($serviceId);
                $itemTotal = $service->price * $quantity;
                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'dry_order_id' => null, // Will be set after order creation
                    'service_id' => $serviceId,
                    'quantity' => $quantity,
                    'price' => $service->price,
                    'total_price' => $itemTotal,
                ];
            }
        }

        // Create the order
        $order = DryOrder::create([
            'customer_id' => auth()->id(),
            'invoice_number' => 'DRY-' . time(),
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'address' => $request->address,
            'pic_spot' => $request->pic_spot,
            'instructions' => $request->instructions,
            'instructions_text' => $request->instructions_text,

            'delivery_speed_type' => 'Standard',


            'detergent_type' => $request->detergent_type?? 'Regular',
            'is_delicate_cycle' => $request->is_delicate_cycle ?? 0,
            'is_hang_dry' => $request->is_hang_dry ?? 0,
            'is_return_hanger' => $request->is_return_hanger ?? 0,
            'is_additional_request' => $request->is_additional_request ?? 0,


            'coverage_type' => $request->coverage_type,

        ]);

        // Add order items
        foreach ($orderItems as &$item) {
            $item['dry_order_id'] = $order->id;
            DryOrderItem::create($item);
        }

        // Process payment
        if ($request->payment_method === 'Card') {
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'Card',
                'card_no' => $request->card_no,
                'card_exp_date' => $request->card_exp_date,
                'card_security_code' => $request->card_security_code,
                'zip_code' => $request->zip_code,
                'payment_date' => now(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'order_type'=> 'dry',
                'delivery_charge' => 0,
            ]);
        }
        Toastr::success('Order Successfully', 'Success');
        return redirect()->route('user.thankyou');
    }












    public function userOrderDecision()
    {
        return view('frontend.pages.userOrderDecision');
    }


    public function userOrder()
    {
        return view('frontend.pages.userOrder');
    }
    public function orderStore(Request $request)
    {
        // Validate request data
        $request->validate([
            'address' => 'required|string',
            'pic_spot' => 'required|string',
            'delivery_speed_type' => 'required|string',
            'detergent_type' => 'required|string',
            'coverage_type' => 'required|string',
        ]);
        // Define delivery charge based on delivery speed type
        if ($request->delivery_speed_type == 'Standard') {
            $delivery_charge = 30;
            $rate_per_bag = 1.60;
        } elseif ($request->delivery_speed_type == 'Express') {
            $delivery_charge = 50;
            $rate_per_bag = 3.00;
        } else {
            $delivery_charge = 0;
            $rate_per_bag = 0;
        }
        $total_amount = 0;
        // Calculate amount for each bag type
        $total_amount += $request->small_bag * $rate_per_bag;
        $total_amount += $request->regular_bag * $rate_per_bag;
        $total_amount += $request->large_bag * $rate_per_bag;
        $total_amount += $request->overSized_bag * $rate_per_bag;
        // If OverSized item is included, add an extra $8
        if ($request->overSized_bag > 0) {
            $total_amount += 8;
        }
        // Add the delivery charge to the total amount
        $total_amount += $delivery_charge;
        // Create a new order
        $order = Order::create([
            'customer_id' => Auth::id(), // Use Auth to get the logged-in user's id
            'address' => $request->address,
            'pic_spot' => $request->pic_spot,
            'instructions' => $request->instructions,
            'instructions_text' => $request->instructions_text,
            'delivery_speed_type' => $request->delivery_speed_type,
            'detergent_type' => $request->detergent_type,
            'is_delicate_cycle' => $request->is_delicate_cycle ?? 0,
            'is_hang_dry' => $request->is_hang_dry ?? 0,
            'is_return_hanger' => $request->is_return_hanger ?? 0,
            'is_additional_request' => $request->is_additional_request ?? 0,
            'coverage_type' => $request->coverage_type,
            'order_date' => now(),
            'invoice_number' => strtoupper(uniqid('INV-')),
            'total_amount' => $total_amount,
            'status' => 'pending',
        ]);

        // Create order items (based on bags selected)
        $orderItemsData = [
            ['order_id' => $order->id, 'bag_name' => 'Small', 'quantity' => $request->small_bag],
            ['order_id' => $order->id, 'bag_name' => 'Regular', 'quantity' => $request->regular_bag],
            ['order_id' => $order->id, 'bag_name' => 'Large', 'quantity' => $request->large_bag],
            ['order_id' => $order->id, 'bag_name' => 'OverSized', 'quantity' => $request->overSized_bag],
        ];
        foreach ($orderItemsData as $item) {
            if ($item['quantity'] > 0) {
                OrderItem::create($item);
            }
        }
        // If payment details are provided, create a payment record
        $paymentData = [
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'payment_date' => now(),
            'delivery_charge' => $delivery_charge,
            'total_amount' => $total_amount,
            'status' => 'pending',
            'order_type' => 'wash',
        ];
        // If payment method is 'card', add card details to the payment data
        if ($request->payment_method === 'Card') {
            $paymentData['card_no'] = $request->card_no;
            $paymentData['card_security_code'] = $request->card_security_code;
            $paymentData['country'] = $request->country?? null;
            $paymentData['zip_code'] = $request->zip_code;
            $paymentData['card_exp_date'] = $request->card_exp_date;
        }
        // Create Payment record
        Payment::create($paymentData);
        Toastr::success('Order Successfully', 'Success');
        return redirect()->route('user.thankyou');
    }

    public function userOrderList()
    {
        $order = Order::where('customer_id', Auth::id())->with('orderItems')->orderBy('id', 'desc')->paginate(20);
        $dryOrder = DryOrder::where('customer_id', Auth::id())->with('dryOrderItems')->orderBy('id', 'desc')->paginate(20);
        return view('frontend.pages.userOrderList', compact('order', 'dryOrder'));
    }

    public function userInvoice($id)
    {
        $order = Order::with(['user', 'orderItems', 'payment' => function($query) {
            $query->where('order_type', 'wash');
        }])->findOrFail($id);
        return view('frontend.pages.userInvoice', compact('order'));
    }

    public function userDryOrderInvoice($id)
    {
        $order = DryOrder::with(['user', 'dryOrderItems.service', 'payment' => function($query) {
            $query->where('order_type', 'dry');
        }])->findOrFail($id);
        return view('frontend.pages.userInvoiceDryCleaning', compact('order'));
    }



}
