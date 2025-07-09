@extends('frontend.layout')
@section('content')
    <br>  <br>
    <section class="invoice-section py-5">
        <div class="container">
            <div class="page-title-box text-center mb-3">
                <h1 class="page-title">Invoice!</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Invoice Logo-->
                            <div class="clearfix">
                                <div class="float-start mb-3">
                                    <img src="{{URL::TO('frontend/images/logo/logo-1.webp')}}" alt="dark logo"  width="80">
                                </div>
                                <div class="float-end">
                                    <h4 class="m-0 d-print-none">Invoice</h4>
                                </div>
                            </div>

                            <!-- Invoice Detail-->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="float-end mt-3">
                                        <p><b>Hello, {{$order->user->first_name}} {{$order->user->last_name}}</b></p>
                                        <p class="text-muted small">
                                            Please review your invoice carefully and ensure all details are correct. If you have any questions or notice any discrepancies, do not hesitate to contact us.
                                        </p>
                                    </div>

                                </div>
                                <div class="col-sm-4 offset-sm-2">
                                    <div class="mt-3 float-sm-end">
                                        <p class="small"><strong>Order Date: </strong> &nbsp;&nbsp;&nbsp; {{  Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</p>
                                        <p class="small"><strong>Order Status: </strong> <span class="badge bg-success ms-1">
                                                @if($order->status == 'pending')
                                                    <span >Pending</span>
                                                @elseif($order->status == 'completed')
                                                    <span >Completed</span>
                                                @elseif($order->status == 'canceled')
                                                    <span >Canceled</span>
                                                @endif
                                            </span></p>
                                        <p class="small"><strong>Invoice : </strong> <span class="float-end">#{{$order->invoice_number }}</span></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <h6 class="fs-14">Address</h6>
                                    <address>
                                        {{$order->user->phone}}<br>
                                        {{$order->address}}
                                    </address>
                                </div>
                            </div>
                            @php
                                if ($order->delivery_speed_type == 'Standard') {
                                    $delivery_charge = 30;
                                    $rate_per_bag = 1.60;
                                } elseif ($order->delivery_speed_type == 'Express') {
                                    $delivery_charge = 50;
                                    $rate_per_bag = 3.00;
                                } else {
                                    $delivery_charge = 0;
                                    $rate_per_bag = 0;
                                }
                            @endphp
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-centered table-hover table-borderless mb-0 mt-3">
                                            <thead class="border-top border-bottom bg-light-subtle border-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Bag Item</th>
                                                <th>Quantity</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($order->orderItems as $index => $item)
                                            <tr>
                                                <td class="">{{$index+1}}</td>
                                                <td>
                                                    {{ $item->bag_name }}
                                                </td>
                                                <td>
                                                    {{$item->quantity}}
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-6">
                                    <div class="clearfix pt-3">
                                        <h6 class="">Notes:</h6>
                                        <p class="small">
                                            All accounts are to be paid within 7 days from receipt of
                                            invoice. To be paid by cheque or credit card or direct payment
                                            online. If account is not paid within 7 days the credits details
                                            supplied as confirmation of work undertaken will be charged the
                                            agreed quoted fee noted above.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="float-end mt-3 mt-sm-0">
                                        <h5>Laundry Cost : ${{ number_format($order->payment->total_amount, 2) - number_format($order->payment->delivery_charge, 2) }} USD</h5>
                                        <h5>Delivery Charge : ${{ number_format($order->payment->delivery_charge, 2) }} USD</h5>
                                        <h3>Total : ${{ number_format($order->payment->total_amount, 2) }} USD</h3>
                                    </div>

{{--                                    <div class="float-end mt-3 mt-sm-0">--}}
{{--                                        <h3>${{ number_format($order->payment->total_amount, 2) }} USD</h3>--}}
{{--                                    </div>--}}
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="d-print-none mt-4">
                                <div class="text-center">
                                    <a href="javascript:window.print()" class="btn btn-primary"><i class="ri-printer-line"></i> Print</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
