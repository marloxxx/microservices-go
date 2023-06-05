<script src="{{ asset('frontend/js/vendor/jquery.js') }}"></script>
<script src="{{ asset('frontend/js/vendor/waypoints.js') }}"></script>
<script src="{{ asset('frontend/js/bootstrap-bundle.js') }}"></script>
<script src="{{ asset('frontend/js/meanmenu.js') }}"></script>
<script src="{{ asset('frontend/js/swiper-bundle.js') }}"></script>
<script src="{{ asset('frontend/js/slick.js') }}"></script>
<script src="{{ asset('frontend/js/range-slider.js') }}"></script>
<script src="{{ asset('frontend/js/magnific-popup.js') }}"></script>
<script src="{{ asset('frontend/js/nice-select.js') }}"></script>
<script src="{{ asset('frontend/js/purecounter.js') }}"></script>
<script src="{{ asset('frontend/js/countdown.js') }}"></script>
<script src="{{ asset('frontend/js/wow.js') }}"></script>
<script src="{{ asset('frontend/js/isotope-pkgd.js') }}"></script>
<script src="{{ asset('frontend/js/imagesloaded-pkgd.js') }}"></script>
<script src="{{ asset('frontend/js/ajax-form.js') }}"></script>
<script src="{{ asset('frontend/js/main.js') }}"></script>
<script src="{{ asset('js/FormControls.js') }}"></script>
<script src="{{ asset('js/toastr.js') }}"></script>
<script src="{{ asset('js/sweetalert.js') }}"></script>
<script>
    function addToCart(product_id) {
        var quantity = $('#quantity').val();
        $.ajax({
            url: "{{ route('cart.store') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: product_id,
                quantity: quantity
            },
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                    // $('#cart-count').html(response.cart_count);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    }
</script>
