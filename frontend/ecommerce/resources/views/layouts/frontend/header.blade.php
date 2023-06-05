<!-- mobile menu area start -->
<div id="tp-bottom-menu-sticky" class="tp-mobile-menu d-lg-none">
    <div class="container">
        <div class="row row-cols-5">
            <div class="col">
                <div class="tp-mobile-item text-center">
                    <a href="shop.html" class="tp-mobile-item-btn">
                        <i class="flaticon-store"></i>
                        <span>Store</span>
                    </a>
                </div>
            </div>
            <div class="col">
                <div class="tp-mobile-item text-center">
                    <button class="tp-mobile-item-btn tp-search-open-btn">
                        <i class="flaticon-search-1"></i>
                        <span>Search</span>
                    </button>
                </div>
            </div>
            <div class="col">
                <div class="tp-mobile-item text-center">
                    <a href="wishlist.html" class="tp-mobile-item-btn">
                        <i class="flaticon-love"></i>
                        <span>Wishlist</span>
                    </a>
                </div>
            </div>
            <div class="col">
                <div class="tp-mobile-item text-center">
                    <a href="profile.html" class="tp-mobile-item-btn">
                        <i class="flaticon-user"></i>
                        <span>Account</span>
                    </a>
                </div>
            </div>
            <div class="col">
                <div class="tp-mobile-item text-center">
                    <button class="tp-mobile-item-btn tp-offcanvas-open-btn">
                        <i class="flaticon-menu-1"></i>
                        <span>Menu</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- mobile menu area end -->

