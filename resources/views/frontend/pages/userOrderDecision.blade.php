@extends('frontend.layout')
@section('content')

    <section class="banner-section2 section-bg py-120 mt-5">
        <div class="section-title text-center mb-30 style-two">
            <h1 class="text-center split-text right title">Choose Your Plan</h1>
        </div>
        <div class="container">
            <div class="d-flex justify-content-center align-items-center  gap-5 mb-5">
                <div class="">
                    <div class="d-flex justify-content-center">
                        <div>
                            <div class="order-img rounded-5">
                                <div class="reveal left">
                                    <img src="{{URL::TO('frontend/images/service/image-4.jpg')}}" alt="order-img"
                                        class="w-100 img-fluid rounded-5" style="max-height: 400px;" />
                                </div>
                                <div class="position-absolute order-overlay">
                                    <div class="overlay">
                                        <a href="{{route('user.order')}}" class="text-white fw-bold text-center fs-3">Select
                                            Your
                                            Items <span> <i class="fa-solid fa-arrow-right"></i></span></a>
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-center mt-3">Wash</h3>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="d-flex justify-content-center">
                        <div>
                            <div class="order-img rounded-5">
                                <div class="reveal right">
                                    <img src="{{URL::TO('frontend/images/service/image-5.jpg')}}" alt="order-img"
                                        class="w-100 img-fluid rounded-5" style="max-height: 400px;" />
                                </div>
                                <div class="position-absolute order-overlay">
                                    <div class="overlay">
                                        <a href="{{route('user.order.dry')}}" class="text-white fw-bold text-center fs-3">Select
                                            Your Items <span> <i class="fa-solid fa-arrow-right"></i></span></a>
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-center mt-3">Dry Cleaning</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection