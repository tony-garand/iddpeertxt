@extends('layouts.app')

@section('title', ' - SMS Conversations')

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
						<a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#addConversation"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Conversation</a>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<div class="loadin"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></div>
						<table style="display:none;" class="datatable table table-striped">
							<thead>
								<tr>
									<th>#</th>
									<th class="sort_by" data-sort_order="asc">Name</th>
									<th>IP</th>
									<th>Zone</th>
									<th>Cores</th>
									<th>RAM</th>
									<th>Disk</th>
									<th>Created</th>
									<th>Status</th>
									<th></th>
									<th></th>
							</thead>
							<tbody>
								@foreach($nodes as $node)
									<tr>
										<td>{{ $node->node_id }}</td>
										<td>{{ $node->name }}</td>
										<td>{{ $node->ip }}</td>
										<td>{{ $node->location }}</td>
										<td data-order="{{ $node->vcpus }}">{{ $node->vcpus }} CPUs</td>
										<td data-order="{{ $node->memory }}">{{ $node->ram }}</td>
										<td data-order="{{ $node->disk }}">{{ $node->storage }}</td>
										<td>{{ Carbon\Carbon::parse($node->node_created)->format('m/d/Y') }}</td>
										<td>{{ $node->node_status }}</td>
										<td><a class="reboot" href="javascript:;" data-href="/nodes/reboot/{{ $node->id }}"><i class="fa fa-refresh" aria-hidden="true"></i></a></td>
										<td><a class="node_add_key" href="javascript:;" data-node_ip="{{ $node->ip }}"><i class="fa fa-upload" aria-hidden="true"></i></a></td>
									</tr>
								@endforeach
							</tbody>

						</table>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addNodeKey" tabindex="-1" role="dialog" aria-labelledby="addNodeKeyLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">
					@include('shared.forms.add_node_key')
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addNode" tabindex="-1" role="dialog" aria-labelledby="addNodeLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">
					@include('shared.forms.add_node')
				</div>
			</div>
		</div>
	</div>

@endsection