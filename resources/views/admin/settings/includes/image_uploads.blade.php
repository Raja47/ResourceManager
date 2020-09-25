<div class="tile">
    <form action="{{ route('admin.settings.update') }}" method="POST" role="form">
        @csrf
        <h3 class="tile-title">General Settings</h3>
        <hr>
        <div class="tile-body">
            <div class="form-group">
                <label class="control-label" for="site_name">Small Image </label>
                    
                <div class="row">

                    <div class="col-md-4">
                        <input
                            class="form-control"
                            type="number"
                            placeholder="Image Width px"
                            id="small_image_width"
                            name="small_image_width"
                            value="{{ config('settings.small_image_width') }}"
                        />
                    </div>

                    <div class="col-md-4">
                        <input
                            class="form-control"
                            type="Number"
                            placeholder="Image Height px"
                            id="small_image_height"
                            name="small_image_height"
                            value="{{ config('settings.small_image_height') }}"
                        />
                    </div>

                    <div class="col-md-4">
                        <input
                            class="form-control"
                            type="Number"
                            placeholder="Image Quality %"
                            id="small_image_quality"
                            name="small_image_quality"
                            min="50"
                            max="100"
                            value="{{ config('settings.small_image_quality') }}"
                        />

                    </div>

                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="site_name">Medium Image </label>
                    
                <div class="row">

                    <div class="col-md-4">
                        <input
                            class="form-control"
                            type="number"
                            placeholder="Image Width"
                            id="medium_image_width"
                            name="medium_image_width"
                            value="{{ config('settings.medium_image_width') }}"
                        />
                    </div>

                    <div class="col-md-4">
                        <input
                            class="form-control"
                            type="Number"
                            placeholder="Image Height"
                            id="medium_image_height"
                            name="medium_image_height"
                            value="{{ config('settings.medium_image_height') }}"
                        />
                    </div>

                    <div class="col-md-4">
                        <input
                            class="form-control"
                            type="Number"
                            placeholder="Image Quality"
                            id="medium_image_quality"
                            name="medium_image_quality"
                            min="50"
                            max="100"
                            value="{{ config('settings.medium_image_quality') }}"
                        />

                    </div>

                </div>
            </div>
            <div class="form-group">
                <br/>
                <p class="control-label" for="site_title">Note* Original Image will treated as large image & wll not be compressed or resized, Thanks.    </p>
                
            </div>
            
        </div>
        <div class="tile-footer">
            <div class="row d-print-none mt-2">
                <div class="col-12 text-right">
                    <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Settings</button>
                </div>
            </div>
        </div>
    </form>
</div>
