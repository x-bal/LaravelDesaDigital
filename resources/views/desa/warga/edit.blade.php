@extends('layouts.app')
@section('title', 'Masyarakat Edit')
@push('bread')
<li class="breadcrumb-item"><a href="{{ route('desa.warga.index') }}">Masyarakat</a></li>
<li class="breadcrumb-item active">Edit</li>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ route('desa.warga.index') }} " class="btn btn-sm btn-outline-info">Back</a>
            </div>
            <div class="card-body">
                <form action="{{ route('desa.warga.update', $warga->id) }}" method="post" id="form">
                    @csrf
                    @method('put')
                    @include('desa.warga.form')
                </form>
            </div>
            <div class="card-footer d-flex flex-row justify-content-end">
                <button class="btn btn-sm btn-outline-success" id="update"><i class="fas fa-edit"></i> Update</button>
            </div>
        </div>
    </div>
</div>
@stop
@push('script')
<script>
    $('#update').on('click', function(){
        $('#form').submit()
    });
</script>
@endpush