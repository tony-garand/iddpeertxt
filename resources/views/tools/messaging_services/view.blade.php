@extends('layouts.app')

@section('title', ' - Messaging Services - View')

@section('hero')
	<div class="full_width hro">
		<h1><a href="/tools/messaging_services">Messaging Services</a> / {{ $messaging_service->name }}</h1>
	</div>
@endsection

@section('content')

	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="panel panel-default">
					<div class="panel-body">
						@include('shared.forms.edit_messaging_service')
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<dl class="row">
							<dt class="col-sm-2">Service ID</dt>
							<dd class="col-sm-10">{{ $messaging_service->id }}</dd>
							<dt class="col-sm-2">Queue SID</dt>
							<dd class="col-sm-10">{{ $messaging_service->sid }}</dd>
							<dt class="col-sm-2">Friendly Name</dt>
							<dd class="col-sm-10">{{ $messaging_service->name }}</dd>
							<dt class="col-sm-2">Created</dt>
							<dd class="col-sm-10">{{ $messaging_service->created_at }}</dd>

							<dt class="col-sm-2"></dt>
							<dd class="col-sm-10"><br/></dd>

							<dt class="col-sm-2">Phone Numbers</dt>
							<dd class="col-sm-10">
							@foreach ($messaging_service_numbers as $messaging_service_number)
								{{ $messaging_service_number->number }} <br/>
							@endforeach

							@if (count($messaging_service_numbers) < 3)
							<form id="frm" class="form-horizontal" method="post" action="/tools/messaging_services/add_number">
							{{ csrf_field() }}
							<input type="hidden" id="messaging_service_id" name="messaging_service_id" value="{{ $messaging_service->id }}" />
							<div class="add_num">add number in <input type="text" size="4" maxlength="3" name="area_code" id="area_code" /> area code &nbsp; <input type="submit" class="btn btn-primary" value="GO" /></div>
							</form>
							@endif
							</dd>
						</dl>

					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
