<br/>
<form id="frm" class="form-horizontal" method="post" action="/tools/sms_conversations/save">
{{ csrf_field() }}

	<div class="form-group">
		<label for="client_id" class="col-sm-3 control-label">Client:</label>
		<div class="col-sm-8">
			<select class="form-control ajax_client_id_convo" id="client_id" name="client_id">
				<option value=""></option>
				@foreach($clients as $client)
					<option value="{{ $client->id }}">{{ $client->name }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="trigger" class="col-sm-3 control-label">Trigger Word:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="trigger" name="trigger" placeholder="" />
		</div>
	</div>

	<div class="form-group">
		<label for="trigger" class="col-sm-3 control-label">Welcome Message:</label>
		<div class="col-sm-8">
			<textarea class="form-control" rows="10" id="welcome" name="welcome"></textarea>
		</div>
	</div>

	<div class="form-group">
		<label for="messaging_services" class="col-sm-3 control-label">Message Service:</label>
		<div class="col-sm-8">
			<select required class="form-control" size="10" multiple id="messaging_services" name="messaging_services[]">
				<option value="-1">--- All Services ---</option>
				{{--@foreach ($messaging_services as $messaging_service)--}}
					{{--<option value="{{ $messaging_service->id }}">{{ $messaging_service->name }} ({{ $messaging_service->sid }})</option>--}}
				{{--@endforeach--}}
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="add_btn" type="submit" class="btn btn-primary">Create</button>
			<button data-dismiss="modal" id="cancel_btn" type="button" class="btn btn-cancel">Cancel</button>
		</div>
	</div>
</form>