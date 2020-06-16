@extends('layouts.app')

@section('title', ' - Users - ' . $user->name)

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1><a href="/users">Users</a> &gt; Edit User</h1>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">
						@include('shared.forms.edit_user')
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
