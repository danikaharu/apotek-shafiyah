<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | @yield('title')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/adminlte.min.css') }}">
</head>
<style>
    .dropdown-item:hover {
        background-color: #28a745;
        /* Warna hijau saat tombol dihover */
        color: #fff;
        /* Warna teks putih */
    }

    .custom-hr {
        border-color: #28a745;
        /* Warna hijau */
        border-width: 2px;
        /* Ketebalan garis */
    }

    .logo-k {
        position: absolute;
        top: 1;
        left: 1;
        width: 20px;
        /* Sesuaikan ukuran logo "K" */
        height: auto;
        z-index: 1;
        /* Pastikan logo "K" muncul di atas gambar */
    }

    .limited-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        /* Batasi teks menjadi dua baris */
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-size: 16px;
    }

    @keyframes blink {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    .blink-animation {
        animation: blink 1s;
        /* Ubah 1.5s sesuai dengan kecepatan yang diinginkan */
    }

    .pagination-success .page-link {
        color: #fff;
        /* Warna teks */
        background-color: #28a745;
        /* Warna latar */
        border-color: #28a745;
        /* Warna border */
    }

    .pagination-success .page-link:hover {
        background-color: #074b17;
        /* Warna latar */
    }

    .pagination-success .page-item.disabled .page-link {
        color: #2c2d2f;
        /* Warna teks untuk tombol Previous saat nonaktif */
    }
</style>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        @include('layouts.user.include.navbar')
        <!-- /.navbar -->


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            @yield('content')
            <!-- /.content-wrapper -->
        </div>
        <!-- ./wrapper -->

        @include('layouts.user.include.footer')

        <!-- REQUIRED SCRIPTS -->

        <!-- jQuery -->
        <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
        <!-- AdminLTE App -->
        <script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>
        <script>
            window.onscroll = function() {
                scrollFunction()
            };

            function scrollFunction() {
                var navbar1 = document.querySelector('.navbar');
                var navbar2 = document.querySelector('.navbar-fixed');

                if (window.pageYOffset >= navbar1.offsetTop + navbar1.offsetHeight) {
                    navbar2.classList.add('fixed-top');
                } else {
                    navbar2.classList.remove('fixed-top');
                }
            }
        </script>
        @stack('script')

</body>

</html>
