@extends('layouts.app')
@section('title', 'Rates Edit')
@push('bread')
<li class="breadcrumb-item"><a href="{{ route('desa.rates.index') }}">Rates</a></li>
<li class="breadcrumb-item active">Edit</li>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ route('desa.rates.index') }} " class="btn btn-sm btn-info">Back</a>
            </div>
            <div class="card-body">
                <form action="{{ route('desa.rates.update', $rate->id) }}" method="post" id="form" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    @include('desa.rates.form')
                </form>
            </div>
            <div class="card-footer d-flex flex-row justify-content-end">
                <button class="btn btn-sm btn-success" id="update">Update</button>
            </div>
        </div>
    </div>
</div>
@stop
@push('script')
<script>
    $('#update').on('click', function() {
        $('#form').submit()
    });
</script>
@endpush