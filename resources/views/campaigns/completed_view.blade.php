@extends('layouts.app')

@section('title', ' - Campaigns')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1><a href="{{ route('campaigns.completed') }}">Completed Campaigns</a> > View</h1>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<div class="col-md-4">
							<h3>SMS Sent</h3>
							<h4>{{ number_format($campaign->sms_sent_count) }}</h4>
						</div>

						<div class="col-md-4">
							<h3>Link Clicks</h3>
							<h4>{{ number_format($campaign->link_click_count) }}</h4>
						</div>

						<div class="col-md-4">
							<h3>Duration</h3>
							<h4>{{ $campaign->updated_at->diffAsCarbonInterval($campaign->created_at)->cascade()->forHumans() }}</h4>
						</div>

					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
