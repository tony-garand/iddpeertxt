<br/>
<form id="frm" class="form-horizontal" method="post" action="/tools/sms_conversations/update/{{ $sms_convo->id }}">
{{ csrf_field() }}

	<div class="form-group">
		<label for="client_id" class="col-sm-3 control-label">Client:</label>
		<div class="col-sm-8">
			<select class="form-control ajax_client_id_convo" id="client_id" name="client_id">
				<option value=""></option>
				@foreach($clients as $client)
					<option {{ (($sms_convo->client_id == $client->id) ? " selected " : "") }} value="{{ $client->id }}">{{ $client->name }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="trigger" class="col-sm-3 control-label">Trigger Word:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="trigger" name="trigger" value="{{ $sms_convo->trigger }}" />
		</div>
	</div>

	<div class="form-group">
		<label for="trigger" class="col-sm-3 control-label">Welcome Message:</label>
		<div class="col-sm-8">
			<textarea class="form-control" rows="10" id="welcome" name="welcome">{{ $sms_convo->welcome }}</textarea>
		</div>
	</div>

	<div class="form-group">
		<label for="messaging_services" class="col-sm-3 control-label">Locations:</label>
		<div class="col-sm-8">
			<select required class="form-control" size="10" multiple id="messaging_services" name="messaging_services[]">
				<option {{ (($sms_convo->all_locations == 1) ? " selected " : "") }} value="-1">--- All Locations ---</option>
				@foreach ($messaging_services as $messaging_service)
					<option {{ ((in_array($messaging_service->id, $selected_ms)) ? " selected " : "") }} value="{{ $messaging_service->id }}">{{ $messaging_service->name }} ({{ $messaging_service->sid }})</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="add_btn" type="submit" class="btn btn-primary">Update</button>
			<button data-href="/tools/sms_conversations/delete/{{ $sms_convo->id }}" id="delete_btn" type="button" class="btn btn-delete">Delete Conversation</button>
		</div>
	</div>
</form>