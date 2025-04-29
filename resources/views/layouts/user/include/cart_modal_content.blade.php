@if (!empty($cart) && $cart->details && $cart->details->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart->details as $item)
                    <tr id="cart-item-{{ $item->id }}">
                        <td>{{ $item->product->name }}</td>
                        <td>
                            <button class="btn btn-sm btn-info update-quantity" data-id="{{ $item->id }}"
                                data-action="decrease">-</button>
                            <span class="quantity">{{ $item->amount }}</span>
                            <button class="btn btn-sm btn-info update-quantity" data-id="{{ $item->id }}"
                                data-action="increase">+</button>
                        </td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>Rp <span class="item-total">{{ number_format($item->total_price, 0, ',', '.') }}</span></td>
                        <td>
                            <button class="btn btn-sm btn-danger remove-item" data-id="{{ $item->id }}">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info text-center" role="alert">
        Troli anda masih kosong. Ayo belanja sekarang! 🛒
    </div>
@endif
