@if (Auth::user()->hasRole(['administrator','manager']))
    <a href="/campaigns/view/{{ $id }}">{{ $id }}</a>
@endif