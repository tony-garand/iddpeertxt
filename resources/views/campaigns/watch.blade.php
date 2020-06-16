@extends('layouts.app')

@section('title', ' - Campaigns - Watch - ' . $campaign->campaign_name)

@section('content')

	<div class="container callback_watch_campaign" data-id="{{ $campaign->id }}">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1><a href="/campaigns">Campaigns</a> &gt; Watch &gt; {{ $campaign->campaign_name }}</h1>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<div class="progress">
							<div class="progress-bar progress-bar-striped active" role="progressbar" style="width:{{ round(($campaign->rollup_completed * 100) / $campaign->rollup_total) }}%">
							</div>
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
			</div>
		</div>
	</div>

@endsection
