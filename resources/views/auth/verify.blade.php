@extends('layouts.login')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">

			<br/><br/>
			<br/><br/>
			<br/><br/>

			<div class="login_form panel panel-default">

				@if (env('IS_DEV_MODE')=='1')
					<div class="warning_lbl">
						Warning: You're on the development server
					</div>
				@endif

				<div class="panel-heading">
					<center><img src="/images/peertxt_logo.png" width="150" /></center>
				</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('user.verify.tos', $user->uuid) }}">
                        {{ csrf_field() }}

						@if ($errors->has('accept_tos'))
						<div class="login_error_block">
							<strong>{{ $errors->first('accept_tos') }}</strong>
						</div>
						@endif

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="col-md-10 col-md-offset-1">
                                <input placeholder="Email" id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" disabled>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="checkbox pull-left">
                                    <label>
                                        <input type="checkbox" name="accept_tos"> I accept User Terms / User Agreement
                                    </label>
                                </div>
{{--								<a class="btn btn-link pull-right" href="{{ url('/password/reset') }}">Forgot Password?</a>--}}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1">
                                <button type="submit" class="btn btn-primary">
                                    Verify account
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

			{{--<center><img src="/images/logo.png" width="120" /></center>--}}

        </div>
    </div>
</div>
@endsection
