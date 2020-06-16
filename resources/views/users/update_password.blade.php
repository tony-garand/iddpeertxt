@extends('layouts.app')

@section('title', ' - Profile - ' . $user->name)

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1>Update password</h1>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">
						<br/>
						<form id="frm" class="form-horizontal" method="post" action="{{ route('user.update.password') }}">
							{{ csrf_field() }}

							<div class="form-group">
								<label for="password" class="col-sm-3 control-label">Password:</label>
								<div class="col-sm-8">
									<input type="password" class="form-control" id="password" name="password" placeholder="" />
									<br/><label class="note"><b>Note:</b> Only enter password if you want to update the users password.</label>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-9">
									<button id="update_btn" type="submit" class="btn btn-primary">Update</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
