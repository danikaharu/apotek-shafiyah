@extends('layouts.user.index')

@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Gagal',
                text: 'Pembayaran Anda gagal atau kadaluarsa.',
                confirmButtonText: 'Coba Lagi'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ url('/') }}';
                }
            });
        });
    </script>
@endsection
