@extends('layouts.user.index')

@section('title', 'Beranda')


@section('content')
    <div class="content-header">
        <div class="container">
            <div class="card h-10">
                <!-- /.card-header -->
                <div class="card-body h-80">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            @foreach ($banners as $key => $banner)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <img class="d-block w-100" src="{{ asset('storage/upload/banner/' . $banner->image) }}"
                                        alt="{{ $banner->description }}">
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-custom-icon" aria-hidden="true">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-custom-icon" aria-hidden="true">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="m-0  text-success">Penawaran Obat </h4>
                </div><!-- /.col -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <div class="content blink-animation">
            <div class="content">
                <div class="container">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-lg-12">
                                    <div class="row">
                                        @forelse ($products as $product)
                                            <div class="col-md-3 col-sm-4 mb-1">
                                                <div class="card h-80">
                                                    <div class="card-body text-center">
                                                        @if ($product->type_id == 1)
                                                            <img src="{{ asset('template/dist/img/k.png') }}" class="logo-k"
                                                                alt="Logo K">
                                                        @endif
                                                        <img src="{{ asset('storage/upload/produk/' . $product->image) }}"
                                                            class="card-img-top" alt="Produk ">
                                                        <p class="limited-text"><b>{{ $product->name }},
                                                            </b>{!! Str::words($product->information, 10, '...') !!}</p>
                                                    </div>
                                                    <div class="card-footer text-center">
                                                        <h4 class="text-success">@currency($product->price)
                                                            /
                                                            {{ $product->unit->name }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-md-12">
                                                <h3 class="text-center">Belum ada produk</h3>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-right">
                                <a href="{{ route('product') }}" class="text-success"> Tampilkan semua produk <i
                                        class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>

            <!-- /.content -->
        </div>
    </div>

    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="m-0  text-success">Obat Diskon </h4>
                </div><!-- /.col -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <div class="content blink-animation">
            <div class="content">
                <div class="container">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-lg-12">
                                    <div class="row">
                                        @forelse($productDiscount as $item)
                                            <div class="col-md-3 col-sm-4 mb-1">
                                                <div class="card h-80">
                                                    <!-- kalau ada diskon pake ini  -->
                                                    <div class="ribbon-wrapper ribbon-lg">
                                                        <div class="ribbon bg-success">
                                                            <b>Diskon @currency($item->discount_amount) </b>
                                                        </div>
                                                    </div>
                                                    <!-- batas diskon -->
                                                    <div class="card-body text-center">
                                                        @if ($item->product->type_id == 1)
                                                            <img src="{{ asset('template/dist/img/k.png') }}" class="logo-k"
                                                                alt="Logo K">
                                                        @endif
                                                        <img src="{{ asset('storage/upload/produk/' . $item->product->image) }}"
                                                            class="card-img-top" alt="Produk">
                                                        <p class="limited-text"><b>{{ $item->product->name }},
                                                            </b>{!! Str::words($item->product->information, 10, '...') !!}</p>
                                                    </div>
                                                    <div class="card-footer text-center">
                                                        <h4 class="text-success">@currency($item->product->price) /
                                                            {{ $item->product->unit->name }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-md-12">
                                                <h3 class="text-center">Belum ada produk yang di diskon</h3>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-right">
                                <a href="{{ route('discount') }}" class="text-success"> Tampilkan semua produk <i
                                        class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>

            <!-- /.content -->
        </div>
    </div>
@endsection
