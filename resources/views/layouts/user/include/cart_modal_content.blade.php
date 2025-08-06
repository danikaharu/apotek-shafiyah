@if ($cart && $cart->details->isNotEmpty())
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
                    @php
                        $product = $item->product;
                        $discount = $product->discount;

                        $isVolumeDiscount =
                            $discount && $discount->type === 'volume' && $item->amount >= $discount->min_quantity;
                        $isSeasonalDiscount =
                            $discount &&
                            $discount->type === 'seasonal' &&
                            now()->between($discount->start_date, $discount->end_date);

                        $isDiscounted = $isVolumeDiscount || $isSeasonalDiscount;
                        $discountAmount = $isDiscounted ? $discount->discount_amount : 0;

                        $finalPrice = $product->price - $discountAmount;
                        $finalTotal = $finalPrice * $item->amount;
                    @endphp

                    <tr id="cart-item-{{ $item->id }}">
                        <td>{{ $product->name }}</td>
                        <td>
                            <button class="btn btn-sm btn-info update-quantity" data-id="{{ $item->id }}"
                                data-action="decrease">-</button>
                            <span class="quantity">{{ $item->amount }}</span>
                            <button class="btn btn-sm btn-info update-quantity" data-id="{{ $item->id }}"
                                data-action="increase">+</button>
                            <br>
                            <small class="text-muted">Stok: <span class="stock">{{ $product->stock }}</span></small>
                        </td>
                        <td>
                            @if ($isDiscounted)
                                <del class="text-muted">Rp {{ number_format($product->price, 0, ',', '.') }}</del><br>
                                <strong>Rp {{ number_format($finalPrice, 0, ',', '.') }}</strong>
                                <br>
                                <small class="badge badge-success">
                                    {{ $discount->type === 'volume' ? 'Diskon Volume' : 'Diskon Musiman' }}
                                </small>
                            @else
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>Rp <span class="item-total">{{ number_format($finalTotal, 0, ',', '.') }}</span></td>
                        <td>
                            <button class="btn btn-sm btn-danger remove-item" data-id="{{ $item->id }}">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Subtotal</strong></td>
                    <td colspan="2"><strong id="cart-subtotal">Rp 0</strong></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right"><strong>Diskon Produk</strong></td>
                    <td colspan="2"><strong id="cart-discount-product">- Rp 0</strong></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right"><strong>Diskon Member</strong></td>
                    <td colspan="2"><strong id="cart-discount-member">- Rp 0</strong></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right"><strong>Diskon Loyalitas</strong></td>
                    <td colspan="2"><strong id="cart-discount-loyalty">- Rp 0</strong></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                    <td colspan="2"><strong id="cart-grand-total">Rp 0</strong></td>
                </tr>
            </tfoot>


        </table>
    </div>
@else
    <div class="alert alert-info text-center" role="alert">
        Troli anda masih kosong. Ayo belanja sekarang! 🛒
    </div>
@endif