@auth
    <!-- cart mini area start -->
    <div class="cartmini__area tp-all-font-roboto">
        <div class="cartmini__wrapper d-flex justify-content-between flex-column">
            <div class="cartmini__top-wrapper">
                <div class="cartmini__top p-relative">
                    <div class="cartmini__top-title">
                        <h4>Shopping cart</h4>
                    </div>
                    <div class="cartmini__close">
                        <button type="button" class="cartmini__close-btn cartmini-close-btn"><i
                                class="fal fa-times"></i></button>
                    </div>
                </div>
                <div class="cartmini__widget">
                    @php
                        $subtotal = 0;
                    @endphp
                    @foreach ($carts as $cart)
                        @php
                            $subtotal += $cart->Price * $cart->Quantity;
                        @endphp
                        <div class="cartmini__widget-item">
                            <div class="cartmini__thumb">
                                <a href="{{ route('products.show', $cart->ProductID) }}">
                                    <img src="{{ asset($cart->ProductImage) }}" alt="">

                                </a>
                            </div>
                            <div class="cartmini__content">
                                <h5 class="cartmini__title"><a href="product-details.html">{{ $cart->ProductName }}</a>
                                    <div class="cartmini__price-wrapper">
                                        <span class="cartmini__price">Rp.
                                            {{ number_format($cart->Price, 0, ',', '.') }}</span>
                                        <span class="cartmini__quantity">x{{ $cart->Quantity }}</span>
                                    </div>
                            </div>
                            <a href="#" class="cartmini__del"><i class="fa-regular fa-xmark"></i></a>
                        </div>
                    @endforeach

                </div>
                <!-- for wp -->
                <!-- if no item in cart -->
                <div class="cartmini__empty text-center d-none">
                    <img src="frontend/img/product/cartmini/empty-cart.png" alt="">
                    <p>Your Cart is empty</p>
                    <a href="{{ route('products.index') }}" class="tp-btn">Go to Shop</a>
                </div>
            </div>
            <div class="cartmini__checkout">
                <div class="cartmini__checkout-title mb-30">
                    <h4>Subtotal:</h4>
                    <span>Rp. {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="cartmini__checkout-btn">
                    <a href="{{ route('cart') }}" class="tp-btn mb-10 w-100"> view cart</a>
                    <a href="{{ route('checkout') }}" class="tp-btn tp-btn-border w-100"> checkout</a>
                </div>
            </div>
        </div>
    </div>
    <!-- cart mini area end -->
@endauth

<!-- header area start -->
<header>
    <div class="tp-header-area p-relative z-index-11">
        <!-- header main start -->
        <div class="tp-header-main tp-header-sticky">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                        <div class="logo">
                            <a href="index-2.html">
                                <img src="frontend/img/logo/logo.svg" alt="logo">
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-7 d-none d-lg-block">
                    </div>
                    <div class="col-xl-4 col-lg-3 col-md-8 col-6">
                        <div class="tp-header-main-right d-flex align-items-center justify-content-end">
                            <div class="tp-header-login d-none d-lg-block">
                                @auth
                                    <a href="profile.html" class="d-flex align-items-center">
                                        <div class="tp-header-login-icon">
                                            <span>
                                                <svg width="17" height="21" viewBox="0 0 17 21" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="8.57894" cy="5.77803" r="4.77803"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M1.00002 17.2014C0.998732 16.8655 1.07385 16.5337 1.2197 16.2311C1.67736 15.3158 2.96798 14.8307 4.03892 14.611C4.81128 14.4462 5.59431 14.336 6.38217 14.2815C7.84084 14.1533 9.30793 14.1533 10.7666 14.2815C11.5544 14.3367 12.3374 14.4468 13.1099 14.611C14.1808 14.8307 15.4714 15.27 15.9291 16.2311C16.2224 16.8479 16.2224 17.564 15.9291 18.1808C15.4714 19.1419 14.1808 19.5812 13.1099 19.7918C12.3384 19.9634 11.5551 20.0766 10.7666 20.1304C9.57937 20.2311 8.38659 20.2494 7.19681 20.1854C6.92221 20.1854 6.65677 20.1854 6.38217 20.1304C5.59663 20.0773 4.81632 19.9641 4.04807 19.7918C2.96798 19.5812 1.68652 19.1419 1.2197 18.1808C1.0746 17.8747 0.999552 17.5401 1.00002 17.2014Z"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="tp-header-login-content d-none d-xl-block">
                                            <span>{{ auth()->user()->name }}</span>
                                            <h5 class="tp-header-login-title">Your Account</h5>
                                        </div>
                                    </a>
                                @endauth
                                @guest
                                    <a href="{{ route('login') }}" class="d-flex align-items-center">
                                        <div class="tp-header-login-icon">
                                            <span>
                                                <i class="fal fa-sign-in-alt"></i>
                                            </span>
                                        </div>
                                        <div class="tp-header-login-content d-none d-xl-block">
                                            Login
                                        </div>
                                    </a>
                                @endguest
                            </div>
                            @auth
                                <div class="tp-header-action d-flex align-items-center ml-50">
                                    <div class="tp-header-action-item d-none d-lg-block">
                                        <a href="wishlist.html" class="tp-header-action-btn">
                                            <svg width="22" height="20" viewBox="0 0 22 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M11.239 18.8538C13.4096 17.5179 15.4289 15.9456 17.2607 14.1652C18.5486 12.8829 19.529 11.3198 20.1269 9.59539C21.2029 6.25031 19.9461 2.42083 16.4289 1.28752C14.5804 0.692435 12.5616 1.03255 11.0039 2.20148C9.44567 1.03398 7.42754 0.693978 5.57894 1.28752C2.06175 2.42083 0.795919 6.25031 1.87187 9.59539C2.46978 11.3198 3.45021 12.8829 4.73806 14.1652C6.56988 15.9456 8.58917 17.5179 10.7598 18.8538L10.9949 19L11.239 18.8538Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M7.26062 5.05302C6.19531 5.39332 5.43839 6.34973 5.3438 7.47501"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <span class="tp-header-action-badge">4</span>
                                        </a>
                                    </div>
                                    <div class="tp-header-action-item">
                                        <button type="button" class="tp-header-action-btn cartmini-open-btn">
                                            <svg width="21" height="22" viewBox="0 0 21 22" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M6.48626 20.5H14.8341C17.9004 20.5 20.2528 19.3924 19.5847 14.9348L18.8066 8.89359C18.3947 6.66934 16.976 5.81808 15.7311 5.81808H5.55262C4.28946 5.81808 2.95308 6.73341 2.4771 8.89359L1.69907 14.9348C1.13157 18.889 3.4199 20.5 6.48626 20.5Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M6.34902 5.5984C6.34902 3.21232 8.28331 1.27803 10.6694 1.27803V1.27803C11.8184 1.27316 12.922 1.72619 13.7362 2.53695C14.5504 3.3477 15.0081 4.44939 15.0081 5.5984V5.5984"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M7.70365 10.1018H7.74942" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M13.5343 10.1018H13.5801" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span class="tp-header-action-badge">{{ $cart_count }}</span>
                                        </button>
                                    </div>
                                    <div class="tp-header-action-item d-lg-none">
                                        <button type="button" class="tp-header-action-btn tp-offcanvas-open-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="16"
                                                viewBox="0 0 30 16">
                                                <rect x="10" width="20" height="2"
                                                    fill="currentColor" />
                                                <rect x="5" y="7" width="25" height="2"
                                                    fill="currentColor" />
                                                <rect x="10" y="14" width="20" height="2"
                                                    fill="currentColor" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- header bottom start -->
        <div class="tp-header-bottom tp-header-bottom-border d-none d-lg-block">
            <div class="container">
                <div class="tp-mega-menu-wrapper p-relative">
                    <div class="row align-items-center">
                        <div class="col-xl-3 col-lg-3">
                        </div>
                        <div class="col-xl-6 col-lg-6">
                            <div class="main-menu menu-style-1">
                                <nav class="tp-main-menu-content">
                                    <ul>
                                        <li><a href="{{ route('home') }}">Home</a></li>
                                        <li><a href="{{ route('products.index') }}">Products</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header area end -->

<div id="header-sticky-2" class="tp-header-sticky-area">
    <div class="container">
        <div class="tp-mega-menu-wrapper p-relative">
            <div class="row align-items-center">
                <div class="col-xl-3 col-lg-3 col-md-3 col-6">
                    <div class="logo">
                        <a href="index-2.html">
                            <img src="frontend/img/logo/logo.svg" alt="logo">
                        </a>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 d-none d-md-block">
                    <div class="tp-header-sticky-menu main-menu menu-style-1">
                        <nav id="mobile-menu">
                            <ul>
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><a href="{{ route('products.index') }}">Products</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-6">
                    <div class="tp-header-action d-flex align-items-center justify-content-end ml-50">
                        <div class="tp-header-action-item d-none d-lg-block">
                            <a href="wishlist.html" class="tp-header-action-btn">
                                <svg width="22" height="20" viewBox="0 0 22 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M11.239 18.8538C13.4096 17.5179 15.4289 15.9456 17.2607 14.1652C18.5486 12.8829 19.529 11.3198 20.1269 9.59539C21.2029 6.25031 19.9461 2.42083 16.4289 1.28752C14.5804 0.692435 12.5616 1.03255 11.0039 2.20148C9.44567 1.03398 7.42754 0.693978 5.57894 1.28752C2.06175 2.42083 0.795919 6.25031 1.87187 9.59539C2.46978 11.3198 3.45021 12.8829 4.73806 14.1652C6.56988 15.9456 8.58917 17.5179 10.7598 18.8538L10.9949 19L11.239 18.8538Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M7.26062 5.05302C6.19531 5.39332 5.43839 6.34973 5.3438 7.47501"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span class="tp-header-action-badge">4</span>
                            </a>
                        </div>
                        <div class="tp-header-action-item">
                            <button type="button" class="tp-header-action-btn cartmini-open-btn">
                                <svg width="21" height="22" viewBox="0 0 21 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M6.48626 20.5H14.8341C17.9004 20.5 20.2528 19.3924 19.5847 14.9348L18.8066 8.89359C18.3947 6.66934 16.976 5.81808 15.7311 5.81808H5.55262C4.28946 5.81808 2.95308 6.73341 2.4771 8.89359L1.69907 14.9348C1.13157 18.889 3.4199 20.5 6.48626 20.5Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M6.34902 5.5984C6.34902 3.21232 8.28331 1.27803 10.6694 1.27803V1.27803C11.8184 1.27316 12.922 1.72619 13.7362 2.53695C14.5504 3.3477 15.0081 4.44939 15.0081 5.5984V5.5984"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M7.70365 10.1018H7.74942" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13.5343 10.1018H13.5801" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span class="tp-header-action-badge">13</span>
                            </button>
                        </div>
                        <div class="tp-header-action-item d-lg-none">
                            <button type="button" class="tp-header-action-btn tp-offcanvas-open-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="16"
                                    viewBox="0 0 30 16">
                                    <rect x="10" width="20" height="2" fill="currentColor" />
                                    <rect x="5" y="7" width="25" height="2"
                                        fill="currentColor" />
                                    <rect x="10" y="14" width="20" height="2"
                                        fill="currentColor" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
