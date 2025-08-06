@extends('frontend.layout')
@section('content')
    <body class="section-bg">
     <div class="app-container py-5 mt-5">
        <div class="d-flex align-items-center justify-content-center min-vh-100 w-100">
            <div
                class="d-flex flex-column justify-content-center align-items-center gap-3 w-100 p-4 border rounded-5 shadow-sm">
                <h1 class="text-center fw-bold my-4">Place An order</h1>
                <form id="order-form" action="{{route('user.order.store')}}" method="post" class="app-form w-100">
                    @csrf
                    <div class="step active w-100" data-step="1">
                        <div class="d-flex flex-column justify-content-center align-items-center gap-3 w-100">
                            <div class="w-100">
                                <h5 class="text-start my-2">Pickup Locations</h5>
                                <p class="text-capitalize">Please enter your order details</p>
                            </div>
                            <div class="position-relative w-100">
                                <input type="text" class="app-input form-control" id="address" name="address" placeholder="Address" required>
                                <span class="app-input-icon"><i class="fa-solid fa-pencil"></i></span>
                            </div>
                            <div class="mb-3 position-relative w-100">
                                <label for="pickupSpot" class="form-label">Select Pickup Spot</label>
                                <select id="pickupSpot" name="pic_spot" class="form-select" aria-label="Pickup Spot" required>
                                    <option value="" disabled selected>Select an option</option>
                                    <option value="Front Door">Front Door</option>
                                    <option value="Back Door">Back Door</option>
                                    <option value="Reception">Reception</option>
                                </select>
                            </div>
                            <div class="w-100 d-flex align-items-center justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="instructions" id="pickupChecks" value="1">
                                    <label class="form-check-label" for="pickupChecks"> Add Pickup instructions </label>
                                </div>
                                <span class="instruction-icon" role="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
                                    <i class="fa-regular fa-circle-question fs-5"></i>
                                </span>
                                <div class="modal fade" id="staticBackdrop" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Pickup instructions</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-black fw-medium">Your Halsey Street Laundromat will ONLY see these details after accepting your order.</p>
                                                <ul class="list-unstyled ms-3">
                                                    <li>Building access details</li>
                                                    <li>Gate codes</li>
                                                    <li>How laundry can be found</li>
                                                </ul>
                                                <p class="text-black mt-2">For requests related to pickup or delivery timing, please message your Halsey Street Laundromat</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="pickupInstructionsWrapper" class="w-100 text-start">
                                <textarea class="form-control" id="pickupInstructions" name="pickup_instructions" rows="3" placeholder="Enter pickup instructions here..."></textarea>
                            </div>
                            <button type="button" class="app-btn next-btn">Continue</button>
                        </div>
                    </div>



                    <div class="step w-100" data-step="2">
                        <div class="d-flex flex-column justify-content-center align-items-center gap-3 w-100">
                            <div class="w-100">
                                <h5 class="text-start my-2 text-uppercase">Pickup Speed</h5>
                                <p class="text-capitalize">Please choose your desired delivery speed</p>
                            </div>
                            <div class="w-100">
                                <div class="card selectable-card">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <div class="d-flex">
                                                <div>
                                                    <input class="form-check-input" type="radio" name="delivery_speed_type" id="standerd delivery" value="Standard" required>
                                                    <label class="form-check-label" for="standerd delivery"> Standard Delivery </label>
                                                </div>
                                                <div class="ms-auto">
                                                    <span class="badge custom-tertiary-bg py-2 rounded-pill">$1.60/LB</span>
                                                </div>
                                            </div>
                                            <ul class="list-unstyled mt-2 w-100">
                                                <li class="d-flex gap-2 align-items-center"><i class="fa-regular fa-ticket" style="transform: rotate(90deg);"></i><span>Place order by 3pm today</span></li>
                                                <li class="d-flex gap-2 align-items-center">
                                                    <i class="fa-regular fa-car"></i><span>Pickup by 8pm today</span>
                                                </li>
                                                <li class="d-flex gap-2 align-items-center">
                                                    <i class="fa-regular fa-truck"></i><span>Delivered by 8pm tomorrow</span></li>
                                                <li class="d-flex gap-2 align-items-center">
                                                    <i class="fa-regular fa-circle-dollar"></i><span>$30 minimum charge</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card selectable-card">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <div class="d-flex">
                                                <div>
                                                    <input class="form-check-input" type="radio" name="delivery_speed_type" id="express_delivery" value="Express" required>
                                                    <label class="form-check-label" for="express_delivery">
                                                        Express Delivery
                                                    </label>
                                                </div>
                                                <div class="ms-auto">
                                                    <span class="badge custom-tertiary-bg py-2 rounded-pill">$3/LB</span>
                                                </div>
                                            </div>
                                            <ul class="list-unstyled mt-2">
                                                <li class="d-flex gap-2 align-items-center">
                                                    <i class="fa-regular fa-ticket" style="transform: rotate(90deg);"></i>
                                                    <span>Place order by 10am today</span>
                                                </li>

                                                <li class="d-flex gap-2 align-items-center">
                                                    <i  class="fa-regular fa-car"></i><span>Pickup by 2pm today</span>
                                                </li>
                                                <li class="d-flex gap-2 align-items-center">
                                                    <i class="fa-regular fa-truck"></i><span>Delivered by 8pm  same day</span></li>
                                                <li class="d-flex gap-2 align-items-center">
                                                    <i class="fa-regular fa-clothes-hanger"></i><span>Hang-dry unavailable</span>
                                                </li>
                                                <li class="d-flex gap-2 align-items-start">
                                                    <i class="fa-regular fa-weight-scale" style="margin-top: 6px;"></i>
                                                    <span>Weight may not exceed 60pounds</span>
                                                </li>
                                                <li class="d-flex gap-2 align-items-center">
                                                    <i class="fa-regular fa-circle-dollar"></i>
                                                    <span>$50 minimum charge</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 d-flex align-items-center justify-content-between">
                                <button type="button" class="app-prev-btn prev-btn">Previous</button>
                                <button type="button" class="app-next-btn next-btn">Next</button>
                            </div>
                        </div>
                    </div>
                    <div class="step w-100" data-step="3">
                        <div class="d-flex flex-column justify-content-center align-items-center gap-3 w-100">
                            <div class="w-100">
                                <h5 class="text-start my-2 text-uppercase">Laundry Care</h5>
                            </div>
                            <div class="w-100">
                                <label for="exampleDataList" class="form-label">Select Detergent</label>
                                <select class="form-select" name="detergent_type" aria-label="Default select example" required>
                                    <option selected disabled value="">Select Detergent</option>
                                    <option value="Hypoallergenic">Hypoallergenic</option>
                                    <option value="Premium Scented">Premium Scented</option>
                                    <option value="Eco Friendly">Eco Friendly</option>
                                    <option value="Provide">I will Provide</option>
                                </select>
                            </div>
                            <div class="w-100" >
                                <h6 class="mb-2">Check all that apply</h6>
                                <div class="form-check">
{{--                                    <input class="form-check-input" name="is_delicate_cycle" type="checkbox" value="1" id="cycle">--}}
{{--                                    <label class="form-check-label" for="cycle">--}}
{{--                                        Delicate cycle--}}
{{--                                    </label>--}}

                                    <input class="form-check-input" name="is_delicate_cycle" type="checkbox" value="1" id="delicate_cycle" data-bs-toggle="modal" data-bs-target="#delicate_cycle_modal" aria-controls="delicate_cycle_modal">
                                    <label class="form-check-label" for="delicate_cycle">
                                        Delicate cycle
                                    </label>
                                    <div class="modal fade" id="delicate_cycle_modal" data-bs-keyboard="false" tabindex="-1"
                                         aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Instructions for  Delicate cycle</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-black" style="font-size: small;">
                                                        Place your delicate items in separate bag labeled Delicate.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="is_hang_dry" type="checkbox" value="1" id="hang_dry" data-bs-toggle="modal" data-bs-target="#hang_dry_modal" aria-controls="hang_dry_modal">
                                    <label class="form-check-label" for="hang_dry">
                                        Hang-dry
                                    </label>
                                    <div class="modal fade" id="hang_dry_modal" data-bs-keyboard="false" tabindex="-1"
                                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Instructions for Hang-dry</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-black" style="font-size: small;">
                                                    Please have hangers in the bag and label. Delicate items must be separated.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="is_return_hanger" type="checkbox" value="1" id="return_items">
                                    <label class="form-check-label" for="return_items">
                                        Return items on hangers
                                    </label>
                                </div>
                                <div class="form-check">
                                    <div class="d-flex align-items-center justify-content-between gap-3 w-100">
                                        <div>
                                            <input class="form-check-input" name="is_additional_request" type="checkbox" value="1" id="additional_rqst" role="button" data-bs-toggle="modal" data-bs-target="#additional_request" aria-controls="additional_request">
                                            <label class="form-check-label" for="additional_rqst">
                                                Additional Requests
                                            </label>
                                        </div>
{{--                                        <span class="instruction-icon" role="button" data-bs-toggle="modal" data-bs-target="#additional_request" aria-controls="additional_request">--}}
{{--                                            <i class="fa-regular fa-circle-question fs-5"></i>--}}
{{--                                        </span>--}}
                                        <div class="modal fade" id="additional_request" data-bs-keyboard="false"
                                             tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Additional
                                                            Requests</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="text-black" style="font-size: small;">Halsey Street Laundromat
                                                            provide a wash-dry-fold service.</p>
                                                        <p class="text-black my-2" style="font-size: small;">They do not
                                                            look for stains, treat stains, or use any stain removal
                                                            products.</p>
                                                        <p class="text-black my-2" style="font-size: small;">They do not
                                                            offer additional services like ironing, lint remoal, shoe
                                                            cleaning, hand washing.</p>
                                                        <p class="text-black" style="font-size: small;">If you request
                                                            specific products other detergent, please leave them out for
                                                            pickup.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 d-flex align-items-center justify-content-between">
                                <button type="button" class="app-prev-btn prev-btn">Previous</button>
                                <button type="button" class="app-next-btn next-btn">Next</button>
                            </div>
                        </div>
                    </div>
                    <div class="step w-100" data-step="4">
                        <div class="d-flex flex-column justify-content-center align-items-center gap-3 w-100">
                            <div class="w-100">
                                <h5 class="text-start my-2 text-uppercase">Bag Count</h5>
                                <p class="bag-desc">
                                    Put your laundry out for pickup in any bag you want, disposable bags, or your
                                    hamper. If you use a hamper we'll transfer your clothes to our bags and leave your
                                    hamper behind. You can also use laundry bags, which we'll return to you laundered.
                                    At this time, you can only choose one bag size.
                                </p>
                            </div>
                            <div class="w-100">
                                <input type="hidden" id="bagCountValidation" required />
                                <div class="bag-item">
                                    <div class="bag-info">
                                        <span class="bag-icon">👜</span>
                                        <div>
                                            <div class="bag-text-title">Small</div>
                                            <div class="bag-text-sub">Grocery bag size</div>
                                        </div>
                                    </div>
                                    <div class="counter-wrapper">
                                        <button type="button" class="counter-btn decrease"
                                                data-target="small">−</button>
                                        <input readonly class="counter-value" id="small" name="small_bag" value="0">
                                        <button type="button" class="counter-btn increase"
                                                data-target="small">+</button>
                                    </div>
                                </div>

                                <div class="bag-item">
                                    <div class="bag-info">
                                        <span class="bag-icon">👜</span>
                                        <div>
                                            <div class="bag-text-title">Regular</div>
                                            <div class="bag-text-sub">Kitchen bag size/hamper</div>
                                        </div>
                                    </div>
                                    <div class="counter-wrapper">
                                        <button type="button" class="counter-btn decrease"
                                                data-target="regular">−</button>
                                        <input readonly class="counter-value" id="regular" name="regular_bag" value="0">
                                        <button type="button" class="counter-btn increase"
                                                data-target="regular">+</button>
                                    </div>
                                </div>
                                <div class="bag-item">
                                    <div class="bag-info">
                                        <span class="bag-icon">👜</span>
                                        <div>
                                            <div class="bag-text-title">Large</div>
                                            <div class="bag-text-sub">Lawn/contractor bag size</div>
                                        </div>
                                    </div>
                                    <div class="counter-wrapper">
                                        <button type="button" class="counter-btn decrease"
                                                data-target="large">−</button>
                                        <input readonly class="counter-value" id="large" name="large_bag" value="0">
                                        <button type="button" class="counter-btn increase"
                                                data-target="large">+</button>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="mb-3">
                                    <h5>OverSized Items (Optional)</h5>
                                    <p class="oversized-desc">Oversized items like comgorters and pillows require their
                                        own load and can be included for an additional $8 fee. <a href="#"
                                                                                                  class="text-decoration-underline">Learn More</a></p>
                                </div>
                                <div class="bag-item">
                                    <div class="bag-info">
                                        <span class="bag-icon">👜</span>
                                        <div>
                                            <div class="bag-text-title">Over sized Items</div>
                                        </div>
                                    </div>
                                    <div class="counter-wrapper">
                                        <button type="button" class="counter-btn decrease"
                                                data-target="overSized">−</button>
                                        <input readonly class="counter-value" id="overSized" name="overSized_bag" value="0">
                                        <button type="button" class="counter-btn increase"
                                                data-target="overSized">+</button>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 d-flex align-items-center justify-content-between">
                                <button type="button" class="app-prev-btn prev-btn">Previous</button>
                                <button type="button" class="app-next-btn" data-bs-toggle="modal"
                                        data-bs-target="#bagpopup">Next</button>
                            </div>
                            <div class="modal fade" id="bagpopup" data-bs-backdrop="static" data-bs-keyboard="false"
                                 tabindex="-1" aria-labelledby="bagpopupLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="bagpopupLabel">Protecting Halsey Street Laundromat</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-dark my-1">To ensure the health and safety of your Halsey Street Laundromat, please certify that your laundry :</p>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                       id="biohazardous">
                                                <label class="form-check-label" for="biohazardous">
                                                    Is safe and does not include bed bugs, fleas, roaches, or
                                                    biohazardous materials.
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="sanitary">
                                                <label class="form-check-label" for="sanitary">
                                                    Is sanitary and does not include feces, urine, blood, or vomit,
                                                    beyond traces normally found in household laundry?
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="petHair">
                                                <label class="form-check-label" for="petHair">
                                                    Does not include excessive pet hair?
                                                </label>
                                            </div>
                                            <p class="text-dark my-1">Violators will incur a $20 fee, laundry returned
                                                "as is," and the account may be closed.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                            <button type="button" class="app-next-btn next-btn w-100"
                                                    data-bs-dismiss="modal">Continue</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--
                    <div class="step w-100" data-step="5">
                        <div class="d-flex flex-column justify-content-center align-items-center gap-3 w-100">
                            <span class="display-1" style="color: #44c8f5;">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <h5 class="my-2 text-uppercase">No Prefered Laundry Pros yet</h5>
                            <div class="w-100 text-center">
                                As you rate Laundry Pros highly, they'll be added here, making it easy to request them
                                for future orders! <a href="#" class="text-decoration-underline">Learn more</a>.
                            </div>
                            <div class="p-3 rounded-5 bg-info d-flex align-items-center gap-3">
                                <span class="d-flex align-items-center justify-content-center rounded-circle"
                                      style="width: 30px; height: 28px;">
                                    <img src="{{URL::to('frontend/images/logo/ten.jpeg')}}" alt="icon" class="img-fluid rounded-circle"
                                         style="width: 30; height: 28px;">
                                </span>
                                <div class="w-100">
                                    <p class="text-capitalize text-dark fw-regular" style="font-size: 14px;">In the
                                        meantime, highly-rated Laundry Pros nearby will be notified of your order.</p>
                                </div>
                            </div>
                            <div class="w-100 d-flex align-items-center justify-content-between">
                                <button type="button" class="app-prev-btn prev-btn">Previous</button>
                                <button type="button" class="app-next-btn next-btn">Next</button>
                            </div>
                        </div>
                    </div>
                     -->
                    <div class="step w-100" data-step="5">
                        <div class="d-flex flex-column justify-content-center align-items-center gap-3 w-100">
                            <div class="w-100">
                                <h5 class="text-start my-2 text-uppercase">Coverage</h5>
                                <p style="font-size: 14px;">
                                    Laundry Protection Plan Covers you in the rare instance of damage or loss.
                                </p>
                            </div>
                            <div class="w-100">
                                <div class="card selectable-card">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <div class="d-flex">
                                                <div>
                                                    <input class="form-check-input" type="radio" name="coverage_type"
                                                           id="basic" value="Basic">
                                                    <label class="form-check-label" for="basic">
                                                        Basic
                                                    </label>
                                                </div>
                                                <div class="ms-auto">
                                                    <span class="badge custom-tertiary-bg py-2 rounded-pill">FREE</span>
                                                </div>
                                            </div>
                                            <ul class="list-unstyled">
                                                <li class="ms-1 text-secondary">Covers $35/agrment</li>
                                                <li class="ms-1 text-secondary">Maximum $100/order</li>
                                            </ul>

                                        </div>
                                    </div>
                                </div>
                                <div class="card selectable-card">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <div class="d-flex">
                                                <div>
                                                    <input class="form-check-input" type="radio" name="coverage_type"
                                                           id="premium" value="Premium" required>
                                                    <label class="form-check-label" for="premium">
                                                        Premium
                                                    </label>
                                                </div>
                                                <div class="ms-auto">
                                                    <span class="badge custom-tertiary-bg py-2 rounded-pill">$1.5</span>
                                                </div>
                                            </div>
                                            <ul class="list-unstyled">
                                                <li class="ms-1 text-secondary">Covers $50/agrment</li>
                                                <li class="ms-1 text-secondary">Maximum $500/order</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card selectable-card">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <div class="d-flex">
                                                <div>
                                                    <input class="form-check-input" type="radio" name="coverage_type"
                                                           id="premium_pLus" value="Primium+" required>
                                                    <label class="form-check-label" for="premium_plus">
                                                        Premium+
                                                    </label>
                                                </div>
                                                <div class="ms-auto">
                                                    <span
                                                        class="badge custom-tertiary-bg py-2 rounded-pill">$2.75</span>
                                                </div>
                                            </div>
                                            <ul class="list-unstyled">
                                                <li class="ms-1 text-secondary">Covers $75/agrment</li>
                                                <li class="ms-1 text-secondary">Maximum $700/order</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100">
                                <p class="text-start text-dark" style="font-size: 14px;"><a href="#" class="text-decoration-underline">Tap here</a> for more information about
                                    Laundry Protection Plan</p>
                            </div>
                            <div class="w-100 d-flex align-items-center justify-content-between">
                                <button type="button" class="app-prev-btn prev-btn">Previous</button>
                                <button type="button" class="app-next-btn next-btn">Next</button>
                            </div>
                        </div>
                    </div>
                    <div class="step w-100" data-step="6">
                        <div class="d-flex flex-column justify-content-center align-items-center gap-3 w-100">
                            <h3 class="text-start my-2 text-uppercase">Payment</h3>
                            <div class="w-100">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" id="paymentTab" role="tablist">
                                    <li class="nav-item w-100" role="presentation">
                                        <button class="nav-link active w-100" id="card-tab" data-bs-toggle="tab"
                                                data-bs-target="#card" type="button" role="tab">Card</button>
                                    </li>

                                </ul>

                                <input type="hidden" name="payment_method" id="payment_method" value="Card">

                                <!-- Tab panes -->
                                <div class="tab-content mt-4">
                                    <!-- Card Form -->
                                    <div class="tab-pane fade show active" id="card" role="tabpanel">
{{--                                        <div class="position-relative w-100 mb-3">--}}
{{--                                            <input type="text" class="app-input form-control" id="card_number" name="card_no"--}}
{{--                                                   placeholder="Card number" maxlength="19">--}}
{{--                                            <span class="app-input-icon"><i class="fa-solid fa-credit-card"></i></span>--}}
{{--                                        </div>--}}

