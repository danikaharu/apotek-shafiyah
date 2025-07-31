@extends('layouts.auth.index')

@section('title', 'Register')

@section('content')
    <p class="login-box-msg">Daftar pengguna baru</p>

    <form action="{{ route('register') }}" method="post">
        @csrf
        <div class="input-group mb-3">
            <input type="text" class="form-control @error('email')
                is-invalid
            @enderror"
                placeholder="Email" name="email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3">
            <input type="text" class="form-control @error('username')
                is-invalid
            @enderror"
                placeholder="Username" name="username">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
            @error('username')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3">
            <input type="password" class="form-control @error('password')
                is-invalid
            @enderror"
                placeholder="Password" name="password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Konfirmasi password" name="password_confirmation">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- /.col -->
            <div class="col-12">
                <button type="submit" class="btn btn-success btn-block">Daftar</button>
            </div>
            <!-- /.col -->
        </div>
    </form>
    <div class="mt-2 text-center">
        Sudah punya akun?, Login <a href="{{ route('login') }}" class="text-center">disini</a>
    </div>
@endsection
