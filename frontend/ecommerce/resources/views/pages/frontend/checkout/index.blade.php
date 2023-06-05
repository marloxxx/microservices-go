@extends('layouts.frontend.master')
@push('custom-styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
    <!-- breadcrumb area start -->
    <section class="breadcrumb__area include-bg pt-95 pb-50" data-bg-color="#EFF1F5">
        <div class="container">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="breadcrumb__content p-relative z-index-1">
                        <h3 class="breadcrumb__title">Checkout</h3>
                        <div class="breadcrumb__list">
                            <span><a href="#">Home</a></span>
                            <span>Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->

    <!-- checkout area start -->
    <section class="tp-checkout-area pb-120" data-bg-color="#EFF1F5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Sorry!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
                <div class="col-lg-7">
                    <div class="tp-checkout-bill-area">
                        <h3 class="tp-checkout-bill-title">Billing Details</h3>
                        <div class="tp-checkout-bill-form">
                            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                                @csrf
                                <input type="hidden" name="shipping" value="0">
                                <div class="tp-checkout-bill-inner">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="tp-checkout-input">
                                                <label>Name <span>*</span></label>
                                                <input type="text" placeholder="Name" id="name" name="name"
                                                    value="{{ Auth::user()->name }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="tp-checkout-input">
                                                <label>Street address</label>
                                                <textarea name="address" id="address" cols="30" rows="10" placeholder="House number and street name">{{ Auth::user()->address }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="tp-checkout-input">
                                                <label>Town / City</label>
                                                <select class="" name="city_id" id="city_id">
                                                    <option value="">Select City</option>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->city_id }}"
                                                            {{ Auth::user()->city_id == $city->city_id ? 'selected' : '' }}>
                                                            {{ $city->city_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="tp-checkout-input">
                                                <label>Kurir</label>
                                                <select class="" name="courier" id="courier">
                                                    <option value="">Select Courier</option>
                                                    <option value="jne">JNE</option>
                                                    <option value="tiki">TIKI</option>
                                                    <option value="pos">POS</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="tp-checkout-input">
                                                <label>Email address <span>*</span></label>
                                                <input type="email" placeholder="" name="email"
                                                    value="{{ Auth::user()->email }}" disabled />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <!-- checkout place order -->
                    <div class="tp-checkout-place white-bg">
                        <h3 class="tp-checkout-place-title">Your Order</h3>

                        <div class="tp-order-info-list">
                            <ul>
                                <!-- header -->
                                <li class="tp-order-info-list-header">
                                    <h4>Product</h4>
                                    <h4>Total</h4>
                                </li>
                                @php
                                    $subtotal = 0;
                                @endphp
                                @foreach ($carts as $cart)
                                    @php
                                        $subtotal += $cart->product ? $cart->product->price * $cart->Quantity : $cart->Price * $cart->Quantity;
                                    @endphp
                                    <li class="tp-order-info-list-desc">
                                        <p>{{ $cart->product ? $cart->product->name : $cart->ProductName }} <span> x
                                                {{ $cart->Quantity }}</span></p>
                                        <span>{{ $cart->product ? $cart->product->price : $cart->Price }}</span>
                                @endforeach

                                <!-- subtotal -->
                                <li class="tp-order-info-list-subtotal">
                                    <span>Subtotal</span>
                                    <span>Rp. {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </li>

                                <!-- shipping -->
                                <li class="tp-order-info-list-shipping">
                                    <span>Shipping</span>
                                    <span id="shipping">Rp. 0</span>
                                </li>

                                <!-- total -->
                                <li class="tp-order-info-list-total">
                                    <span>Total</span>
                                    <span id="total">Rp. {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="tp-checkout-btn-wrapper">
                            <a href="javascript:void(0)" onclick="checkout()" class="tp-checkout-btn w-100">Place Order</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- checkout area end -->
@endsection
@push('custom-scritps')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
        $(document).ready(function() {
            $('#city_id').change(function() {
                var city_id = $('#city_id').val();
                var courier = $('#courier').val();
                var subtotal = {{ $subtotal }};
                $.ajax({
                    url: "{{ route('checkout.shipping') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        city_id: city_id,
                        courier: courier,
                        subtotal: subtotal
                    },
                    success: function(response) {
                        if (response.status) {
                            $('input[name="shipping"]').val(response.ongkir);
                            $('#shipping').text(response.ongkir);
                            $('#total').text(response.total);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message,
                            })
                        }
                    }
                });
            });

            $('#courier').change(function() {
                var city_id = $('#city_id').val();
                var courier = $('#courier').val();
                var subtotal = {{ $subtotal }};
                $.ajax({
                    url: "{{ route('checkout.shipping') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        city_id: city_id,
                        courier: courier,
                        subtotal: subtotal
                    },
                    success: function(response) {
                        if (response.status) {
                            $('input[name="shipping"]').val(response.ongkir);
                            $('#shipping').text(response.ongkir);
                            $('#total').text(response.total);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message,
                            })
                        }
                    }
                });
            });
        });

        function checkout() {
            var name = $('#name').val();
            var address = $('#address').val();
            var city_id = $('#city_id').val();
            var shipping = $('#shipping').text();
            var subtotal = {{ $subtotal }};
            $.ajax({
                url: "{{ route('checkout.check') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: name,
                    address: address,
                    city_id: city_id,
                    shipping: shipping,
                    subtotal: subtotal
                },
                success: function(response) {
                    if (response.status) {
                        // submit form
                        $('#checkout-form').submit();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        })
                    }
                }
            });
        }
    </script>
@endpush
