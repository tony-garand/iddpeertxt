<br/>
<form id="frm" class="form-horizontal" method="post" action="/rights/update_users/{{ $right->id }}">
	{{ csrf_field() }}

	<div class="form-group">
		<label for="users" class="col-sm-3 control-label">Users:</label>
		<div class="col-sm-8">
			<select multiple class="form-control" id="users" size="10" name="users[]">
				@foreach ($users as $user)
					<option {{ ((in_array($user->id, $selected_users)) ? " selected " : "") }} value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="add_btn" type="submit" class="btn btn-primary">Update</button>
		</div>
	</div>

</form>
