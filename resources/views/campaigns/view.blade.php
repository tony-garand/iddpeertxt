@extends('layouts.app')

@section('title', ' - Campaigns - #' . $campaign->id)

@section('content')

	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1><a href="/campaigns">Campaigns</a> &gt; View Campaign</h1>
					</div>


					<div class="pull-right action">
						@if (Auth::user()->hasRole(['administrator', 'manager']) && $campaign->campaign_status == 20)
							<a href="{{ route('campaigns.pause', ['id' => $campaign->id]) }}" class="btn btn-primary small_btn">Pause Campaign</a>
						@endif
						@if (Auth::user()->hasRole(['administrator', 'manager']) && $campaign->campaign_status == 30)
							<a href="{{ route('campaigns.resume', ['id' => $campaign->id]) }}" class="btn btn-primary small_btn">Resume Campaign</a>
						@endif
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">
						@if (Auth::user()->can('reply'))
							@include('campaigns.reply')
						@else
							@include('shared.forms.edit_campaign')
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

	@if (Auth::user()->hasRole(['administrator','manager']))
		<div class="modal fade" id="addNumbers" tabindex="-1" role="dialog" aria-labelledby="addNumbersLabel">
			<div class="modal-dialog modal_wide" role="document">
				<div class="modal-content">
					<div class="modal-body">
						@include('shared.forms.add_numbers', compact('campaign'))
					</div>
				</div>
			</div>
		</div>
	@endif

@endsection