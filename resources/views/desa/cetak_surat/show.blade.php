@extends('layouts.app')
@section('title', 'Cetak Surat Show')
@push('bread')
<li class="breadcrumb-item"><a href="{{ route('desa.cetak_surat.index') }}">Cetak Surat</a></li>
<li class="breadcrumb-item active">Show</li>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ route('desa.cetak_surat.index') }} " class="btn btn-sm btn-outline-info"><i class="fas fa-long-arrow-alt-left"></i> Back</a>
            </div>
            <div class="card-body">
                <form action="{{ route('desa.cetak_surat.store') }}" method="post" id="form">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="" class="form-label">Nik</label>
                            <select id="select2" name="warga_id" class="form-control select2-ajax-cetak-surat @error('warga_id') is-invalid @enderror"></select>
                            @error('warga_id')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                    </div>
                    <div class="form-group row" id="warga">
                        <div class="col-md-4">
                            <label for="" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Tanggal Lahir</label>
                            <input type="text" class="form-control" name="tanggal_lahir" id="tanggal_lahir" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Umur</label>
                            <input type="text" class="form-control" name="umur" id="umur" readonly>
                        </div>
                    </div>
                    <hr>
                    @include('desa.cetak_surat.form')
                    @if($table == 'permohonan_surat_kurang_mampus')
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>no</th>
                                        <th>nik</th>
                                        <th>nama</th>
                                        <th>jenis kelamin</th>
                                        <th>tempat tanggal lahir</th>
                                    </tr>
                                </thead>
                                <tbody id="tableloop">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </form>

            </div>
            <div class="card-footer d-flex flex-row justify-content-end">
                <button class="btn btn-sm btn-outline-success" id="store"><i class="fas fa-cloud-upload-alt"></i> Store</button>
            </div>
        </div>
    </div>
</div>
@stop
@push('script')
<script>
    $('#select2').on('change', function() {
        const id = $(this).val()
        $.ajax({
            url: `/api/select2/cetaksurat/${id}`,
            success: data => {
                console.log(data)
                $('#tempat_lahir').val(data.resource.tempat_lahir)
                $('#tanggal_lahir').val(data.resource.tanggal_lahir)
                $('#umur').val(data.umur)
            }
        })
    })
    $('#store').on('click', function() {
        $('#form').submit()
    });
</script>
@if($table == 'permohonan_surat_kurang_mampus')
<script>
    $('#select2').on('change', function() {
        const id = $(this).val()
        $('#tableloop').html('')
        $.ajax({
            url: `/api/select2/findfamily/${id}`,
            success: data => {
                data.map(data => {
                    return (
                        $('#tableloop').append(
                            `
                                <tr class="${data.id == id ? 'text-primary text-bold' : 'text-dark' }">
                                    <td>${data.id}</td>
                                    <td>${data.nik}</td>
                                    <td>${data.nama_warga}</td>
                                    <td>${data.jenis_kelamin}</td>
                                    <td>${data.tempat_lahir} / ${data.tanggal_lahir}</td>
                                </tr>
                            `
                        )
                    )
                })
            }
        })
    })
</script>
@endif
@endpush