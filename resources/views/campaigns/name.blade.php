@if (($campaign_status == 20 || $campaign_status == 50) && Auth::user()->can('reply'))
    <a href="/campaigns/inbox/{{ $id }}">{{ $campaign_name }}</a>
@elseif ($campaign_status == 20)
    <a href="/campaigns/run/{{ $id }}">{{ $campaign_name }}</a>
@elseif ($campaign_status == 50)
    <a href="/campaigns/run/{{ $id }}">{{ $campaign_name }}</a>
@else
    {{ $campaign_name }}
@endif