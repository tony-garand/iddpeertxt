@extends('layouts.app')

@section('title', ' - Roles - ' . $role->name)

@section('hero')
	<div class="full_width hro">
		<h1><a href="/tools/roles">Roles</a> / {{ $role->name }}</h1>
	</div>
@endsection

@section('content')

	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="panel panel-default">
					<div class="panel-body">
						@include('shared.forms.edit_role')
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
