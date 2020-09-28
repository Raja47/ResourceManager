<div class="tile">
    <form action="{{ route('admin.settings.update') }}" method="POST" role="form">
        @csrf
        <h3 class="tile-title">Newyork Tax</h3>
        <hr>
        <div class="tile-body">
           
            <div class="form-group pt-2">
                <label class="control-label" for="newyork_tax_enable">Newyork Tax</label>
                <select name="newyork_tax_enable" id="newyork_tax_enable" class="form-control">
                    <option value="1" {{ (config('settings.newyork_tax_enable')) == '1' ? 'selected' : '' }}>Enabled</option>
                    <option value="0" {{ (config('settings.newyork_tax_enable')) == '0' ? 'selected' : '' }}>Disabled</option>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label" for="newyork_tax_value">Tax(%)</label>
                <input
                    class="form-control"
                    type="number"
                    placeholder="Enter New York Tax Percentage"
                    id="newyork_tax_value"
                    name="newyork_tax_value"
                    value="{{ config('settings.newyork_tax_value') }}"
                />
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
