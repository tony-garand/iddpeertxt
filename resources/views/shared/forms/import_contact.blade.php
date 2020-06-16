<br/>
<form id="frm" class="form-horizontal" method="post" action="/contacts/import" enctype="multipart/form-data">
{{ csrf_field() }}

	@if (Auth::user()->hasRole(['administrator']))
	<div class="form-group">
		<label for="company_id" class="col-sm-3 control-label">Company:</label>
		<div class="col-sm-8">
			<select required class="form-control ajax_load_campaign_data" id="company_id" name="company_id">
				<option value=""></option>
				@foreach ($companies as $company)
					<option value="{{ $company->id }}">{{ $company->company_name }}</option>
				@endforeach
			</select>
		</div>
	</div>
	@endif

	<div class="form-group">
		<label for="file_upload" class="col-sm-3 control-label">File Upload:</label>
		<div class="col-sm-8">
			<input type="file" accept=".csv" class="form-control-file" name="file_upload" id="file_upload" aria-describedby="fileHelp">
			<small id="fileHelp" class="form-text text-muted">File must be a CSV file that matches the <a href="/example_upload.csv" style="text-decoration:underline;" target="_new">example format</a>.</small>
		</div>
	</div>

	<div class="form-group">
		<label for="tags" class="col-sm-3 control-label">Tags:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="tags" name="tags" />
			<small id="fileHelp" class="form-text text-muted">Comma-delimited list of tags to apply to each imported contact</small>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="add_btn" type="submit" class="btn btn-primary">Upload</button>
		</div>
	</div>
</form>
