@extends('layouts.app')

@section('title', ' - Custom Replies')

@push('scripts')
	<script src="{{ asset('js/pages/custom-replies.js') }}"></script>
@endpush

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1>Custom Replies</h1>
					</div>
					<div class="pull-right action">
						<a href="javascript:;" class="btn btn-primary small_btn" data-toggle="modal" data-target="#addReply">Add New
							Reply</a>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<table class="table table-striped" id="customReplies">
							<thead>
							<tr>
								<th>ID</th>
								@if (Auth::user()->hasRole(['administrator']))
									<th>Company</th>
								@endif
								<th>Name</th>
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
												ajax: '{!! route('customReplies.index.table') !!}',

												columns: [
													{data: 'id', name: 'id'},
														@if (Auth::user()->hasRole(['administrator']))
													{
														data: 'company.company_name', name: 'Company.company_name'
													},
														@endif
													{
														data: 'reply_name', name: 'reply_name'
													},
													{
														data: 'updated_at', render: function (data) {
															return moment(data).format('MM-DD-YYYY');
														}, name: 'updated_at'
													}
												],

												order: [[0, 'desc']],   // double brackets so default sort is displayed correctly

												oLanguage: {
													'sSearch': 'Filter:'
												},
												dom: '<"top"lf>rti<"bottom"p><"clear">'
											});

											$('#customReplies').dataTable(options);
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

	<div class="modal fade" id="addReply" tabindex="-1" role="dialog" aria-labelledby="addReplyLabel">
		<div class="modal-dialog modal_med" role="document">
			<div class="modal-content">
				<div class="modal-body">
					@include('shared.forms.add_reply')
				</div>
			</div>
		</div>
	</div>
@endsection