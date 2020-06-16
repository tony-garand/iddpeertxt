@extends('layouts.app')

@section('title', ' - Campaigns')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1>Completed Campaigns</h1>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<table class="table table-striped" id="campaigns">
							<thead>
							<tr>
								@if (Auth::user()->hasRole(['administrator','manager']))
									<th>ID</th>
								@endif
								@if (Auth::user()->hasRole(['administrator']))
									<th>Company</th>
								@endif
								<th>Campaign Name</th>
								<th>Type</th>
								<th>Roll-up</th>
								<th>Duration</th>
								<th></th>
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
												ajax: '{!! route('campaigns.completed.table') !!}',

												columns: [
														@if (Auth::user()->hasRole(['administrator','manager']))
													{
														data: 'id', name: 'id'
													},
														@endif
														@if (Auth::user()->hasRole(['administrator']))
													{
														data: 'company.company_name', name: 'Company.company_name'
													},
														@endif
													{
														data: 'campaign_name', name: 'campaign_name'
													},
													{data: 'campaign_type', name: 'campaign_type'},
													{data: 'rollup_completed', name: 'rollup_completed'},
													{data: 'duration', name: 'duration', sortable: false},
													{data: 'button', name: 'button', searchable: false, sortable: false}
												],

												order: [[2, 'desc']],   // double brackets so default sort is displayed correctly

												oLanguage: {
													'sSearch': 'Filter:'
												},
												dom: '<"top"lf>rti<"bottom"p><"clear">'
											});

											$('#campaigns').dataTable(options);
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

@endsection
