@extends('layouts.app')
@section('title', 'Antrian List')
@push('bread')
<li class="breadcrumb-item active">Antrian</li>
@endpush
@section('content')
@if(session('speech'))
{!! session('speech') !!}
@endif
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Antrian Tanggal : {{ date('d/m/Y') }}</h5>
            </div>
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-info"><i class="fas fa-arrow-left"></i> Back</a>
                <a href="{{ route('desa.antrian.create') }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus"></i> Create New</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No Antrian</th>
                                <th>Nik</th>
                                <th>Nama</th>
                                <th>Jenis Surat</th>
                                <th>Tanggal Antri</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($antrians as $antrian)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <th>{{ $antrian->no_antrian }}</th>
                                <th>{{ $antrian->warga->nik }}</th>
                                <th>{{ $antrian->warga->nama_warga }}</th>
                                <th>{{ $antrian->jenis->jenis_surat }}</th>
                                <th>{{ $antrian->tanggal_antri }}</th>
                                <td>
                                    <div class="btn-group">
                                        @if($antrian->status != 2)
                                        <a href="{{ route('desa.antrian.status', $antrian->id) }}?status={{ $antrian->status == 0 ? 1 : 2 }}" class="btn btn-sm btn-outline-success">{{ $antrian->status == 0 ? 'Panggil' : 'Konfirmasi' }}</a>
                                        @endif
                                        <a href="{{ route('desa.antrian.edit', $antrian->id) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i> Edit</a>
                                        <form action="{{ route('desa.antrian.destroy', $antrian->id) }}" method="post">
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