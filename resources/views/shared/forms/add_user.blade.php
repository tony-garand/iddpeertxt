<br/>
<form id="frm" class="form-horizontal" method="post" action="/users/save">
	{{ csrf_field() }}

	@if (Auth::user()->hasRole(['administrator']))
	<div class="form-group">
		<label for="role_id" class="col-sm-3 control-label">Company:</label>
		<div class="col-sm-8">
			<select class="form-control" id="company_id" name="company_id">
				@foreach ($companies as $company)
					<option value="{{ $company->id }}">{{ $company->parent_company ? $company->parent_company->company_name.' -> ' : ''}} {{ $company->company_name }}</option>
				@endforeach
			</select>
		</div>
	</div>
	@endif

	<div class="form-group">
		<label for="status" class="col-sm-3 control-label">Status:</label>
		<div class="col-sm-8">
			<select class="form-control" id="status" name="status">
				<option value="1" disabled>Active</option>
				<option value="0" disabled>Inactive</option>
				<option value="-1" selected>Verify</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="name" class="col-sm-3 control-label">Full Name:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="name" name="name" placeholder="" />
		</div>
	</div>

	<div class="form-group">
		<label for="email" class="col-sm-3 control-label">Email:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="email" name="email" placeholder="" />
		</div>
	</div>

	<div class="form-group">
		<label for="password" class="col-sm-3 control-label">Password:</label>
		<div class="col-sm-8">
			<input type="password" class="form-control" id="password" name="password" placeholder="" />
		</div>
	</div>

	<div class="form-group">
		<label for="role_id" class="col-sm-3 control-label">Role:</label>
		<div class="col-sm-8">
			<select class="form-control" id="role_id" name="role_id">
				@foreach ($roles as $role)
					<option value="{{ $role }}">{{ $role }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="add_btn" type="submit" class="btn btn-primary">Create</button>
		</div>
	</div>

</form>
