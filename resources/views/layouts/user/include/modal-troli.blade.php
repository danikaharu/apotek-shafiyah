<!-- Modal Troli -->
<div class="modal fade" id="modal-troli" tabindex="-1" aria-labelledby="modalTroliLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalTroliLabel"><i class="fas fa-shopping-cart"></i> Troli Anda</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <div class="spinner-border text-success" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <form action="{{ route('store.order') }}" method="POST" style="display: inline;">
                    @csrf
                    <button id="checkout-btn" type="submit" class="btn btn-success">
                        <i class="fas fa-credit-card"></i> Checkout
                    </button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#modal-troli').on('show.bs.modal', function(e) {
            $.ajax({
                url: '{{ route('cart.get') }}', // Pastikan kamu buat route cart.get
                type: 'GET',
                success: function(response) {
                    $('#cart-modal-body').html(response.cart_html);
                    updateCartTotal(); // refresh badge & total
                },
                error: function(xhr) {
                    $('#cart-modal-body').html(
                        '<p class="text-danger">Gagal memuat troli.</p>');
                }
            });
        });


        function updateCartTotal() {
            $.ajax({
                url: '{{ route('cart.total') }}', // kita buat route baru cart.total
                type: 'GET',
                success: function(response) {
                    $('#cart-total-price').text('Rp ' + response.total_price);
                    $('#badge-cart-count').text(response.total_items);
                }
            });
        }

        // Handle increase/decrease quantity
        $(document).on('click', '.update-quantity', function() {
            var cartItemId = $(this).data('id');
            var action = $(this).data('action');
            var quantityElement = $(this).closest('tr').find('.quantity');
            var itemTotalElement = $(this).closest('tr').find('.item-total');
            var currentQuantity = parseInt(quantityElement.text());

            var newQuantity = (action === 'increase') ? currentQuantity + 1 : currentQuantity - 1;

            if (newQuantity < 1) return;

            $.ajax({
                url: '/cart/update-quantity/' + cartItemId,
                type: 'PATCH',
                data: {
                    quantity: newQuantity,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    quantityElement.text(newQuantity);
                    itemTotalElement.text(response.item_total);

                    updateCartTotal(); // Recalculate total cart price
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });

        // Handle remove item
        $(document).on('click', '.remove-item', function() {
            var cartItemId = $(this).data('id');

            $.ajax({
                url: '/cart/remove/' + cartItemId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#cart-item-' + cartItemId).remove();
                    updateCartTotal(); // Recalculate total cart price
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });
    });
</script>
