<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\DryOrder;
use App\Models\DryOrderItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function store(Request $request)
    {


        // Validate request data
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
            'pic_spot' => 'required|string|max:255',
            'delivery_speed_type' => 'required|string|max:255',
            'detergent_type' => 'required|string|max:255',
            'order_items' => 'required|array',
            'order_items.*.bag_name' => 'required|string|max:255',
            'order_items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
        ]);

       // dd($request->all());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create Order
        $order = Order::create([
            'customer_id' => auth()->user()->id,
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
            'invoice_number' => strtoupper(uniqid('INV-')),
            'order_date' => now(),
            'total_amount' => $request->total_amount,
            'status' => 'pending',
        ]);

        // Create Order Items
        foreach ($request->order_items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'bag_name' => $item['bag_name'],
                'quantity' => $item['quantity'],
            ]);
        }

        // Create Payment
        $paymentData = [
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'payment_date' => now(),
            'delivery_charge' => $request->delivery_charge ?? 0,
            'total_amount' => $request->total_amount,
            'status' => 'pending',
            'order_type' => 'wash',
        ];

        // If payment method is 'card', add card details to the payment data
        if ($request->payment_method === 'Card') {
            $paymentData['card_no'] = $request->card_no;
            $paymentData['card_security_code'] = $request->card_security_code;
            $paymentData['country'] = $request->country;
            $paymentData['zip_code'] = $request->zip_code;
        }

        // Create Payment record
        $payment = Payment::create($paymentData);
        $orderWithDetails = Order::with(['payment', 'orderItems'])->find($order->id);


        // Return response
        return response()->json([
            'message' => 'Order created successfully!',
            'order' => $orderWithDetails,
        ], 201);
    }

    public function myOrder(Request $request)
    {
        $orders = Order::with(['orderItems', 'payment'])
            ->where('customer_id', $request->user()->id)
            ->latest()
            ->paginate(10);
        $dryOrder = DryOrder::where('customer_id', Auth::id())
            ->with('dryOrderItems')
            ->orderBy('id', 'desc')
            ->paginate(10);
        return response()->json([
            'message' => 'Your Order List!',
            'order' => $orders,
            'dryOrder' => $dryOrder,
        ], 201);
    }

    public function washOrder(Request $request)
    {
        $washOrder = Order::with(['orderItems', 'payment'])
            ->where('customer_id', $request->user()->id)
            ->latest()
            ->paginate(10);
//        return response()->json([
//            'washOrder' => $orders,
//        ], 201);
        return response()->json($washOrder);
    }


    public function dryOrder(Request $request)
    {
        $dryOrder = DryOrder::where('customer_id', Auth::id())
            ->with('dryOrderItems')
            ->orderBy('id', 'desc')
            ->paginate(10);
//        return response()->json([
//            'dryOrder' => $dryOrder,
//        ], 201);
        return response()->json($dryOrder);
    }

    public function userDryOrderStore(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            // New array format
            'order_items' => 'sometimes|array',
            'order_items.*.service_id' => 'required_with:order_items|exists:services,id',
            'order_items.*.quantity' => 'required_with:order_items|integer|min:1',

            // Legacy format
            'items' => 'sometimes|array',
            'items.*' => 'integer|min:0',

            // Payment fields
            'payment_method' => 'required|string|in:Card,Cash,Other',
            'card_no' => 'required_if:payment_method,Card|string|max:20',
            'card_exp_date' => 'required_if:payment_method,Card|string|max:5',
            'card_security_code' => 'required_if:payment_method,Card|string|max:4',
            'zip_code' => 'required_if:payment_method,Card|string|max:10',
        ]);

        try {
            // Normalize items
            $items = [];
            if ($request->has('order_items')) {
                foreach ($request->order_items as $item) {
                    if ($item['quantity'] > 0) {
                        $items[$item['service_id']] = $item['quantity'];
                    }
                }
            } else {
                foreach ($request->items as $serviceId => $quantity) {
                    if ($quantity > 0) {
                        $items[$serviceId] = $quantity;
                    }
                }
            }

            if (empty($items)) {
                return response()->json(['message' => 'No valid items provided'], 400);
            }

            // Calculate total and prepare order items
            $totalAmount = 0;
            $orderItems = [];
            foreach ($items as $serviceId => $quantity) {
                $service = Service::findOrFail($serviceId);
                $itemTotal = $service->price * $quantity;
                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'service_id' => $serviceId,
                    'quantity' => $quantity,
                    'price' => $service->price,
                    'total_price' => $itemTotal,
                ];
            }

            // Create order
            $order = DryOrder::create([
                'customer_id' => auth()->id(),
                'invoice_number' => 'DRY-' . time(),
                'order_date' => now(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // Create order items
            foreach ($orderItems as &$item) {
                $item['dry_order_id'] = $order->id;
                DryOrderItem::create($item);
            }

            // Process payment if card
            $payment = null;
            if ($request->payment_method === 'Card') {
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'Card',
                    'card_no' => $request->card_no,
                    'card_exp_date' => $request->card_exp_date,
                    'card_security_code' => '***',
                    'zip_code' => $request->zip_code,
                    'payment_date' => now(),
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'order_type' => 'dry',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order' => $order,
                    'items' => $orderItems,
                    'payment' => $payment
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
