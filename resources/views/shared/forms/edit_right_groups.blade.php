<br/>
<form id="frm" class="form-horizontal" method="post" action="/rights/update_groups/{{ $right->id }}">
	{{ csrf_field() }}

	<div class="form-group">
		<label for="users" class="col-sm-3 control-label">Groups:</label>
		<div class="col-sm-8">
			<select multiple class="form-control" id="groups" size="10" name="groups[]">
				@foreach ($groups as $group)
					<option {{ ((in_array($group->id, $selected_groups)) ? " selected " : "") }} value="{{ $group->id }}">{{ $group->name }} ( # {{ $group->id }} )</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button id="add_btn" type="submit" class="btn btn-primary">Update</button>
		</div>
	</div>

</form>
