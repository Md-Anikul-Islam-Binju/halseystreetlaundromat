@extends('admin.app')
@section('admin_content')
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Halsey Street Laundromat</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                            <li class="breadcrumb-item active">Welcome!</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Welcome!</h4>
                </div>
            </div>
        </div>

        @can('cart-list')
        <div class="row">
            <div class="col-xxl-3 col-sm-6">
                <div class="card widget-flat text-bg-pink">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="ri-app-store-line widget-icon"></i>
                        </div>
                        <h6 class="text-uppercase mt-0" title="Customers">Total App</h6>
                        <h2 class="my-2">10</h2>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <div class="card widget-flat text-bg-purple">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="ri-profile-line widget-icon"></i>
                        </div>
                        <h6 class="text-uppercase mt-0" title="Customers">Total Website</h6>
                        <h2 class="my-2">10</h2>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <div class="card widget-flat text-bg-info">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="ri-route-line widget-icon"></i>
                        </div>
                        <h6 class="text-uppercase mt-0" title="Customers">Total News</h6>
                        <h2 class="my-2">10</h2>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <div class="card widget-flat text-bg-primary">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="ri-file-line widget-icon"></i>
                        </div>
                        <h6 class="text-uppercase mt-0" title="Customers">Total Blog</h6>
                        <h2 class="my-2">10</h2>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('login-log-list')
            <dic class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex">
                                <h4 class="page-title">Dry Order!</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Customer</th>
                                    <th>Invoice</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dryOrders as $key=>$orderData)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                            {{$orderData->user->name}}<br>
                                            {{$orderData->user->phone}}<br>
                                        </td>
                                        <td>
                                            {{$orderData->invoice_number}}
                                        </td>
                                        <td>
                                            {{$orderData->order_date}}
                                        </td>
                                        <td>
                                            {{$orderData->total_amount + $orderData->payment->delivery_charge??0}}
                                        </td>
                                        <td>
                                            @if($orderData->status == 'pending')
                                                <span class="badge badge-outline-success">Pending</span>
                                            @elseif($orderData->status == 'completed')
                                                <span class="badge badge-outline-info">Completed</span>
                                            @elseif($orderData->status == 'decline')
                                                <span class="badge badge-danger">Decline</span>
                                            @endif

                                        </td>
                                        <td style="width: 100px;">
                                            <a href="{{route('dry.order.manage.show',$orderData->id)}}" class="btn btn-primary btn-sm">Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex">
                                <h4 class="page-title">Wash Order!</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Customer</th>
                                    <th>Invoice</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $key=>$orderData)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                            {{$orderData->user->name}}<br>
                                            {{$orderData->user->phone}}<br>
                                            {{$orderData->user->email}}
                                        </td>
                                        <td>
                                            {{$orderData->invoice_number}}
                                        </td>
                                        <td>
                                            {{$orderData->order_date}}
                                        </td>
                                        <td>
                                            {{$orderData->total_amount}}
                                        </td>
                                        <td>
                                            @if($orderData->status == 'pending')
                                                <span class="badge badge-outline-success">Pending</span>
                                            @elseif($orderData->status == 'completed')
                                                <span class="badge badge-outline-info">Completed</span>
                                            @elseif($orderData->status == 'decline')
                                                <span class="badge badge-danger">Decline</span>
                                            @endif

                                        </td>
                                        <td style="width: 100px;">
                                            <a href="{{route('order.manage.show',$orderData->id)}}" class="btn btn-primary btn-sm">Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </dic>




        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-end">
                    </div>
                </div>
                <div class="card-body">
                    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Email</th>
                            <th>IP</th>
                            <th>Browser</th>
                            <th>Platform</th>
                            <th>Last Login</th>
                            <th>User Agent</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($loginLog as $key=>$loginLogData)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$loginLogData->email}}</td>
                                <td>{{$loginLogData->ip}}</td>
                                <td>{{$loginLogData->browser}}</td>
                                <td>{{$loginLogData->platform}}</td>
                                <td>{{$loginLogData->last_login}}
                                <td>{{$loginLogData->user_agent}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endcan


@endsection
