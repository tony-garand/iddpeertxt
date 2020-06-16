<br/>
<form id="frm" class="form-horizontal" method="post" action="/campaigns/numbers/add">
{{ csrf_field() }}
	<input type="hidden" name="campaign_id" id="campaign_id" value="{{ $campaign->id }}" />

	<div class="form-group">
		<label for="add_nums" class="col-sm-3 control-label">Add SMS Numbers:</label>
		<div class="col-sm-8">
			<select class="form-control" id="add_nums" name="add_nums">
				@for ($i = 1; $i <= (10 - ($campaign->MessagingService ? $campaign->MessagingService->Numbers->count() : 0)); $i++)
					<option value="{{ $i }}">{{ $i }}</option>
				@endfor
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="add_btn" type="submit" class="btn btn-primary">Add Numbers</button>
			<button data-dismiss="modal" id="cancel_btn" type="button" class="btn btn-cancel">Cancel</button>
		</div>
	</div>
</form>
