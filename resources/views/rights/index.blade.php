@extends('layouts.app')

@section('title', ' - Rights')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1>Rights</h1>
					</div>
					<div class="pull-right action">
						<a href="javascript:;" class="btn btn-primary small_btn" data-toggle="modal" data-target="#addRight">Add New Right</a>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<div class="loadin"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></div>
						<table style="display:none;" class="datatable table table-striped">
							<thead>
								<tr>
									<th>ID</th>
									@if (Auth::user()->hasRole(['administrator']))
									<th>Company</th>
									@endif
									<th class="sort_by" data-sort_order="asc">Name</th>
									<th>Status</th>
									<th>Created Date</th>
									<th>Modified Date</th>
								</tr>
							</thead>

							<tbody>
								@foreach ($rights as $right)
									<tr>
										<td><a href="/rights/view/{{ $right->id }}">{{ $right->id }}</a></td>
										@if (Auth::user()->hasRole(['administrator']))
											<td>{{ $right->Company->company_name }}</td>
										@endif
										<td>{{ $right->name }}</td>
										<td>{{ right_status($right->status, $right->deleted_at) }}</td>
										<td>{{ $right->created_at }}</td>
										<td>{{ $right->updated_at }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addRight" tabindex="-1" role="dialog" aria-labelledby="addRightLabel">
		<div class="modal-dialog modal_med" role="document">
			<div class="modal-content">
				<div class="modal-body">
					@include('shared.forms.add_right')
				</div>
			</div>
		</div>
	</div>

@endsection

