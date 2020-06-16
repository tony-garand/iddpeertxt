<br/>
<form id="frm" class="form-horizontal" method="post" action="/tools/roles/save">
{{ csrf_field() }}

	<div class="form-group">
		<label for="name" class="col-sm-3 control-label">Role Name:</label>
		<div class="col-sm-8">
			<input type="text" class="clean form-control" id="name" name="name" placeholder="MyRole" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="add_btn" type="submit" class="btn btn-primary">Create</button>
			<button data-dismiss="modal" id="cancel_btn" type="button" class="btn btn-cancel">Cancel</button>
		</div>
	</div>
</form>
