@extends('layouts.app')

@section('hero')
	<div class="full_width hro">
		<h1>SMS Conversations</h1>
	</div>
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1><a href="/tools/sms_conversations">SMS Conversations</a> &gt; {{ $client->name }} ({{ $sms_convo->trigger }}) &gt; <a href="/tools/sms_conversation_threads/{{ $sms_convo->id }}">Threads</a> &gt; View</h1>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">
						<div class="chat_window">
							{{--<div class="start_timestmp">{{ $thread->created_at }}</div>--}}
							@foreach ($out as $o)
								<div class="block_question">
									{{ $o['q'] }}
								</div>
								@if (@$o['r'])
								<div class="block_answer">
									{{ @$o['r'] }}
								</div>
								<div class="answer_timestmp">{{ $o['r_ts'] }}</div>
								@endif
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection

