<br/>
<form id="frm" class="form-horizontal" method="post" action="/users_profile_update">
	{{ csrf_field() }}

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
		<label for="password" class="col-sm-3 control-label">Password:</label>
		<div class="col-sm-8">
			<input type="password" class="form-control" id="password" name="password" placeholder="" />
			<br/><label class="note"><b>Note:</b> Only enter password if you want to update the users password.</label>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="update_btn" type="submit" class="btn btn-primary">Update</button>
		</div>
	</div>
</form>