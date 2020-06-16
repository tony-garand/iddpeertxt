<br/>
<form id="frm" class="form-horizontal" method="post" action="/tools/sms_conversations/save_script">
<input type="hidden" name="sms_convo_id" value="{{ $sms_convo_id }}" />
{{ csrf_field() }}

	<div class="form-group">
		<label for="script_body" class="col-sm-3 control-label">Body:</label>
		<div class="col-sm-8">
			<textarea rows="10" class="form-control" id="script_body" name="script_body"></textarea>
		</div>
	</div>

	{{--<div class="form-group">--}}
		{{--<label for="script_body" class="col-sm-3 control-label">Magic Tags:</label>--}}
		{{--<div class="col-sm-8" style="padding-top: 7px;">--}}
			{{--<span class="script_tag" data-tag="[[LocationName]]">[[LocationName]]</span>--}}
			{{--<span class="script_tag" data-tag="[[SubscriberName]]">[[SubscriberName]]</span>--}}
		{{--</div>--}}
	{{--</div>--}}

	<div class="form-group">
		<label for="data_destination" class="col-sm-3 control-label">Data Destination:</label>
		<div class="col-sm-8">
			<input type="text" class="clean form-control" id="data_destination" name="data_destination" placeholder="" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="add_btn" type="submit" class="btn btn-primary">Create</button>
			<button data-dismiss="modal" id="cancel_btn" type="button" class="btn btn-cancel">Cancel</button>
		</div>
	</div>
</form>