{{--                                        <div class="row">--}}
{{--                                            <div class="col-6 mb-3">--}}
{{--                                                <input type="text" class="form-control app-input" id="exp_date" name="card_exp_date"--}}
{{--                                                       placeholder="MM/YY" maxlength="5">--}}
{{--                                            </div>--}}
{{--                                            <div class="col-6 mb-3">--}}
{{--                                                <input type="text" class="form-control app-input" placeholder="CVC" id="security_code" name="card_security_code" maxlength="3">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                        <div class="row">--}}
{{--                                            <div class="col-12 mb-3">--}}
{{--                                                <input type="text" class="form-control app-input" placeholder="ZIP code" id="zip_code" name="zip_code" maxlength="9">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}


                                        <input type="hidden" name="stripeToken" id="stripe-token-id">

                                        <div class="form-group mb-3">
                                            <label class="text-black">Name on Card</label>
                                            <input type="text" name="card_holder_name" class="form-control" placeholder="Card Holder Name" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="text-black">Card Info</label>
                                            <div id="card-element" class="form-control bg-white p-2 rounded"></div>
                                        </div>
                                        <br>


                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" value="1" id="has_coupon_checkbox">
                                            <label class="form-check-label" for="has_coupon_checkbox">
                                                Have a coupon?
                                            </label>
                                        </div>

                                        <div class="mb-3" id="coupon_input_group" style="display: none;">
                                            <input type="text" class="form-control app-input" name="coupon_code" id="coupon_code" placeholder="Enter coupon code">
                                        </div>

                                        <p class="text-muted small mb-3">
                                            By providing your card information, you allow Halsey Street Laundromat to charge your card
                                            for future payments in accordance with their terms.
                                        </p>

{{--                                        <button  type="submit" onclick="createToken()" class="payment-btn app-btn">Submit</button>--}}
                                        <button type="button" onclick="createToken()" id="pay-btn" class="payment-btn app-btn mt-3">
                                            Submit Payment
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="w-100 d-flex align-items-center justify-content-between">
                                <button type="button" class="app-prev-btn prev-btn">Previous</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="stepper mb-4">
                    <div class="circle step-circle" data-step="1">1</div>
                    <div class="circle step-circle" data-step="2">2</div>
                    <div class="circle step-circle" data-step="3">3</div>
                    <div class="circle step-circle" data-step="4">4</div>
                    <div class="circle step-circle" data-step="5">5</div>
                    <div class="circle step-circle" data-step="6">6</div>

                    <!-- <div class="circle step-circle" data-step="7">7</div> -->

                </div>
            </div>
        </div>
    </div>
    </body>


    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Initialize Stripe
        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements();
        var cardElement = elements.create('card');
        cardElement.mount('#card-element');

        function createToken() {
            document.getElementById("pay-btn").disabled = true;

            stripe.createToken(cardElement).then(function(result) {
                if (result.error) {
                    alert(result.error.message);
                    document.getElementById("pay-btn").disabled = false;
                } else {
                    console.log("Stripe Token:", result.token.id); // DEBUG: See token in console
                    document.getElementById("stripe-token-id").value = result.token.id;
                    document.getElementById("order-form").submit();
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("order-form");
            form.addEventListener("submit", function (e) {
                const paymentMethod = document.getElementById("payment_method").value;
                const tokenInput = document.getElementById("stripe-token-id").value;
                if (paymentMethod === "Card" && tokenInput === '') {
                    e.preventDefault();
                    alert("Please wait while we process your payment.");
                    return false;
                }
            });
        });
    </script>


    <script>
        document.getElementById('has_coupon_checkbox').addEventListener('change', function () {
            const couponInput = document.getElementById('coupon_input_group');
            if (this.checked) {
                couponInput.style.display = 'block';
            } else {
                couponInput.style.display = 'none';
                document.getElementById('coupon_code').value = '';
            }
        });
    </script>



@endsection
