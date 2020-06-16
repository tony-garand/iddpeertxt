@extends('layouts.app')

@section('hero')
	<div class="full_width hro">
		<h1><a href="/tools/sms_conversations">SMS Conversations</a> / {{ $sms_convo->trigger }}</h1>
	</div>

@endsection

@section('content')

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				@include('shared.messages')
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<div class="panel panel-default">
					<div class="panel-body">
						@include('shared.forms.edit_sms_convo_script')
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
