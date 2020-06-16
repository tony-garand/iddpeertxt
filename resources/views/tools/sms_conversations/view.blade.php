@extends('layouts.app')

@section('content')

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1><a href="/tools/sms_conversations">SMS Conversations</a> &gt; View</h1>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<div class="panel panel-default">
					<div class="panel-body">
						@include('shared.forms.edit_sms_convo')
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<div class="panel panel-default">
					<div class="panel-body">

						<div class="actions">
							<div class="action col-md-2 fr pull-right">
								<a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#addScript">Add Convo Script</a>
							</div>
						</div>

						<table class="table table-striped">
							<thead>
								<tr>
									<th>Step</th>
									<th>Body</th>
									<th></th>
								</tr>
							</thead>

							<tbody>
								@foreach ($sms_convo_scripts as $sms_convo_script)
									<tr>
										<td>{{ $sms_convo_script->step }}</td>
										<td>{{ $sms_convo_script->script_body }}</td>
										<td align="right"><a href="/tools/sms_conversations/edit_script/{{ $sms_convo_script->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> View / Edit</a></td>
									</tr>
								@endforeach
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addScript" tabindex="-1" role="dialog" aria-labelledby="addScriptLabel">
		<div class="modal-dialog modal_med" role="document">
			<div class="modal-content">
				<div class="modal-body">
					@include('shared.forms.add_sms_convo_script', ['sms_convo_id' => $sms_convo->id])
				</div>
			</div>
		</div>
	</div>

@endsection