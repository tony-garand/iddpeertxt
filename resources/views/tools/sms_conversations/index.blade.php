@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1>SMS Conversations</h1>
					</div>
					<div class="pull-right action">
						<a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#addConvo">Add Conversation</a>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<div class="loadin"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></div>
						<table style="display:none;" class="datatable table table-striped">
							<thead>
								<tr>
									<th>Convo ID</th>
									<th class="sort_by" data-sort_order="asc">Client</th>
									<th>Trigger Name</th>
									<th>Threads</th>
									<th>Client Users</th>
									<th>Modified</th>
								</tr>
							</thead>

							<tbody>
								@foreach ($sms_conversations as $sms_conversation)
									<tr>
										<td><a href="/tools/sms_conversations/view/{{ $sms_conversation->id }}">{{ $sms_conversation->id }}</a></td>
										<td>{{ $sms_conversation->client_name }}</td>
										<td>{{ $sms_conversation->trigger }}</td>
										<td><a href="/tools/sms_conversation_threads/{{ $sms_conversation->id }}">{{ $sms_conversation->count_threads }}</a></td>
										<td><a href="/tools/sms_conversation_users/{{ $sms_conversation->id }}">{{ $sms_conversation->count_client_users }}</a></td>
										<td>{{ $sms_conversation->updated_at }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addConvo" tabindex="-1" role="dialog" aria-labelledby="addConvoLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">
					@include('shared.forms.add_sms_convo')
				</div>
			</div>
		</div>
	</div>

@endsection

