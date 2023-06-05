<script src="{{ asset('backend/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('backend/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('js/method.js') }}"></script>
<script src="{{ asset('js/plugin.js') }}"></script>
<script src="{{ asset('backend/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('backend/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
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
@stack('custom-scripts')
