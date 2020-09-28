@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-briefcase"></i> {{ $pageTitle }}</h1>
        </div>
    </div>
    @include('admin.partials.flash')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="tile">
                <h3 class="tile-title">{{ $subTitle }}</h3>
                <form action="{{ route('admin.banners.store') }}" method="POST" role="form" enctype="multipart/form-data">
                    @csrf
                    <div class="tile-body">
                        <div class="form-group">
                            <label class="control-label" for="title">Title <span class="m-l-5 text-danger"> *</span></label>
                            <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" id="title" value="{{ old('title') }}"/>
                            @error('title') {{ $message }} @enderror
                        </div>
                         <div class="form-group">
                            <label class="control-label" for="name">Description <span class="m-l-5 text-danger"> *</span></label>
                            <input class="form-control @error('description') is-invalid @enderror" type="text" name="description" id=description value="{{ old('description') }}"/>
                            @error('description') {{ $message }} @enderror
                        </div>  
                        <div class="form-group">
                            <label class="control-label">Banner</label>
                            <input class="form-control @error('banner') is-invalid @enderror" type="file" id="banner" name="banner"/>
                            @error('banner') {{ $message }} @enderror
                        </div>
                        <div class="form-group">
                            
                            <input class="@error('status') is-invalid @enderror" type="checkbox" id="status" name="status" />&nbsp;&nbsp;<label class="control-label" for="status">Publish </label>
                            @error('status') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="tile-footer">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save Banner</button>
                        &nbsp;&nbsp;&nbsp;
                        <a class="btn btn-secondary" href="{{ route('admin.banners.index') }}"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

@endpush
