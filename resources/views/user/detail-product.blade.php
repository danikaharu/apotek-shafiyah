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
                                        <p>{!! $product->information !!} </p>
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
                                                    @currency($product->price)
                                                @else
                                                    @currency($product->price - $product->discount->discount)
                                                @endif
                                            </h2>
                                            <h5 class="mt-0">
                                                <small style="font-style: italic;">Harga Jual : @currency($product->price),
                                                    Diskon : @if ($product->discount == null)
                                                        -
                                                    @else
                                                        @currency($product->discount->discount)
                                                    @endif
                                                </small>
                                            </h5>
                                        </div>

                                        <div class="row mt-4">
                                            <a class="btn btn-secondary btn-lg my-2 mr-2" href="{{ url()->previous() }}">
                                                <i class="fas fa-arrow-left fa-md mr-2"></i>
                                                Kembali
                                            </a>
                                            @if (auth()->user())
                                                <form action="{{ route('store.cart') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <button type="submit" class="btn btn-success btn-block btn-lg my-2">
                                                        <i class="fas fa-shopping-cart"></i> Tambah
                                                    </button>
                                                </form>
                                            @endif
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
