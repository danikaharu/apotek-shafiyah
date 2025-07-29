@extends('layouts.admin.index')

@section('title', 'Diskon Produk')

@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('breadcrumb')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h4>Diskon Produk</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                <li class="breadcrumb-item active">Diskon Produk</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <button type="button" class="btn btn-success btn-sm float-right" data-toggle="modal"
                        data-target="#modal-diskon">
                        <i class="fa fa-plus" aria-hidden="true" style="font-size: 10px;"></i>
                        Tambah Diskon
                    </button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- modal diskon -->
                    <div class="modal fade" id="modal-diskon">
                        <div class="modal-dialog modal-l">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h6 class="modal-title">Tambah Diskon</h6>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="formDiscount" method="POST">
                                        @csrf

                                        <div class="form-group">
                                            <label for="">Nama Obat <span class="text-danger">*</span></label>
                                            <select id="selectProduct" class="form-control select2" name="product_id"
                                                data-placeholder="Pilih Obat">
                                                @foreach ($products as $product)
                                                    <option></option>
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                                        data-name="{{ $product->name }}">
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger" id="product_id_error"></span>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-secondary">Harga Jual</label>
                                            <input type="text" id="product_price" class="form-control" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="type" class="text-secondary">Jenis Diskon <span
                                                    class="text-danger">*</span></label>
                                            <select name="type" id="discount_type" class="form-control" required>
                                                <option value="">-- Pilih Jenis --</option>
                                                <option value="seasonal">Jangka Pendek / Musiman</option>
                                                <option value="volume">Diskon Volume (1 Box / 10 Strip)</option>
                                            </select>
                                            <span class="text-danger" id="type_error"></span>
                                        </div>

                                        <div class="form-group seasonal-field d-none">
                                            <label class="text-secondary">Tanggal Mulai <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="start_date">
                                            <span class="text-danger" id="start_date_error"></span>
                                        </div>

                                        <div class="form-group seasonal-field d-none">
                                            <label class="text-secondary">Tanggal Berakhir <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="end_date">
                                            <span class="text-danger" id="end_date_error"></span>
                                        </div>

                                        <div class="form-group volume-field d-none">
                                            <label class="text-secondary">Minimal Pembelian <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="min_quantity">
                                            <span class="text-danger" id="min_quantity_error"></span>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-secondary">Diskon <span class="text-danger">*</span> (Ex:
                                                5000)</label>
                                            <input type="number" class="form-control" name="discount_amount">
                                            <span class="text-danger" id="discount_amount_error"></span>
                                        </div>

                                        <input type="hidden" name="show_on_dashboard" value="0">
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" name="show_on_dashboard"
                                                id="show_on_dashboard">
                                            <label class="form-check-label" for="show_on_dashboard">Tampilkan di
                                                Dashboard</label>
                                        </div>

                                        <div class="modal-footer justify-content-between">
                                            <button type="submit" class="btn btn-success btn-sm btn-block"><i
                                                    class="fa fa-plus" aria-hidden="true" style="font-size: 10px;"></i>
                                                Tambah</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- /.modal diskon -->


                    </div>

                    <table id="example" class="table table-bordered table-striped table-sm">
                        <thead class="bg-info">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Stok </th>
                                <th>Harga Asli</th>
                                <th>Diskon</th>
                                <th>Harga Jual</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                        </tbody>
                    </table>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('template/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('template/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('#example').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                ajax: '{{ route('admin.discount.index') }}',
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'product.code',
                    }, {
                        data: 'product.name',
                    }, {
                        data: 'product.stock',
                    }, {
                        data: 'product_price',
                    },
                    {
                        data: 'discount',
                    },
                    {
                        data: 'selling_price',
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });


            $('#discount_type').change(function() {
                let selected = $(this).val();
                $('.seasonal-field').toggleClass('d-none', selected !== 'seasonal');
                $('.volume-field').toggleClass('d-none', selected !== 'volume');
            });

            //Initialize Select2 Elements
            $('.select2').select2({
                placeholder: function() {
                    $(this).data('placeholder');
                }
            })

            $('#selectProduct').change(function() {
                var selectedOption = $(this).find(':selected');
                let name = selectedOption.data('name');
                let price = selectedOption.data('price');

                $('#product_name').val(name);
                $('#product_price').val(price);
            })

            $('#formDiscount').on('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.discount.store') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('.modal').removeClass('in');
                        $('.modal').attr("aria-hidden", "true");
                        $('.modal').css("display", "none");
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data berhasil ditambah!',
                        }).then(function() {
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            $.each(errors, function(key, value) {
                                $('#' + key + '_error').text(value);
                            });
                            $('#modal-diskon').modal('show');
                        }
                    }
                });
            });

            $("body").on('submit', `form[role='alert']`, function(event) {
                event.preventDefault();

                Swal.fire({
                    title: $(this).attr('alert-title'),
                    text: $(this).attr('alert-text'),
                    icon: "warning",
                    allowOutsideClick: false,
                    showCancelButton: true,
                    cancelButtonText: "Batal",
                    reverseButton: true,
                    confirmButtonText: "Hapus",
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.submit();
                    }
                })
            });
        });
    </script>
@endpush
