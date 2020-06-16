@extends('layouts.app')

@section('title', ' - Roles')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1>Roles</h1>
					</div>
					<div class="pull-right action">
						<a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#addRole">Add New Role</a>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<table class="table table-striped">
							<thead>
								<tr>
									<th>Role Name</th>
									<th>Guard Name</th>
									<th></th>
								</tr>
							</thead>

							<tbody>
								@foreach ($roles as $role)
									<tr>
										<td>{{ $role->name }}</td>
										<td>{{ $role->guard_name }}</td>
										<td align="right"><a href="/tools/roles/view/{{ $role->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> View / Edit</a></td>
									</tr>
								@endforeach
							</tbody>
						</table>

						<div class="paginator">
							{{ $roles->links() }}
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addRole" tabindex="-1" role="dialog" aria-labelledby="addRoleLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">
					@include('shared.forms.add_role')
				</div>
			</div>
		</div>
	</div>

@endsection

