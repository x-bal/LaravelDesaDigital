@extends('layouts.app')
@section('title', 'Masyarakat List')
@push('bread')
<li class="breadcrumb-item active">Masyarakat</li>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-info">Back</a>
                <a href="{{ route('desa.warga.create') }}" class="btn btn-sm btn-primary">Create New</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>nik</th>
                                <th>nama</th>
                                <th>jenis kelamin</th>
                                <th>tempat lahir</th>
                                <th>tanggal lahir</th>
                                <th>desa</th>
                                <th>kecamatan</th>
                                <th>kabupaten</th>
                                <th>provinsi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wargas as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <th>{{ $data->nik }}</th>
                                <th>{{ $data->nama }}</th>
                                <th>{{ $data->jenis_kelamin }}</th>
                                <th>{{ $data->tempat_lahir }}</th>
                                <th>{{ $data->tanggal_lahir }}</th>
                                <th>{{ $data->desa }}</th>
                                <th>{{ $data->kecamatan }}</th>
                                <th>{{ $data->kabupaten }}</th>
                                <th>{{ $data->provinsi }}</th>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('desa.warga.edit', $data->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('desa.warga.destroy', $data->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-sm btn-danger delete_confirm" type="submit">Destroy</button>
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