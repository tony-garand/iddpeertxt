@extends('layouts.app')

@section('title', ' - Companies - View')

@section('hero')
	<div class="full_width hro">
		<h1><a href="/tools/companies">Companies</a> / {{ $company->company_name }}</h1>
	</div>
@endsection

@section('content')

	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="panel panel-default">
					<div class="panel-body">
						@include('shared.forms.edit_company')
					</div>
				</div>

			</div>
		</div>
	</div>

@endsection
