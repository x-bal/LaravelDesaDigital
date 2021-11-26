@extends('layouts.app')
@section('title', 'Informasi List')
@push('bread')
<li class="breadcrumb-item active">Informasi</li>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-info">Back</a>
                <a href="{{ route('desa.informasi.create') }}" class="btn btn-sm btn-primary">Create New</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>desa</th>
                                <th>judul</th>
                                <th>deskripsi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($informasis as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->desa->nama_desa }}</td>
                                <td>{{ $data->judul }}</td>
                                <td>{{ $data->deskripsi }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('desa.informasi.edit', $data->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('desa.informasi.destroy', $data->id) }}" method="post">
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