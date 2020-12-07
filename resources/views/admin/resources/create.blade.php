@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
=@section('content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-shopping-bag"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
        </div>
    </div>
    @include('admin.partials.flash')
    <div class="row user">
          <div class="col-md-3">
                <div class="tile p-0">
                <ul class="nav flex-column nav-tabs user-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#Scrapper" data-toggle="tab">Scrapper</a></li>
                </ul>
            </div>
          </div>
          <div class="col-md-9">
            <div class="tab-content">
                <div class="tab-pane active" id="Scrapper">
                    <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="resource_source" id="resource_source" class="form-control">
                                    <option value="">Select Source</option>
                                    <option  value="themeforest">ThemeForest</option>
                                    <option  value="shutterstock">ShutterStock</option>
                                    <option value="istock" >iStock</option>
                                </select>
                            </div>
                            <div class="col-md-9">
                                <input id="resource_url" class="form-control" type="text" placeholder="enter source url">
                            </div>
                        </div>      
                        
                  
                  </div>
                  <div class="col-md-3">
                        <input class="form-control" type="button" value="Scrap" id="scrap" >
                  </div>
                  </div>
                </div>
            </div>
          </div>
          
    </div>    
    <hr/>
    
    <div class="row user">
        <div class="col-md-3">
            <div class="tile p-0">
                <ul class="nav flex-column nav-tabs user-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#general" data-toggle="tab">General</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    <div class="tile">
                        <form action="{{ route('admin.resources.store') }}" method="POST" role="form">
                            @csrf
                            <h3 class="tile-title">Resource Information</h3>
                            <hr>
                            <div class="tile-body">
                                <div class="form-group">
                                    <label class="control-label" for="title">Title*</label>
                                    <input
                                        class="form-control @error('title') is-invalid @enderror"
                                        type="text"
                                        placeholder="Enter Resource Title"
                                        id="title"
                                        name="title"
                                        value="{{ old('title') }}"
                                    />
                                    <div class="invalid-feedback active">
                                        <i class="fa fa-exclamation-circle fa-fw"></i> @error('title') <span>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                               
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="categories">Category*</label>
                                            <select name="resource_category_id" id="categories" class="form-control" >
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                    
                               <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="image">Display Image Url</label>
                                            <input
                                                class="form-control @error('image') is-invalid @enderror"
                                                type="text"
                                                placeholder="Enter Resource Title"
                                                id="image"
                                                name="image"
                                                value="{{ old('image') }}"
                                            />
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="demo_url">Demo Url (For plugins & themes)</label>
                                            <input
                                                class="form-control @error('demo_url') is-invalid @enderror"
                                                type="text"
                                                placeholder="Enter Resource Demo Url"
                                                id="demo_url"
                                                name="demo_url"
                                                value="{{ old('demo_url') }}"
                                            />
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="tags">Keywords / Tags</label>
                                            <select name="keywords[]" id="keywords" class="form-control" multiple>
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                
                                
                                <div class="form-group">
                                    <label class="control-label" for="description">Description</label>
                                    <textarea name="description" id="description" rows="8" class="form-control" >{{ old('description') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   id="status"
                                                   name="status"
                                                   checked="true"
                                                />Status
                                        </label>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="tile-footer">
                                <div class="row d-print-none mt-2">
                                    <div class="col-12 text-right">
                                        <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save Resource</button>
                                        <a class="btn btn-danger" href="{{ route('admin.resources.index') }}"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('backend/js/plugins/select2.min.js') }}"></script>
    <script>
        $( document ).ready(function() {
            
            $("#keywords").select2({
                tags: true,
                tokenSeparators: [',']
            });
            
            $("#scrap").on('click' , function(){
                
                var source = $("#resource_source").val();
                var url = $("#resource_url").val();
                if(source == undefined || source == "" || url == undefined || url == ""){
                    alert("kindly put select proper resource and url address");
                    return false;
                }
                $.ajax({
                     type:'POST',
                     url:"{{ route('admin.scrapper.scrap')}}",
                     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                     data: {'source': source , 'url': url},
                     success:function(data){
                         
                        if(data.status != true){
                            alert('unable to scrap source');
                        }else{
                            
                            if(data.resource){
                                if(data.resource.name){
                                    $("#title").val(data.resource.name);
                                }
                                
                                if(data.resource.desc){
                                    $("#description").val(data.resource.desc)
                                }
                                
                                if(data.resource.tags){
                                    
                                    var tagsArray = data.resource.tags.split(",");
                                    
                                    for(var i = 0; i < tagsArray.length ; i++){
                                        var newOption = new Option(tagsArray[i], tagsArray[i], true, true);
                                        // Append it to the select
                                        $('#keywords').append(newOption).trigger('change');
                                    }
                                }
                                if(data.resource.category){
                                    var id = "";
                                    if(data.resource.category == "image"){
                                        id = "1";
                                    }else if(data.resource.category == "image-photo"){
                                        id = "1";
                                    }else if(data.resource.category == "image-vector"){
                                        id = "5";
                                    }else if(data.resource.category == "image-illustration"){
                                        id = "6";
                                    }else if(data.resource.category == "video"){
                                        id = "2";
                                    }else if(data.resource.category == "theme"){
                                        id = "4";
                                    }else if(data.resource.category == "plugin"){
                                        id = "3";
                                    } 
                                    $("#categories").val(id);
                                }
                                
                                if(data.resource.image != ""){
                                    $("#image").val(data.resource.image);
                                }    
                            
                        }
                    }
                    },
                    error: function (request, status, error) {
                        alert('unable to scrap Resource due to '+request.responseText);
                    }
                });
            });
          
            
            
        });
        
    </script>
@endpush
