<div class="tile">
    <form action="{{ route('admin.settings.update') }}" method="POST" role="form" enctype="multipart/form-data">
        @csrf
        <h3 class="tile-title">Site Logo</h3>
        <hr>
        <div class="tile-body">
            <div class="row">
                <div class="col-3">
                    @if (config('settings.site_logo') != null)
                        <img src="{{ asset('storage/'.config('settings.site_logo')) }}" id="logoImg" style="width: 80px; height: auto;">
                    @else
                        <img src="https://via.placeholder.com/80x80?text=Placeholder+Image" id="logoImg" style="width: 80px; height: auto;">
                    @endif
                </div>
                <div class="col-9">
                    <div class="form-group">
                        <label class="control-label">Site Logo</label>
                        <input class="form-control" type="file" name="site_logo" onchange="loadFile(event,'logoImg')"/>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-3">
                    @if (config('settings.site_favicon') != null)
                        <img src="{{ asset('storage/'.config('settings.site_favicon')) }}" id="faviconImg" style="width: 80px; height: auto;">
                    @else
                        <img src="https://via.placeholder.com/80x80?text=Placeholder+Image" id="faviconImg" style="width: 80px; height: auto;">
                    @endif
                </div>
                <div class="col-9">
                    <div class="form-group">
                        <label class="control-label">Site Favicon</label>
                        <input class="form-control" type="file" name="site_favicon" onchange="loadFile(event,'faviconImg')"/>
                    </div>
                </div>
            </div>
             <div class="row mt-4">
                <div class="col-3">
                    @if (config('settings.site_popup') != null)
                        <img src="{{ asset('storage/'.config('settings.site_popup')) }}" id="popupImg" style="width: 80px; height: auto;">
                    @else
                        <img src="https://via.placeholder.com/80x80?text=Placeholder+Image" id="popupImg" style="width: 80px; height: auto;">
                    @endif
                </div>
                <div class="col-9">
                    <div class="row">
                    <div calss="col-9">
                    <div class="form-group">
                        <label class="control-label">Site Popup</label>
                        <input class="form-control" type="file" name="site_popup" onchange="loadFile(event,'popupImg')"/>
                        
                    </div>
                    </div>
                    <div class="col-3">
                     <div class="form-group">
                        <label class="control-label">Enable</label>
                        <select name="site_popup_enable" id="site_popup_enable" class="form-control">
                            <option value="1" {{ (config('settings.site_popup_enable')) == 1 ? 'selected' : '' }}>Enabled</option>
                            <option value="0" {{ (config('settings.site_popup_enable')) == 0 ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="row mt-4">
                <div class="col-3">
                    @if (config('settings.watermark_image') != null)
                        <img src="{{ asset('storage/'.config('settings.watermark_image')) }}" id="watermarkImg" style="width: 80px; height: auto;">
                    @else
                        <img src="https://via.placeholder.com/80x80?text=Placeholder+Image" id="watermarkImg" style="width: 80px; height: auto;">
                    @endif
                </div>
                <div class="col-9">
                    <div class="form-group">
                        <label class="control-label">Water Mark Image</label>
                        <input class="form-control" type="file" name="watermark_image" onchange="loadFile(event,'watermarkImg')"/>
                    </div>
                </div>
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
@push('scripts')
    <script>
        loadFile = function(event, id) {
            var output = document.getElementById(id);
            output.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
@endpush
