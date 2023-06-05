@extends('layouts.frontend.master')
@section('content')
    <main>

        <!-- breadcrumb area start -->
        <section class="breadcrumb__area include-bg pt-95 pb-90">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="breadcrumb__content p-relative z-index-1">
                            <h3 class="breadcrumb__title">Track your order</h3>
                            <div class="breadcrumb__list">
                                <span><a href="#">Home</a></span>
                                <span>Track your order</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- breadcrumb area end -->

        <!-- order area start -->
        <section class="tp-order-area pb-160">
            <div class="container">
                <div class="tp-order-inner">
                    <div class="row gx-0">
                        <div class="col-lg-6">
                            <div class="tp-order-details" data-bg-color="#4F3D97">
                                <div class="tp-order-details-top text-center mb-70">
                                    <div class="tp-order-details-icon">
                                        <span>
                                            <svg width="52" height="52" viewBox="0 0 52 52" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M46 26V51H6V26" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M51 13.5H1V26H51V13.5Z" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M26 51V13.5" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M26 13.5H14.75C13.0924 13.5 11.5027 12.8415 10.3306 11.6694C9.15848 10.4973 8.5 8.9076 8.5 7.25C8.5 5.5924 9.15848 4.00269 10.3306 2.83058C11.5027 1.65848 13.0924 1 14.75 1C23.5 1 26 13.5 26 13.5Z"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M26 13.5H37.25C38.9076 13.5 40.4973 12.8415 41.6694 11.6694C42.8415 10.4973 43.5 8.9076 43.5 7.25C43.5 5.5924 42.8415 4.00269 41.6694 2.83058C40.4973 1.65848 38.9076 1 37.25 1C28.5 1 26 13.5 26 13.5Z"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="tp-order-details-content">
                                        <h3 class="tp-order-details-title">Your Order Has Been Placed</h3>
                                        <p>We will send you a shipping confirmation email as soon <br> as your order ships
                                        </p>
                                    </div>
                                </div>
                                <div class="tp-order-details-item-wrapper">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="tp-order-details-item">
                                                <h4>Order Date:</h4>
                                                {{-- <p>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</p> --}}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="tp-order-details-item">
                                                <h4>Order Number:</h4>
                                                <p>{{ $order->code }}</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="tp-order-details-item">
                                                <h4>Payment Status:</h4>
                                                <p>
                                                    @if ($order->payment_status == '1')
                                                        <span class="badge badge-warning">Pending</span>
                                                    @elseif($order->payment_status == '2')
                                                        <span class="badge badge-success">Success</span>
                                                    @elseif($order->payment_status == '3')
                                                        <span class="badge badge-danger">Failed</span>
                                                    @elseif($order->payment_status == '4')
                                                        <span class="badge badge-info">Expired</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="tp-order-info-wrapper">
                                <h4 class="tp-order-info-title">Order Details</h4>

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
                                        @foreach ($order->OrderItems as $item)
                                            @php
                                                $subtotal += $item->price * $item->quantity;
                                            @endphp
                                            <!-- item list -->
                                            <li class="tp-order-info-list-desc">
                                                <p>{{ $item->product_name }} <span> x {{ $item->quantity }}</span></p>
                                                <span>{{ $item->price }}</span>
                                            </li>
                                        @endforeach

                                        <!-- subtotal -->
                                        <li class="tp-order-info-list-subtotal">
                                            <span>Subtotal</span>
                                            <span>Rp. {{ number_format($subtotal, 0, ',', '.') }}</span>
                                        </li>

                                        <!-- shipping -->
                                        <!-- shipping -->
                                        <li class="tp-order-info-list-shipping">
                                            <span>Shipping</span>
                                            <span>Rp.
                                                {{ number_format($order->shipping_price, 0, ',', '.') }}</span>
                                        </li>

                                        <!-- total -->
                                        <li class="tp-order-info-list-total">
                                            <span>Total</span>
                                            <span>Rp. {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                        </li>

                                        <!-- button payment -->
                                        <li class="tp-order-info-list-btn">
                                            <button class="btn btn-primary" id="pay-button">Bayar Sekarang</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- order area end -->

    </main>
@endsection
@push('custom-scritps')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        const payButton = document.querySelector('#pay-button');
        payButton.addEventListener('click', function(e) {
            e.preventDefault();

            snap.pay('{{ $snapToken }}', {
                // Optional
                onSuccess: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                },
                // Optional
                onPending: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                },
                // Optional
                onError: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                }
            });
        });
    </script>
@endpush
