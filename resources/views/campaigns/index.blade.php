@extends('layouts.app')

@section('title', ' - Campaigns')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1>Campaigns</h1>
					</div>
					<div class="pull-right action">
						@if (Auth::user()->hasRole(['administrator','manager']))
						<a href="javascript:;" class="btn btn-primary small_btn" data-toggle="modal" data-target="#addCampaign">Add New Campaign</a>
						@endif
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
									<th>Status</th>
									<th>Type</th>
									<th>Created Date</th>
									<th>Modified Date</th>
									<th></th>
								</tr>
							</thead>

							@push ('scripts')
								<script>
									(function () {
										$(document).ready(function () {
                                            $("body").on("change", ".whole_day_cb", function () {

                                                if ($(this).is(":checked")) {
                                                    $(this).closest('.form-group').find('.time-holder').hide();
                                                } else {
                                                    $(this).closest('.form-group').find('.time-holder').show();
                                                }
                                            });

											let options = $.extend(true, {}, $.fn.dataTable.defaults, {
												processing: true,
												serverSide: true,
												responsive: true,
												pageLength: 10,   // always only show 10 records at a time
												ajax: '{!! route('campaigns.index.table') !!}',

												columns: [
													@if (Auth::user()->hasRole(['administrator','manager']))
													{data: 'id', name: 'id'},
													@endif
													@if (Auth::user()->hasRole(['administrator']))
													{data: 'company.company_name', name: 'Company.company_name'},
													@endif
													{data: 'campaign_name', name: 'campaign_name'},
													{data: 'campaign_status', name: 'campaign_status'},
													{data: 'campaign_type', name: 'campaign_type'},
													{data: 'created_at' , render:function(data){
													return moment(data).format('MM-DD-YYYY');
													}, name: 'created_at'},
													{data: 'updated_at', render:function(data){
													return moment(data).format('MM-DD-YYYY');
													}, name: 'updated_at'},
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

	@if (Auth::user()->hasRole(['administrator','manager']))
	<div class="modal fade" id="addCampaign" tabindex="-1" role="dialog" aria-labelledby="addCampaignLabel">
		<div class="modal-dialog modal_wide" role="document">
			<div class="modal-content">
				<div class="modal-body">
					@include('shared.forms.add_campaign')
				</div>
			</div>
		</div>
	</div>
	@endif

@endsection
