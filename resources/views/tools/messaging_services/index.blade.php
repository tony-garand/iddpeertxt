@extends('layouts.app')

@section('title', ' - Messaging Services')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1>Messaging Services</h1>
					</div>
					<div class="pull-right action">
						<a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#addMS">Add New Service</a>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<div class="loadin"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></div>
						<table style="display:none;" class="datatable table table-striped">
							<thead>
								<tr>
									<th>ID</th>
									<th>SID</th>
									<th>Company</th>
									<th class="sort_by" data-sort_order="asc">Name</th>
									<th>Created Date</th>
								</tr>
							</thead>

							<tbody>
								@foreach ($messaging_services as $messaging_service)
									<tr>
										<td><a href="/tools/messaging_services/view/{{ $messaging_service->id }}">{{ $messaging_service->id }}</a></td>
										<td>{{ $messaging_service->sid }}</td>
										<td>{{ $messaging_service->Company->company_name }}</td>
										<td>{{ $messaging_service->name }}</td>
										<td>{{ $messaging_service->created_at }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addMS" tabindex="-1" role="dialog" aria-labelledby="addMSLabel">
		<div class="modal-dialog modal_med" role="document">
			<div class="modal-content">
				<div class="modal-body">
					@include('shared.forms.add_messaging_service')
				</div>
			</div>
		</div>
	</div>

@endsection

