<br/>
<form id="frm" class="form-horizontal" method="post" action="/customLabels/save">
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
		<label for="label" class="col-sm-3 control-label">Label:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="label" name="label" placeholder="" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="add_btn" type="submit" class="btn btn-primary">Create</button>
		</div>
	</div>

</form>
