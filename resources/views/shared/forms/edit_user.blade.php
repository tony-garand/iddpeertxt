<br/>
<form id="frm" class="form-horizontal" method="post" action="/users/update/{{ $user->id }}">
	{{ csrf_field() }}

	@if (Auth::user()->hasRole(['administrator']))
	<div class="form-group">
		<label for="role_id" class="col-sm-3 control-label">Company:</label>
		<div class="col-sm-8">
			<select class="form-control" id="company_id" name="company_id">
				@foreach ($companies as $company)
					<option {{ (($company->id == $user->company_id) ? " selected " : "") }} value="{{ $company->id }}">{{ $company->parent_company ? $company->parent_company->company_name.' -> ' : ''}} {{ $company->company_name }}</option>
				@endforeach
			</select>
		</div>
	</div>
	@endif

	<div class="form-group">
		<label for="status" class="col-sm-3 control-label">Status:</label>
		<div class="col-sm-8">
			<select class="form-control" id="status" name="status">
				<option {{ (($user->status == 1) ? " selected " : "") }} value="1">Active</option>
				<option {{ (($user->status == 0) ? " selected " : "") }} value="0">Inactive</option>
				<option {{ (($user->status == -1) ? " selected " : "") }} value="-1">Verify</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="name" class="col-sm-3 control-label">Full Name:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" />
		</div>
	</div>

	<div class="form-group">
		<label for="email" class="col-sm-3 control-label">Email:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="email" name="email" value="{{ $user->email }}" />
		</div>
	</div>

	<div class="form-group">
		<label for="role_id" class="col-sm-3 control-label">Role:</label>
		<div class="col-sm-8">
			<select class="form-control" id="role_id" name="role_id">
				@foreach ($roles as $role)
					<option {{ (($role == $userRole) ? " selected " : "") }} value="{{ $role }}">{{ $role }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="password" class="col-sm-3 control-label">Password:</label>
		<div class="col-sm-8">
			<input type="password" class="form-control" id="password" name="password" placeholder="" />
			<br/><label class="note"><b>Note:</b> Only enter password if you want to update the users password.</label>
		</div>
	</div>

	<div class="form-group">
		<label for="clickReply" class="col-sm-3 control-label">Click/Reply</label>
		<div class="col-sm-8">
			<select class="form-control" id="clickReply" name="clickReply">
				<option value=""></option>
				<option {{ (($user->can('click')) ? " selected " : "") }} value="click">Click</option>
				<option {{ (($user->can('reply')) ? " selected " : "") }} value="reply">Reply</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="update_btn" type="submit" class="btn btn-primary">Update</button>
			@if (Auth::user()->hasRole(['administrator']))
				@if ($user->deleted_at)
					<button data-href="/users/undelete/{{ $user->id }}" id="delete_btn" type="button" class="btn btn-primary btn-delete">Undelete User</button>
				@else
					@if ($user->id > "1")
						<button data-href="/users/delete/{{ $user->id }}" id="delete_btn" type="button" class="btn btn-primary btn-delete">Delete User</button>
					@endif
				@endif
			@else
				@if ($user->id > "1")
					<button data-href="/users/delete/{{ $user->id }}" id="delete_btn" type="button" class="btn btn-primary btn-delete">Delete User</button>
				@endif
			@endif
		</div>
	</div>

</form>
