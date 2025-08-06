@extends('layouts.admin.index')

@section('title', 'Edit Level Anggota')

@section('breadcrumb')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h4>Edit Level Anggota</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                <li class="breadcrumb-item active">Edit Level Anggota</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <form id="formUpdateMemberLevel" action="{{ route('admin.member-level.update', $memberLevel->id) }}" method="POST"
            enctype="multipart/form-data">
            <div class="modal-body">
                @csrf
                @method('PUT')
                @include('admin.member-level.include.form')
                <!-- /.row -->

            </div>
            <div class="modal-footer justify-content-between">
                <button type="submit" class="btn btn-success btn-sm btn-block"><i class="fas fa-pencil-alt"
                        aria-hidden="true" style="font-size: 12px;"></i>
                    Update</button>
            </div>
        </form>
    </div>
@endsection
