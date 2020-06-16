<br/>
<form id="frm" class="form-horizontal" method="post" action="/tools/companies/update/{{ $company->id }}">
    {{ csrf_field() }}

    <div class="form-group">
        <label for="status" class="col-sm-3 control-label">Status:</label>
        <div class="col-sm-8">
            <select class="form-control" id="status" name="status">
                <option {{ (($company->status == 1) ? " selected " : "") }} value="1">Active</option>
                <option {{ (($company->status == 0) ? " selected " : "") }} value="0">Inactive</option>
            </select>
        </div>
    </div>
    @if(!isset($isParent) || isset($isParent) && !$isParent)
        <div class="form-group">
            <label for="status" class="col-sm-3 control-label">Parent Company:</label>
            <div class="col-sm-8">
                <select class="form-control" id="parent_company_id" name="parent_company_id">
                    <option value="">No parent company</option>
                    @foreach($companies as $parentCompany)
                        <option {{ $company->parent_company_id == $parentCompany->id ? " selected " : "" }} value="{{$parentCompany->id}}">{{ $parentCompany->company_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <div class="form-group">
        <label for="company_name" class="col-sm-3 control-label">Company Name:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="company_name" name="company_name"
                   value="{{ $company->company_name }}"/>
        </div>
    </div>

    <div class="form-group">
        <label for="default_zipcode" class="col-sm-3 control-label">Default Zip Code:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="default_zipcode" name="default_zipcode"
                   value="{{ $company->default_zipcode }}" maxlength="5"/>
        </div>
    </div>

    <div class="form-group">
        <label for="default_nearphone" class="col-sm-3 control-label">Default Near Phone:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="default_nearphone" name="default_nearphone"
                   value="{{ $company->default_nearphone }}" maxlength="15"/>
        </div>
    </div>

    <div class="form-group">
        <label for="default_areacode" class="col-sm-3 control-label">Default Area Code:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="default_areacode" name="default_areacode"
                   value="{{ $company->default_areacode }}" maxlength="3"/>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button id="add_btn" type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>
