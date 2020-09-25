@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-briefcase"></i> {{ $pageTitle }}</h1>
            <p>{{ $subTitle }}</p>
        </div>
        <a href="{{ route('admin.category.banners.create') }}" class="btn btn-primary pull-right">Add banner</a>
    </div>
    @include('admin.partials.flash')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                        <tr>
                            <th> # </th>
                            <th> Title </th>
                            <th> Category </th>
                            <th> Description </th>
                            <th> Image </th>
                            <th> Status </th>
                            <th style="width:100px; min-width:100px;" class="text-center text-danger"><i class="fa fa-bolt"> </i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($banners as $banner)
                            <tr>
                                <td>{{ $banner->id }}</td>
                                <td>{{ $banner->title }}</td>
                                <td>{{ $banner->category->name }}</td>

                                <td>{{ $banner->description }}</td>
                                <td> @if ($banner->banner != null)
                                    <div class="col-md-2">
                                        <figure class="mt-2" style="width: 160px; height: auto;">
                                                <img src="{{ asset('storage/'.$banner->banner) }}" id="banner" class="img-fluid" alt="img">
                                        </figure>
                                    </div>
                                @endif</td>
                                 <td class="text-center">
                                        @if ( $banner->status == '1' )
                                            <span class="badge badge-success">Published</span>
                                        @else
                                            <span class="badge badge-danger">Not Published</span>
                                        @endif
                                    </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Second group">
                                        <a href="{{ route('admin.category.banners.edit', $banner->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        <a href="{{ route('admin.category.banners.delete', $banner->id) }}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('backend/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
@endpush

