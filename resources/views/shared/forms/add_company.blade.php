<br/>
<form id="frm" class="form-horizontal" method="post" action="/tools/companies/save">
	{{ csrf_field() }}

	<div class="form-group">
		<label for="status" class="col-sm-3 control-label">Status:</label>
		<div class="col-sm-8">
			<select class="form-control" id="status" name="status">
				<option value="1">Active</option>
				<option value="0">Inactive</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="company_name" class="col-sm-3 control-label">Company Name:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="company_name" name="company_name" placeholder="" />
		</div>
	</div>

	<div class="form-group">
		<label for="status" class="col-sm-3 control-label">Parent Company:</label>
		<div class="col-sm-8">
			<select class="form-control" id="parent_company_id" name="parent_company_id">
				<option value="">No parent company</option>
				@foreach($parentCompanies as $parentCompany)
					<option {{ $company->parent_company_id == $parentCompany->id ? " selected " : "" }} value="{{$parentCompany->id}}">{{ $parentCompany->company_name }}</option>
				@endforeach
			</select>
		</div>
	</div>


	<div class="form-group">
		<label for="default_zipcode" class="col-sm-3 control-label">Default Zip Code:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="default_zipcode" name="default_zipcode" maxlength="5" />
		</div>
	</div>

	<div class="form-group">
		<label for="default_nearphone" class="col-sm-3 control-label">Default Near Phone:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="default_nearphone" name="default_nearphone" placeholder="(123) 456-7890" maxlength="15" />
		</div>
	</div>

	<div class="form-group">
		<label for="default_areacode" class="col-sm-3 control-label">Default Area Code:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="default_areacode" name="default_areacode" maxlength="3" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="add_btn" type="submit" class="btn btn-primary">Create</button>
		</div>
	</div>
</form>
