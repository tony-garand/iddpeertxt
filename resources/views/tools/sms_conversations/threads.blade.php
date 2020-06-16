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
						<h1><a href="/tools/sms_conversations">SMS Conversations</a> &gt; {{ $client->name }} ({{ $sms_convo->trigger }}) &gt; Threads</h1>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<div class="loadin"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></div>
						<table style="display:none;" class="datatable table table-striped">
							<thead>
								<tr>
									<th class="sort_by" data-sort_order="desc">ID</th>
									<th>Message ID</th>
									<th>From</th>
									<th>Created</th>
								</tr>
							</thead>

							<tbody>
								@foreach ($threads as $thread)
									<tr>
										<td><a href="/tools/sms_conversation_thread_view/{{ $thread->id }}">{{ $thread->id }}</a></td>
										<td>{{ $thread->sms_message_sid }}</td>
										<td>{{ $thread->from }}</td>
										<td>{{ $thread->created_at }}</td>
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

