<br/>
<form id="frm" class="form-horizontal" method="post" action="/tools/roles/update/{{ $role->id }}">
{{ csrf_field() }}

	<div class="form-group">
		<label for="name" class="col-sm-3 control-label">Role Name:</label>
		<div class="col-sm-8">
			<input type="text" class="clean form-control" id="name" name="name" placeholder="MyRole" value="{{ $role->name }}" />
		</div>
	</div>

	<div class="form-group">
		<label for="guard_name" class="col-sm-3 control-label">Guard Name:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="guard_name" name="guard_name" placeholder="My Role" value="{{ $role->guard_name }}" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="add_btn" type="submit" class="btn btn-primary">Update</button>
		</div>
	</div>
</form>
