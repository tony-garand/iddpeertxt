<br/>
<form id="frm" class="form-horizontal" method="post" action="/contacts/save">
	{{ csrf_field() }}

	@if (Auth::user()->hasRole(['administrator']))
	<div class="form-group half-width">
		<label for="company_id" class=" control-label">Company:</label>
		<div>
			<select class="form-control" id="company_id" name="company_id">
				@foreach ($companies as $company)
					<option value="{{ $company->id }}">{{ $company->company_name }}</option>
				@endforeach
			</select>
		</div>
	</div>
	@endif

	<div class="form-group half-width">
		<label for="status" class=" control-label">Status:</label>
		<div>
			<select class="form-control" id="status" name="status">
				<option value="1">Active</option>
				<option value="0">Inactive</option>
			</select>
		</div>
	</div>

	<div class="form-group half-width">
		<label for="first_name" class=" control-label">First Name:</label>
		<div>
			<input type="text" class="form-control" id="first_name" name="first_name" placeholder="" />
		</div>
	</div>

	<div class="form-group half-width">
		<label for="last_name" class=" control-label">Last Name:</label>
		<div>
			<input type="text" class="form-control" id="last_name" name="last_name" placeholder="" />
		</div>
	</div>

	<div class="form-group half-width">
		<label for="phone" class=" control-label">Phone:</label>
		<div>
			<input type="text" class="form-control" id="phone" name="phone" placeholder="" />
		</div>
	</div>

	<div class="form-group half-width">
		<label for="email" class=" control-label">Email:</label>
		<div>
			<input type="email" class="form-control" id="email" name="email" placeholder="" />
		</div>
	</div>

	<div class="form-group">
		<label for="address1" class=" control-label">Address 1:</label>
		<div>
			<input type="text" class="form-control" id="address1" name="address1" placeholder="" />
		</div>
	</div>

	<div class="form-group">
		<label for="address2" class=" control-label">Address 2:</label>
		<div>
			<input type="text" class="form-control" id="address2" name="address2" placeholder="" />
		</div>
	</div>

	<div class="form-group half-width">
		<label for="city" class=" control-label">City:</label>
		<div>
			<input type="text" class="form-control" id="city" name="city" placeholder="" />
		</div>
	</div>

	<div class="form-group half-width">
		<label for="state" class=" control-label">State:</label>
		<div>
			<select class="form-control" id="state" name="state">
				<option value=""></option>
				@foreach ($states as $k=>$v)
					<option value="{{ $k }}">{{ $k }} - {{ $v }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group half-width">
		<label for="zip" class=" control-label">Zip:</label>
		<div>
			<input type="text" class="form-control" id="zip" name="zip" placeholder="" />
		</div>
	</div>

	<div class="form-group half-width">
		<label for="url" class=" control-label">URL:</label>
		<div>
			<input type="text" class="form-control" id="url" name="url" placeholder="http://" />
		</div>
	</div>

	<div class="form-group">
		<label for="tags" class=" control-label">Tags:</label>
		<div>
			<input type="text" class="form-control" id="tags" name="tags" placeholder=""/>
		</div>
	</div>

	<div class="form-group">
		<div>
			<button id="add_btn" type="submit" class="btn btn-primary">Create</button>
		</div>
	</div>

</form>

@push('scripts')
	<script>
		$(document).ready(function() {
			$('input[name="tags"]').amsifySuggestags();
		});
	</script>
@endpush