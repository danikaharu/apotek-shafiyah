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
        // Fungsi untuk memperbarui status tombol berdasarkan jumlah dan stok
        function updateQuantityButtons() {
            $('.update-quantity').each(function() {
                var $button = $(this);
                var $row = $button.closest('tr');
                var quantity = parseInt($row.find('.quantity').text());
                var stock = parseInt($row.find('.stock').text());

                if ($button.data('action') === 'increase') {
                    $button.prop('disabled', quantity >= stock);
                } else if ($button.data('action') === 'decrease') {
                    $button.prop('disabled', quantity <= 1);
                }
            });
        }

        // Fungsi untuk memperbarui total troli
        function updateCartTotal() {
            $.ajax({
                url: '{{ route('cart.total') }}',
                type: 'GET',
                success: function(response) {
                    $('#cart-total-price').text('Rp ' + response.total_price);
                    $('#badge-cart-count').text(response.total_items);
                }
            });
        }

        // Ketika modal troli ditampilkan
        $('#modal-troli').on('show.bs.modal', function(e) {
            $.ajax({
                url: '{{ route('cart.get') }}',
                type: 'GET',
                success: function(response) {
                    $('#cart-modal-body').html(response.cart_html);
                    updateCartTotal();
                    updateQuantityButtons
                (); // Memperbarui status tombol setelah memuat troli
                },
                error: function(xhr) {
                    $('#cart-modal-body').html(
                        '<p class="text-danger">Gagal memuat troli.</p>');
                }
            });
        });

        // Menangani klik pada tombol increase/decrease
        $(document).on('click', '.update-quantity', function() {
            var $button = $(this);
            var cartItemId = $button.data('id');
            var action = $button.data('action');
            var $row = $button.closest('tr');
            var quantityElement = $row.find('.quantity');
            var itemTotalElement = $row.find('.item-total');
            var currentQuantity = parseInt(quantityElement.text());
            var stock = parseInt($row.find('.stock').text());

            var newQuantity = (action === 'increase') ? currentQuantity + 1 : currentQuantity - 1;

            // Validasi jumlah baru
            if (newQuantity < 1 || newQuantity > stock) return;

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
                    updateCartTotal();
                    updateQuantityButtons(); // Memperbarui status tombol setelah perubahan
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        alert(xhr.responseJSON.message);
                    } else {
                        alert('Terjadi kesalahan saat memperbarui jumlah.');
                    }
                }
            });
        });

        // Menangani penghapusan item dari troli
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
                    updateCartTotal();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });
    });
</script>
