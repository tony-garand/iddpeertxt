@if (count($errors) > 0)
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

@if(Session::has('status'))
	<div class="alert alert-success">
		{{Session::get('status')}}
	</div>
@endif
