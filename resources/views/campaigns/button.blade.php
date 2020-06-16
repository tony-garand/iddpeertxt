@if (Auth::user()->hasRole(['administrator','manager']))
	@if ($campaign_status == 1)
		<input type="button" class="btn begin_campaign_btn" data-id="{{ $id }}" value="Begin Campaign" />
	@endif
	@if ($campaign_status == 10)
		@if ($campaign_type == 2)
			<input type="button" class="btn sendlive_campaign_btn" data-id="{{ $id }}" value="Send Campaign Now" />
		@else
			<input type="button" class="btn golive_campaign_btn" data-id="{{ $id }}" value="Go Live Campaign" />
		@endif
	@endif

	@if ($campaign_status > 10)
		@if ($campaign_status >= 20)
			<a href="/campaigns/watch/{{ $id }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
			&nbsp;&nbsp;
		@endif

		<a href="/campaigns/inbox/{{ $id }}"><i class="fa fa-inbox" aria-hidden="true"></i></a>
		&nbsp;&nbsp;
		<a class="confirm" href="{{ route('campaigns.archive', [$id]) }}" data-msg="Are you sure you want to archive this campaign?"><i class="fa fa-archive" aria-hidden="true"></i></a>
	@endif

@endif
