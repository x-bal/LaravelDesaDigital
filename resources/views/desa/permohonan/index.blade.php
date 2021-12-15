@extends('layouts.app')
@section('title', 'Permohonan Surat List')
@push('bread')
<li class="breadcrumb-item active">Permohonan Surat</li>
@endpush
@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-info"><i class="fas fa-arrow-left"></i> Back</a>
                <a href="{{ route('desa.permohonan.create') }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus"></i> Create New</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nik</th>
                                <th>Nama</th>
                                <th>Jenis Surat</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permohonans as $permohonanSurat)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <th>{{ $permohonanSurat->warga->nik }}</th>
                                <th>{{ $permohonanSurat->warga->nama_warga }}</th>
                                <th>{{ $permohonanSurat->jenis->jenis_surat }}</th>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('desa.cetak_surat.show',$permohonanSurat->id) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-file"></i> Cetak Surat</a>
                                        <a href="{{ route('desa.permohonan.edit', $permohonanSurat->id) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i> Edit</a>
                                        <form action="{{ route('desa.permohonan.destroy', $permohonanSurat->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-sm btn-outline-danger delete_confirm" type="submit"><i class="fas fa-trash"></i> Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@push('script')
<script>
    $('#datatable').DataTable()
    $('.delete_confirm').click(function(event) {
        let form = $(this).closest("form");
        event.preventDefault();
        Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            })
            .then((willDelete) => {
                if (willDelete.value) {
                    form.submit();
                }
            });
    });
</script>
@endpush