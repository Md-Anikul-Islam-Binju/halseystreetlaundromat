@extends('frontend.layout')
@section('content')
    <br><br><br><br><br>
    <body class="section-bg">
       <div class="container">
         <div class="d-flex align-items-center justify-content-center min-vh-100 w-100 ">
            <div class="d-flex flex-column justify-content-center align-items-center gap-3 w-100 p-4 border rounded-5 shadow-sm bg-white">
                <br>   <br>
                <h1 class="text-center fw-bold my-4">Your Order List</h1>
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-wash-tab" data-bs-toggle="pill" data-bs-target="#pills-wash"
                            type="button" role="tab" aria-controls="pills-wash" aria-selected="true">Wash</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-dry-cleaning"
                            type="button" role="tab" aria-controls="pills-dry-cleaning" aria-selected="false">Dry Cleaning</button>
                    </li>
                </ul>


                <div class="tab-content w-100" id="pills-tabContent">

                    @if($order->isNotEmpty())
                    <div class="tab-pane fade show active w-100" id="pills-wash" role="tabpanel" aria-labelledby="pills-wash-tab" tabindex="0">
                        <ul class="responsive-table">
                            <li class="table-header row">
                                <div class="col-12 col-lg-2">Invoice No</div>
                                <div class="col-12 col-lg-2">order date</div>
                                <div class="col-12 col-lg-2">Total Price</div>
                                <div class="col-12 col-lg-1">Status</div>
                                <div class="col-12 col-lg-1">Invoice</div>
                            </li>
                            @foreach($order as $orderData)
                                <li class="table-row row">
                                    <div class="col-12 col-lg-2 data-label" data-bs-label="Invoice No">{{$orderData->invoice_number }}</div>
                                    <div class="col-12 col-lg-2 data-label" data-bs-label="Order Date">{{$orderData->order_date }}</div>

                                    @foreach($orderData->orderItems as $item)
                                        {{-- <div class="col-12 col-lg-2 data-label" data-bs-label="Bag name">{{$item->bag_name }}</div>--}}
                                        {{-- <div class="col-12 col-lg-2 data-label" data-bs-label="Quantity">{{$item->quantity }}</div>--}}
                                    @endforeach

                                    <div class="col-12 col-lg-2 data-label" data-bs-label="Total Price">{{$orderData->total_amount }}$USD</div>
                                    <div class="col-12 col-lg-1 data-label" data-bs-label="Status">
                                        @if($orderData->status == 'pending')
                                            <span class="">Pending</span>
                                        @elseif($orderData->status == 'completed')
                                            <span class="">Completed</span>
                                        @elseif($orderData->status == 'decline')
                                            <span class="">Decline</span>
                                        @endif
                                    </div>
                                    <div class="col-12 col-lg-1 data-label" data-bs-label="Invoice"><a
                                            href="{{route('user.order.invoice', $orderData->id)}}" class="btn btn-primary btn-sm">Invoice</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @elseif($order->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            No orders found.
                        </div>
                    @endif

                    @if($dryOrder->isNotEmpty())
                    <div class="tab-pane fade w-100" id="pills-dry-cleaning" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                    <ul class="responsive-table">
                            <li class="table-header row">
                                <div class="col-12 col-lg-2">Invoice No</div>
                                <div class="col-12 col-lg-2">order date</div>
                                <div class="col-12 col-lg-2">Total Price</div>
                                <div class="col-12 col-lg-1">Status</div>
                                <div class="col-12 col-lg-1">Invoice</div>
                            </li>
                            @foreach($dryOrder as $orderData)
                                <li class="table-row row">
                                    <div class="col-12 col-lg-2 data-label" data-bs-label="Invoice No">{{$orderData->invoice_number }}</div>
                                    <div class="col-12 col-lg-2 data-label" data-bs-label="Order Date">{{$orderData->order_date }}</div>

                                    @foreach($orderData->dryOrderItems as $item)
                                        {{-- <div class="col-12 col-lg-2 data-label" data-bs-label="Bag name">{{$item->bag_name }}</div>--}}
                                        {{-- <div class="col-12 col-lg-2 data-label" data-bs-label="Quantity">{{$item->quantity }}</div>--}}
                                    @endforeach

                                    <div class="col-12 col-lg-2 data-label" data-bs-label="Total Price">{{$orderData->total_amount + $orderData->payment->delivery_charge??0 }}$USD</div>
                                    <div class="col-12 col-lg-1 data-label" data-bs-label="Status">
                                        @if($orderData->status == 'pending')
                                            <span class="">Pending</span>
                                        @elseif($orderData->status == 'completed')
                                            <span class="">Completed</span>
                                        @elseif($orderData->status == 'decline')
                                            <span class="">Decline</span>
                                        @endif
                                    </div>
                                    <div class="col-12 col-lg-1 data-label" data-bs-label="Invoice"><a
                                            href="{{route('user.dry.order.invoice', $orderData->id)}}" class="btn btn-primary btn-sm">Invoice</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @elseif($dryOrder->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            No orders found.
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $order->links('pagination::bootstrap-4') }}
                </div>

            </div>
         </div>
       </div>
    </body>
    <br>
@endsection
