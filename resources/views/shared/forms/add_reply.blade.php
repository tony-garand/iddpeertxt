<br/>
<form id="frm" class="form-horizontal" method="post" action="/customReplies/save">
	{{ csrf_field() }}

	@if (Auth::user()->hasRole(['administrator']))
	<div class="form-group">
		<label for="company_id" class="col-sm-3 control-label">Company:</label>
		<div class="col-sm-8">
			<select class="form-control" id="company_id" name="company_id">
				@foreach ($companies as $company)
					<option value="{{ $company->id }}">{{ $company->company_name }}</option>
				@endforeach
			</select>
		</div>
	</div>
	@endif

	<div class="form-group">
		<div class="form-group">
			<label for="reply_name" class="col-sm-3 control-label">Name:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="reply_name" name="reply_name" placeholder="" required/>
			</div>
		</div>
		<div class="form-group">
			<label for="reply_body" class="col-sm-3 control-label">Body:</label>
			<div class="col-sm-8">
				<textarea class="form-control" rows="3" id="reply_body" name="reply_body" required></textarea>
				<div class="row" style="margin-top: 5px;">
					<div class="col-md-4">
						<select class="form-control" id="fields" name="fields">
							@foreach (customReplyFields() as $field=>$label)
								<option value="{{ $field }}">{{ $label }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-8">
						<button id="add_field" type="button" class="btn btn-primary">Add Field</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="add_btn" type="submit" class="btn btn-primary">Create</button>
		</div>
	</div>

</form>
