@extends('layouts.app')

@section('hero')
	<div class="full_width hro">
		<h1>SMS Conversations</h1>
	</div>
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1><a href="/tools/sms_conversations">SMS Conversations</a> &gt; {{ $client->name }} &gt; Users</h1>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<div class="loadin"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></div>
						<table style="display:none;" class="datatable table table-striped">
							<thead>
								<tr>
									<th>ID</th>
									<th class="sort_by" data-sort_order="asc">Phone</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Email</th>
									<th>Status</th>
									<th>Created</th>
								</tr>
							</thead>

							<tbody>
								@foreach ($users as $user)
									<tr>
										<td>{{ $user->id }}</td>
										<td>{{ $user->from }}</td>
										<td>{{ $user->first_name }}</td>
										<td>{{ $user->last_name }}</td>
										<td>{{ $user->email }}</td>
										<td>{{ sms_status($user->status) }}</td>
										<td>{{ $user->created_at }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>

@endsection

