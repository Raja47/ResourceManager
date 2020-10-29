
    

@extends('admin.app')

@section('title') {{ "Resources" }} @endsection
@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('content')
    
<div class="container mt-5">
    <h2 class="mb-4">Resources</h2>
    <table class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>title</th>
                <th>category</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
   
@endsection
@push('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
  $(function () {
    
    var table = $('.yajra-datatable').DataTable({
        
        processing: true,
        serverSide: true,
        searchable: true,
        ajax: "{{ route('admin.resources.list') }}",
        columns: [
            {data: 'id', name: 'id' , searchable: true },
            {data: 'title', name: 'title' ,searchable: true },
            {data: 'categories', name: 'categories' , searchable: true },
            {
                data: 'action', 
                name: 'action', 
                searchable: true,
                filter: true,        
            },
        ]
    });
    
  });
</script>
@endpush