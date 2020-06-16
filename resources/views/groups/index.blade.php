@extends('layouts.app')

@section('title', ' - Groups')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1>Groups</h1>
					</div>
					<div class="pull-right action">
						<a href="javascript:;" class="btn btn-primary small_btn" data-toggle="modal" data-target="#addGroup">Add New Group</a>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<table class="table table-striped" id="groups">
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

							@push ('scripts')
								<script>
									(function () {
										$(document).ready(function () {
											let options = $.extend(true, {}, $.fn.dataTable.defaults, {
												processing: true,
												serverSide: true,
												responsive: true,
												pageLength: 10,   // always only show 10 records at a time
												ajax: '{!! route('groups.index.table') !!}',

												columns: [
													{data: 'id', name: 'id'},
													@if (Auth::user()->hasRole(['administrator']))
													{data: 'company.company_name', name: 'Company.company_name'},
													@endif
													{data: 'name', name: 'name'},
													{data: 'status', name: 'status'},
													{data: 'created_at' , render:function(data){
													return moment(data).format('MM-DD-YYYY'); 
													}, name: 'created_at'},
													{data: 'updated_at', render:function(data){
													return moment(data).format('MM-DD-YYYY'); 
													}, name: 'updated_at'}
												],

												order: [[2, 'desc']],   // double brackets so default sort is displayed correctly

												oLanguage: {
													'sSearch': 'Filter:'
												},
												dom: '<"top"lf>rti<"bottom"p><"clear">'
											});

											$('#groups').dataTable(options);
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

	<div class="modal fade" id="addGroup" tabindex="-1" role="dialog" aria-labelledby="addGroupLabel">
		<div class="modal-dialog modal_med" role="document">
			<div class="modal-content">
				<div class="modal-body">
					@include('shared.forms.add_group')
				</div>
			</div>
		</div>
	</div>

@endsection

