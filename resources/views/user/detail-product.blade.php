@extends('layouts.user.index')

@section('title', 'Detail Produk')

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5 class="m-0  text-success"> Detail Produk</h5>
                </div><!-- /.col -->
            </div><!-- /.container-fluid -->
            <hr class="custom-hr">
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <div class="content blink-animation">
            <div class="content">
                <div class="container">
                    <div class="row mb-2">
                        <div class="card card-solid">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="col-12">
                                            <img src="{{ asset('storage/upload/produk/' . $product->image) }}"
                                                class="product-image" alt="Product Image">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <h3 class="my-3"><b>{{ $product->name }}</b>
                                        </h3>
                                        <h5><b>Deskripsi :</b></h5>
                                        <p>{{ $product->information }} </p>
                                        <table class="table table-sm">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <h4>Kategori</h4>
                                                    </td>
                                                    <td>:</td>
                                                    <td>{{ $product->category->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h4>Jenis Obat</h4>
                                                    </td>
                                                    <td>:</td>
                                                    <td>{{ $product->type->name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="bg-gray py-2 px-3 mt-4">
                                            <h2 class="mb-0">
                                                @if ($product->discount == null)
                                                    Rp. {{ $product->price }}
                                                @else
                                                    Rp. {{ $product->price - $product->discount->discount }}
                                                @endif
                                            </h2>
                                            <h5 class="mt-0">
                                                <small style="font-style: italic;">Harga Jual : Rp. {{ $product->price }},
                                                    Disc : @if ($product->discount == null)
                                                        0
                                                    @else
                                                        {{ $product->discount->discount }}
                                                    @endif
                                                </small>
                                            </h5>
                                        </div>

                                        <div class="mt-4">
                                            <a class="btn btn-secondary btn-lg" href="{{ url()->previous() }}">
                                                <i class="fas fa-arrow-left fa-md mr-2"></i>
                                                Kembali
                                            </a>
                                            <div class="btn btn-success btn-lg">
                                                <i class="fas fa-cart-plus fa-lg mr-2"></i>
                                                Tambahkan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>

            <!-- /.content -->
        </div>
    </div>
@endsection
