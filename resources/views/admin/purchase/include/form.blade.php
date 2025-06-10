<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Tanggal Pemesanan<span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="order_date">
        </div>
        <!-- /.form-group -->
        <div class="form-group">
            <label>No SP<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="purchase_number">
        </div>
    </div>
    <!-- /.col -->
    <div class="col-md-6">
        <!-- /.form-group -->
        <div class="form-group">
            <label>Pilih Supplier<span class="text-danger">*</span></label>
            <select class="form-control select2" style="width: 100%;" name="supplier_id">
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Nomor Invoice<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="invoice_number">
        </div>
    </div>
    <!-- /.col -->
    <div class="col-md-12">
        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="information" class="form-control" rows="1"></textarea>
        </div>
    </div>
    <div class="col-md-12">
        <hr>
        <a href="#" class="btn btn-info btn-sm mb-2" id="tambahBaris">Tambah Produk</a>
        <table id="myTable" class="table table-sm table-striped table-bordered text-center">
            <thead class="bg-navy">
                <tr>
                    <th style="width:20%;">Nama</th>
                    <th>Tanggal Expired</th>
                    <th>Kuantiti</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="tbody">
                <tr class="clone">
                    <td>
                        <select id="selectProduct" name="product_id[]" class="form-control selectProduct"
                            style="width: 100%;">
                            <option>-- Pilih Obat --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                    data-unit="{{ $product->unit->name }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="date" class="form-control" name="expired_date[]"></td>
                    <td><input type="text" class="form-control" name="quantity[]"></td>
                    <td><input type="text" class="form-control" id="product_unit" readonly></td>
                    <td><input type="text" class="form-control" name="price[]"></td>
                    <td><input type="text" class="form-control" name="total_price[]"></td>
                    <td><a href="#" class="btn btn-sm btn-danger hapusBaris"><i class="fa fa-trash"></i></a>
                    </td> <!-- Tombol hapus -->
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- /.row -->

@push('script')
    <!-- Select2 -->
    <script src="{{ asset('template/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(".select2").select2({
            placeholder: 'Pilih Supplier'
        });

        $("#tambahBaris").click(function() {
            var $tableBody = $('#myTable').find("tbody"),
                $trLast = $tableBody.find("tr:last"),
                $trNew = $trLast.clone();

            $trNew.find('input').val('');

            $trLast.after($trNew);
        });

        $("body").on("click", ".hapusBaris", function() {
            $(this).closest('tr').remove();
        });

        $(document).on('change', '.selectProduct', function() {
            var selectedOption = $(this).find(':selected');
            let name = selectedOption.data('name');
            let unit = selectedOption.data('unit');

            var row = $(this).closest('tr');
            row.find('#product_name').val(name);
            row.find('#product_unit').val(unit);
        });

        $(document).on('input', 'input[name="quantity[]"], input[name="price[]"]', function() {
            var row = $(this).closest('tr');
            var quantity = parseFloat(row.find('input[name="quantity[]"]').val());
            var price = parseFloat(row.find('input[name="price[]"]').val());
            var totalPrice = quantity * price;

            row.find('input[name="total_price[]"]').val(totalPrice);
        });
    </script>
@endpush
