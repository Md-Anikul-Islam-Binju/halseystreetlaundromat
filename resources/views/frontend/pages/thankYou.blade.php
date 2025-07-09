@extends('frontend.layout')
@section('content')
<section class="py-120 mt-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div class="d-flex flex-column justify-content-center align-items-center gap-3 w-100 p-4 border rounded-5 shadow-sm">
                <div class="logo">
                    <img src="{{URL::TO('frontend/images/logo/logo-1.webp')}}" alt="logo">
                </div>
                <h2>Thank You!</h2>
                <p class="text-capitalize">Your order has been placed successfully.</p>
                <a href="{{route('user.order.list')}}" class="app-btn text-center">Go to Your Order</a>
            </div>
        </div>
    </div>
</section>
@endsection
