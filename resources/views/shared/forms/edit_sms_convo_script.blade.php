<br/>
<form id="frm" class="form-horizontal" method="post" action="/tools/sms_conversations/update_script/{{ $sms_script->id }}">
{{ csrf_field() }}

	<div class="form-group">
		<label for="script_body" class="col-sm-3 control-label">Body:</label>
		<div class="col-sm-8">
			<textarea rows="10" class="form-control" id="script_body" name="script_body">{{ $sms_script->script_body }}</textarea>
		</div>
	</div>

	<div class="form-group">
		<label for="data_destination" class="col-sm-3 control-label">Data Destination:</label>
		<div class="col-sm-8">
			<input type="text" class="clean form-control" id="data_destination" name="data_destination" value="{{ $sms_script->data_destination }}" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="add_btn" type="submit" class="btn btn-primary">Update</button>
			<button data-href="/tools/sms_conversations/delete_script/{{ $sms_script->id }}" id="delete_btn" type="button" class="btn btn-delete">Delete Script</button>
		</div>
	</div>
</form>