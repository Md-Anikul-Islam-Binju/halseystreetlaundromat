<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\DryOrder;
use App\Models\DryOrderItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
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

//    public function userDryOrderStore(Request $request)
//    {
//        $validated = $request->validate([
//            'items' => 'required|array',
//            'payment_method' => 'required|string',
//        ]);
//        $totalAmount = 0;
//        $orderItems = [];
//        foreach ($request->items as $serviceId => $quantity) {
//            if ($quantity > 0) {
//                $service = Service::find($serviceId);
//                $itemTotal = $service->price * $quantity;
//                $totalAmount += $itemTotal;
//
//                $orderItems[] = [
//                    'dry_order_id' => null, // Will be set after order creation
//                    'service_id' => $serviceId,
//                    'quantity' => $quantity,
//                    'price' => $service->price,
//                    'total_price' => $itemTotal,
//                    'is_crease' => isset($request->crease[$serviceId]) ? 1 : 0,
//                ];
//            }
//        }
//
//        $coupon = null;
//        $coupon_id = null;
//        $discount_amount = 0;
//
//        if ($request->filled('coupon_code')) {
//            $coupon = Coupon::where('coupon_code', $request->coupon_code)
//                ->where('status', 1)
//                ->first();
//
//            if (!$coupon) {
//                return back()->with('error', 'Invalid or inactive coupon code.');
//            }
//
//            $now = Carbon::now();
//
//            if ($coupon->start_date && $now->lt(Carbon::parse($coupon->start_date))) {
//                return back()->with('error', 'This coupon is not active yet.');
//            }
//
//            if ($coupon->end_date && $now->gt(Carbon::parse($coupon->end_date))) {
//                return back()->with('error', 'This coupon has expired.');
//            }
//
//            if ($coupon->amount_spend && $totalAmount < $coupon->amount_spend) {
//                return back()->with('error', "You need to spend at least $coupon->amount_spend to use this coupon.");
//            }
//
//            $usedCount = DryOrder::where('customer_id', auth()->id())
//                ->where('coupon_id', $coupon->id)
//                ->count();
//
//            if ($coupon->use_limit && $usedCount >= $coupon->use_limit) {
//                return back()->with('error', "You have already used this coupon the maximum allowed times.");
//            }
//
//            // Apply discount
//            $discount_amount = $coupon->discount_amount;
//            $totalAmount -= $discount_amount;
//            $coupon_id = $coupon->id;
//        }
//        // Create the order
//        $order = DryOrder::create([
//            'customer_id' => auth()->id(),
//            'invoice_number' => 'DRY-' . time(),
//            'order_date' => now(),
//            'total_amount' => $totalAmount,
//            'status' => 'pending',
//            'address' => $request->address,
//            'pic_spot' => $request->pic_spot,
//            'instructions' => $request->instructions,
//            'instructions_text' => $request->instructions_text,
//            'delivery_speed_type' => 'Standard',
//            'detergent_type' => $request->detergent_type?? 'Regular',
//            'is_delicate_cycle' => $request->is_delicate_cycle ?? 0,
//            'is_hang_dry' => $request->is_hang_dry ?? 0,
//            'is_return_hanger' => $request->is_return_hanger ?? 0,
//            'is_additional_request' => $request->is_additional_request ?? 0,
//            'coverage_type' => $request->coverage_type,
//            'coupon_id' => $coupon_id,
//
//        ]);
//
//        // Add order items
//        foreach ($orderItems as &$item) {
//            $item['dry_order_id'] = $order->id;
//            DryOrderItem::create($item);
//        }
//        // Process payment
//        if ($request->payment_method === 'Card') {
//            Payment::create([
//                'order_id' => $order->id,
//                'payment_method' => 'Card',
//                'card_no' => $request->card_no,
//                'card_exp_date' => $request->card_exp_date,
//                'card_security_code' => $request->card_security_code,
//                'zip_code' => $request->zip_code,
//                'payment_date' => now(),
//                'total_amount' => $totalAmount,
//                'status' => 'pending',
//                'order_type'=> 'dry',
//                'delivery_charge' => 0,
//            ]);
//        }
//        Toastr::success('Order Successfully', 'Success');
//        return redirect()->route('user.thankyou');
//    }


    public function userDryOrderStore(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'payment_method' => 'required|string',
        ]);

        // Calculate total amount from items
        $totalAmount = 0;
        $orderItems = [];

        foreach ($request->items as $serviceId => $quantity) {
            if ($quantity > 0) {
                $service = Service::find($serviceId);
                $itemTotal = $service->price * $quantity;
                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'dry_order_id' => null,
                    'service_id' => $serviceId,
                    'quantity' => $quantity,
                    'price' => $service->price,
                    'total_price' => $itemTotal,
                    'is_crease' => isset($request->crease[$serviceId]) ? 1 : 0,
                ];
            }
        }

        $coupon = null;
        $coupon_id = null;
        $discount_amount = 0;

        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('coupon_code', $request->coupon_code)
                ->where('status', 1)
                ->first();

            if (!$coupon) {
                return back()->with('error', 'Invalid or inactive coupon code.');
            }

            $now = Carbon::now();

            if ($coupon->start_date && $now->lt(Carbon::parse($coupon->start_date))) {
                return back()->with('error', 'This coupon is not active yet.');
            }

            if ($coupon->end_date && $now->gt(Carbon::parse($coupon->end_date))) {
                return back()->with('error', 'This coupon has expired.');
            }

            if ($coupon->amount_spend && $totalAmount < $coupon->amount_spend) {
                return back()->with('error', "You need to spend at least $coupon->amount_spend to use this coupon.");
            }

            $usedCount = DryOrder::where('customer_id', auth()->id())
                ->where('coupon_id', $coupon->id)
                ->count();

            if ($coupon->use_limit && $usedCount >= $coupon->use_limit) {
                return back()->with('error', "You have already used this coupon the maximum allowed times.");
            }

            $discount_amount = $coupon->discount_amount;
            $totalAmount -= $discount_amount;
            $coupon_id = $coupon->id;
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
            'detergent_type' => $request->detergent_type ?? 'Regular',
            'is_delicate_cycle' => $request->is_delicate_cycle ?? 0,
            'is_hang_dry' => $request->is_hang_dry ?? 0,
            'is_return_hanger' => $request->is_return_hanger ?? 0,
            'is_additional_request' => $request->is_additional_request ?? 0,
            'coverage_type' => $request->coverage_type,
            'coupon_id' => $coupon_id,
        ]);

        foreach ($orderItems as &$item) {
            $item['dry_order_id'] = $order->id;
            DryOrderItem::create($item);
        }

        if ($request->payment_method === 'Card') {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            try {
                Charge::create([
                    'amount' => round($totalAmount * 100),
                    'currency' => 'usd',
                    'description' => 'Laundry Order Payment',
                    'source' => $request->stripeToken,
                    'metadata' => [
                        'card_holder_name' => $request->card_holder_name,
                    ]
                ]);

                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'Card',
                    'payment_date' => now(),
                    'total_amount' => $totalAmount,
                    'status' => 'paid',
                    'order_type' => 'dry',
                    'delivery_charge' => 0,
                ]);
            } catch (\Exception $e) {
                $order->delete();
                return back()->withErrors(['error' => 'Payment failed: ' . $e->getMessage()]);
            }
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

        $request->validate([
            'address' => 'required|string',
            'pic_spot' => 'required|string',
            'delivery_speed_type' => 'required|string',
            'detergent_type' => 'required|string',
            'coverage_type' => 'required|string',
            'coupon_code' => 'nullable|string',
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
        $coupon = null;
        $coupon_id = null;
        $discount_amount = 0;

        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('coupon_code', $request->coupon_code)
                ->where('status', 1)
                ->first();

            if (!$coupon) {
                return back()->with('error', 'Invalid or inactive coupon code.');
            }

            $now = Carbon::now();

            if ($coupon->start_date && $now->lt(Carbon::parse($coupon->start_date))) {
                return back()->with('error', 'This coupon is not active yet.');
            }

            if ($coupon->end_date && $now->gt(Carbon::parse($coupon->end_date))) {
                return back()->with('error', 'This coupon has expired.');
            }

            if ($coupon->amount_spend && $total_amount < $coupon->amount_spend) {
                return back()->with('error', "You need to spend at least $coupon->amount_spend to use this coupon.");
            }

            // Usage limit validation (per user, optional)
            $usedCount = Order::where('customer_id', Auth::id())
                ->where('coupon_id', $coupon->id)
                ->count();

            if ($coupon->use_limit && $usedCount >= $coupon->use_limit) {
                return back()->with('error', "You have already used this coupon the maximum allowed times.");
            }
            // Apply discount
            $discount_amount = $coupon->discount_amount;
            $total_amount -= $discount_amount;
            $coupon_id = $coupon->id;
        }


        // Handle Stripe payment if payment method is Card
        if ($request->payment_method === 'Card') {
            try {
                Stripe::setApiKey(env('STRIPE_SECRET'));

                Charge::create([
                    'amount' => round($total_amount * 100), // Stripe works with cents
                    'currency' => 'usd',
                    'description' => 'Laundry Order Payment',
                    'source' => $request->stripeToken,
                    'metadata' => [
                        'card_holder_name' => $request->card_holder_name,
                    ]
                ]);
            } catch (ApiErrorException $e) {
                return back()->with('error', 'Payment failed: ' . $e->getMessage());
            }
        }

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
            'coupon_id' => $coupon_id,
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
        // Payment record
        Payment::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'payment_date' => now(),
            'delivery_charge' => $delivery_charge,
            'total_amount' => $total_amount,
            'status' => 'paid',
            'order_type' => 'wash',
        ]);
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
