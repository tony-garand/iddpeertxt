@extends('layouts.app')

@section('title', ' - Users')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1>Users</h1>
					</div>
					<div class="pull-right action">
						<a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#addUser">Add New User</a>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<table class="table table-striped" id="users">
							<thead>
								<tr>
									<th>#</th>
									<th class="sort_by" data-sort_order="asc">Name</th>
									@if (Auth::user()->hasRole(['administrator']))
									<th>Company</th>
									@endif
									<th>Email</th>
									<th>Role</th>
									<th>Status</th>
									<th>Created</th>
									<th>Modified</th>
								</tr>
							</thead>

							@push ('scripts')
								<script>
									(function () {
										$(document).ready(function () {
											let options = $.extend(true, {}, $.fn.dataTable.defaults, {
												processing: true,
												serverSide: true,
												responsive: true,
												pageLength: 10,   // always only show 10 records at a time
												ajax: '{!! route('users.index.table') !!}',

												columns: [
													{data: 'id', name: 'id'},
													{data: 'name', name: 'name'},
													@if (Auth::user()->hasRole(['administrator']))
													{data: 'company.company_name', name: 'Company.company_name'},
													@endif
													{data: 'email', name: 'email'},
													{data: 'roles', name: 'roles'},
													{data: 'status', name: 'status'},
													{data: 'created_at' , render:function(data){
													return moment(data).format('MM-DD-YYYY'); 
													}, name: 'created_at'},
													{data: 'updated_at', render:function(data){
													return moment(data).format('MM-DD-YYYY'); 
													}, name: 'updated_at'}
												],

												order: [[1, 'desc']],   // double brackets so default sort is displayed correctly

												oLanguage: {
													'sSearch': 'Filter:'
												},
												dom: '<"top"lf>rti<"bottom"p><"clear">'
											});

											$('#users').dataTable(options);
										});
									})();
								</script>
							@endpush

						</table>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="addUserLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">
					@include('shared.forms.add_user')
				</div>
			</div>
		</div>
	</div>

@endsection
