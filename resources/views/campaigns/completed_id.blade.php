@if (Auth::user()->hasRole(['administrator','manager']))
	<a href="/campaigns/completed/view/{{ $id }}">{{ $id }}</a>
@endif