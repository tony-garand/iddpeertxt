<br/>
<form id="frm" class="form-horizontal" method="post" action="/tools/messaging_services/save">
{{ csrf_field() }}

	<div class="form-group">
		<label for="client_id" class="col-sm-3 control-label">Company:</label>
		<div class="col-sm-8">
			<select class="form-control" id="company_id" name="company_id">
				<option value=""></option>
				@foreach($companies as $company)
					<option value="{{ $company->id }}">{{ $company->company_name }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="name" class="col-sm-3 control-label">Friendly Name:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="name" name="name" placeholder="" />
		</div>
	</div>

	<div class="form-group">
		<label for="add_nums" class="col-sm-3 control-label">Add SMS Numbers:</label>
		<div class="col-sm-8">
			<select class="form-control" id="add_nums" name="add_nums">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="area_code" class="col-sm-3 control-label">Area Code for Numbers:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="area_code" name="area_code" placeholder="" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="add_btn" type="submit" class="btn btn-primary">Create</button>
			<button data-dismiss="modal" id="cancel_btn" type="button" class="btn btn-cancel">Cancel</button>
		</div>
	</div>
</form>
