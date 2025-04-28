@extends('layouts.user.index')

@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Menunggu Pembayaran',
                text: 'Pesanan Anda sedang menunggu pembayaran.',
                confirmButtonText: 'Cek Status Pembayaran'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ url('/') }}';
                }
            });
        });
    </script>
@endsection
