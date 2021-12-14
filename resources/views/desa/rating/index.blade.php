@extends('layouts.app')
@section('title', 'Rating')
@push('head')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
@endpush
@push('bread')
<li class="breadcrumb-item active">Rating</li>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-info"><i class="fas fa-long-arrow-alt-left"></i> <span>Back</span></a>
                <!-- <a href="{{ route('desa.rating.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> <span>add</span></a> -->
            </div>
            <div class="card-body">
                <form action="" method="get" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="date" name="from" id="from" class="form-control" value="{{ request('from') ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <input type="date" name="to" id="to" class="form-control" value="{{ request('to') ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Total</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ratings as $rating)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $rating->name }}</td>
                                <td>
                                    {{ $rating->ratings()->count() }}
                                </td>
                                <!-- <td>
                                    <div class="btn-group">
                                        <a href="{{ route('desa.rating.edit', $rating->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                        <form action="{{ route('desa.rating.destroy', $rating->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-sm btn-danger delete_confirm" type="submit"><i class="fas fa-trash"></i> Delete</button>
                                        </form>
                                    </div>
                                </td> -->
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
<script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
<script>
    $('#datatable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    })
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