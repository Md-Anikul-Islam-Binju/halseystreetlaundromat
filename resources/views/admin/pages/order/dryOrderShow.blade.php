@extends('admin.app')
@section('admin_content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Halsey Street Laundromat</a></li>
                        <li class="breadcrumb-item active">Dry Order Details Section!</li>
                    </ol>
                </div>
                <h4 class="page-title">Dry Order Details Section!</h4>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-end">
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="">Dry Order Information</h5>
                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editNewModalId{{$order->id}}">Change Dry Order Status</button>

                    <div class="modal fade" id="editNewModalId{{$order->id}}" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editNewModalLabel{{$order->id}}" aria-hidden="true">
                        <div class="modal-dialog  modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="addNewModalLabel{{$order->id}}">Order Status</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="{{route('dry.order.status.change',$order->id)}}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label for="example-select" class="form-label">Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                        <option value="canceled" {{ $order->status === 'canceled' ? 'selected' : '' }}>Canceled</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-primary" type="submit">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <p><strong>Customer Name:</strong> {{ $order->user->name }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                    <p><strong>Phone:</strong> {{ $order->user->phone }}</p>
                    <p><strong>Order Status:</strong>
                        @if($order->status == 'pending')
                            <span class="badge badge-outline-primary">Pending</span>
                        @elseif($order->status == 'completed')
                            <span class="badge badge-outline-info">Completed</span>
                        @elseif($order->status == 'canceled')
                            <span class="badge badge-danger">Canceled</span>
                        @else
                            <span class="badge badge-secondary">Unknown</span>
                        @endif
                    </p>

                </div>

            </div>

            <div class="card mb-4">
                <div class="card-header">Dry Order</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Address</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $order->invoice_number  }}</td>
                            <td>{{ $order->user->address }}</td>
                            <td>{{ $order->order_date }}</td>
                            <td>{{ $order->total_amount }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>



            <div class="card mb-4">
                <div class="card-header">Dry Order Items</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>is Crease </th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->dryOrderItems as $item)
                            <tr>
                                <td>{{$item->service->title}}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="{{ $item->is_crease == 1 ? 'text-success' : 'text-danger' }}">
                                    {{ $item->is_crease == 1 ? 'Yes' : 'Not Need' }}
                                </td>
                                <td>{{ $item->price }}</td>
                                <td>{{ $item->total_price }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Payment Information</div>
                <div class="card-body">
                    <p><strong>Payment Method:</strong> {{ $order->payment->payment_method }}</p>
                    <p><strong>Payment Status:</strong> {{ ucfirst($order->payment->status) }}</p>
                    <p><strong>Payment Date:</strong> {{ $order->payment->payment_date }}</p>
                    <p><strong>Delivery Charge:</strong> ${{ number_format($order->payment->delivery_charge, 2) }}</p>
                    <p><strong>Laundry Cost:</strong> ${{ number_format($order->payment->total_amount, 2) }}</p>
                    <p><strong>Total amount:</strong> ${{ number_format($order->payment->total_amount, 2) + number_format($order->payment->delivery_charge, 2) }}</p>
                </div>
            </div>
            <div class="text-center mb-2">
                <a href="{{ route('dry.order.manage') }}" class="btn btn-primary d-inline-block">Back to Dry Orders</a>
            </div>
        </div>
    </div>
@endsection
