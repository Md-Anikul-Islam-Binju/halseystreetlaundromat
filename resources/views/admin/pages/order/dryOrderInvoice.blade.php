@extends('admin.app')
@section('admin_content')
    {{-- CKEditor CDN --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Wings</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Halsey street laundromat</a></li>
                        <li class="breadcrumb-item active">Invoice!</li>
                    </ol>
                </div>
                <h4 class="page-title">Invoice!</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!-- Invoice Logo-->
                    <div class="clearfix">
                        <div class="float-start mb-3">
                            <img src="{{URL::TO('frontend/images/logo/logo-1.webp')}}" alt="dark logo" height="80">
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
                                <p class="text-muted fs-13">
                                    Please review your invoice carefully and ensure all details are correct. If you have any questions or notice any discrepancies, do not hesitate to contact us.
                                </p>
                            </div>

                        </div>
                        <div class="col-sm-4 offset-sm-2">
                            <div class="mt-3 float-sm-end">
                                <p class="fs-13"><strong>Order Date: </strong> &nbsp;&nbsp;&nbsp; {{  Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</p>
                                <p class="fs-13"><strong>Order Status: </strong> <span class="badge bg-success ">
                                          @if($order->status == 'Pending')
                                            <span class=" badge-outline-primary">Pending</span>
                                        @elseif($order->status == 'Order Accepted')
                                            <span class=" badge-outline-info">Order Accepted</span>
                                        @elseif($order->status == 'In process')
                                            <span class=" badge-outline-warning">In process</span>
                                        @elseif($order->status == 'Wait for deliver')
                                            <span class=" badge-outline-secondary">Wait for deliver</span>
                                        @elseif($order->status == 'Completed')
                                            <span class=" badge-outline-success">Completed</span>
                                        @elseif($order->status == 'Canceled')
                                            <span class=" badge-outline-danger">Canceled</span>
                                        @endif
                                    </span></p>
                                <p class="fs-13"><strong>INVOICE : </strong> <span class="float-end">
                                       <b> #{{$order->invoice_number }}</b>
                                    </span></p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-6">
                            <h6 class="fs-14">Address</h6>
                            <address>
                                {{$order->user->phone}}<br>
                                {{$order->user->address}}
                            </address>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-centered table-hover table-borderless mb-0 mt-3">
                                    <thead class="border-top border-bottom bg-light-subtle border-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>is Crease </th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->dryOrderItems as $index => $item)
                                        <tr>
                                            <td class="">{{$index+1}}</td>
                                            <td>
                                                {{ $item->service->title }}
                                            </td>
                                            <td>
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="{{ $item->is_crease == 1 ? 'text-success' : 'text-danger' }}">
                                                {{ $item->is_crease == 1 ? 'Yes' : 'Not Need' }}
                                            </td>
                                            <td>
                                                {{ $item->price }}
                                            </td>
                                            <td>
                                                {{ $item->total_price }}
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="clearfix pt-3">
                                <h6 class="text-muted fs-14">Notes:</h6>
                                <small>
                                    All accounts are to be paid within 7 days from receipt of
                                    invoice. To be paid by cheque or credit card or direct payment
                                    online. If account is not paid within 7 days the credits details
                                    supplied as confirmation of work undertaken will be charged the
                                    agreed quoted fee noted above.
                                </small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-end mt-3 mt-sm-0">
                                <div class="float-end mt-3 mt-sm-0">
                                    @if($order->coupon_id!=null)
                                        @php
                                            $coupon = \App\Models\Coupon::find($order->coupon_id);
                                        @endphp
                                        <h5>Actual Laundry Cost : ${{ number_format($order->payment->total_amount, 2) - number_format($order->payment->delivery_charge, 2) + number_format($coupon->discount_amount, 2) }} USD</h5>
                                        <h5>Laundry Cost Discount: ${{ number_format($coupon->discount_amount, 2) }} USD</h5>
                                    @else
                                    @endif
                                    <h5>Laundry Cost : ${{ number_format($order->payment->total_amount, 2) }} USD</h5>
                                    <h5>Delivery Charge : ${{ number_format($order->payment->delivery_charge, 2) }} USD</h5>
                                    <h4>Total : ${{ number_format($order->payment->total_amount, 2) + number_format($order->payment->delivery_charge, 2) }} USD</h4>
                                </div>
                            </div>
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

@endsection
