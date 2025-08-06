<a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-detail-{{ $row->id }}"><i
        class="fa fa-eye"></i></a>

<!-- modal detail -->
<div class="modal fade" id="modal-detail-{{ $row->id }}">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h6 class="modal-title">Detail Customer</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="row">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <td rowspan="8" style="width:20%;"><img
                                        src="{{ asset('storage/upload/avatar/' . $row->image) }}"
                                        style="max-width:300px;">
                                </td>
                                <td>Nama Customer</td>
                                <td>:</td>
                                <td>{{ $row->full_name }}</td>
                            </tr>
                            <tr>
                                <td>Level Anggota</td>
                                <td>:</td>
                                <td>{{ $row->memberLevel->name }}</td>
                            </tr>
                            <tr>
                                <td>Telp</td>
                                <td>:</td>
                                <td>{{ $row->phone_number }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td>{{ $row->address }}</td>
                            </tr>
                        </table>
                    </div>
                    {{-- <!-- /.col -->
                    <div class="col-md-12">
                        <h4>Riwayat Order</h4>
                        <hr>
                        <table class="table table-sm table-striped table-bordered text-center">
                            <thead class="bg-navy">
                                <tr>
                                    <td>No</td>
                                    <th>Nama Item</th>
                                    <th>No Batch</th>
                                    <th>Tanggal Pesan</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th>Diskon</th>
                                    <th>Kuanti Pesan</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Baris default atau awal -->
                                <tr>
                                    <td>1</td>
                                    <td><input type="text" class="form-control" name="nama" readonly /></td>
                                    <td><input type="text" class="form-control" name="batch" readonly /></td>
                                    <td><input type="date" class="form-control" name="tanggal" readonly /></td>
                                    <td><input type="text" class="form-control" name="satuan" readonly /></td>
                                    <td><input type="text" class="form-control" name="harga" readonly /></td>
                                    <td><input type="text" class="form-control" name="diskon" readonly /></td>
                                    <td><input type="text" class="form-control" name="pesan" readonly /></td>
                                    <td><input type="text" class="form-control" name="total" readonly /></td>
                                </tr>
                            </tbody>
                        </table>
                    </div> --}}
            </div>
            <!-- /.row -->

        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
