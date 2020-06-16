@extends('layouts.app')

@section('title', ' - Campaigns - Run - ' . $campaign->campaign_name)

@section('content')

	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1><a href="/campaigns">Campaigns</a> &gt; Run &gt; {{ $campaign->campaign_name }}</h1>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<div class="progress">
							@if ($campaign->campaign_status == 50)
								<div class="progress-bar progress-bar-striped" role="progressbar" style="width:{{ round(($campaign->rollup_completed * 100) / $campaign->rollup_total) }}%">
								</div>
							@else
								<div class="progress-bar progress-bar-striped active" role="progressbar" style="width:{{ round(($campaign->rollup_completed * 100) / $campaign->rollup_total) }}%">
								</div>
							@endif
						</div>

						<div class="row">
							<div class="col-sm-4">
								<div class="panel panel-primary">
									<div class="panel-heading">Total Records for Campaign</div>
									<div class="panel-body"><div class="total_records">{{ $campaign->rollup_total }}</div></div>
								</div>
							</div>

							<div class="col-sm-4">
								<div class="panel panel-primary">
									<div class="panel-heading">Completed Records for Campaign</div>
									<div class="panel-body"><div class="completed_records">{{ $campaign->rollup_completed }}</div></div>
								</div>
							</div>

							<div class="col-sm-4">
								<div class="panel panel-primary">
									<div class="panel-heading">Campaign Completion Percentage</div>
									<div class="panel-body"><div class="complete_percent">{{ round(($campaign->rollup_completed * 100) / $campaign->rollup_total) }}%</div></div>
								</div>
							</div>
						</div>

					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						@if ($campaign->campaign_status == 50)
							<h4>This campaign is completed!</h4>
						@else
							<div class="campaign_msg" style="display:none;">
							</div>

							<div class="intro">
								<b>Notice:</b> By clicking the button below, you will begin a peer to peer SMS session. You are responsible for manually clicking the button to send the text as stated to the recipient listed.
								<br/>
								<input type="button" class="btn begin_p2p_btn" data-uuid="{{ $campaign->uuid }}" data-value="Begin Peer to Peer for '{{ $campaign->campaign_name }}'" value="Begin Peer to Peer for '{{ $campaign->campaign_name }}'" />
							</div>

							<div class="p2p_action" style="display:none;">
								<div class="to">To: <span class="to_phone"></span> ( <span class="to_name"></span> )</div>
								<div class="text_message"></div>
								<div class="uuid"></div>
								<div class="send_it">
									<center>
										<input type="button" class="btn send_txt_btn" data-campaign_uuid="" data-uuid="" data-value="Send This Message" value="Send This Message" />
										<input type="button" class="btn cancel_hold_btn" data-campaign_uuid="" data-uuid="" value="Cancel" />
									</center>
								</div>
							</div>
						@endif
					</div>
				</div>

			</div>
		</div>
	</div>

@endsection

