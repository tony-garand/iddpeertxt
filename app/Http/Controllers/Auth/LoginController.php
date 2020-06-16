<?php

namespace peertxt\Http\Controllers\Auth;

use peertxt\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use peertxt\models\Company;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = '/home';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}

	public function login(\Illuminate\Http\Request $request) {
		$this->validateLogin($request);

		// If the class is using the ThrottlesLogins trait, we can automatically throttle
		// the login attempts for this application. We'll key this by the username and
		// the IP address of the client making these requests into this application.
		if ($this->hasTooManyLoginAttempts($request)) {
			$this->fireLockoutEvent($request);
			return $this->sendLockoutResponse($request);
		}

		// This section is the only change
		if ($this->guard()->validate($this->credentials($request))) {
			$user = $this->guard()->getLastAttempted();

			// Make sure the user is active
			$company = Company::where('id', $user->company_id)->first();

			if (($company->status == 1) && ($user->status == 1) && (!$user->deleted_at) && ($this->attemptLogin($request))) {
				return $this->sendLoginResponse($request);
			} else {
				// Increment the failed login attempts and redirect back to the
				// login form with an error message.
				$this->incrementLoginAttempts($request);
				return redirect()
					->back()
					->withInput($request->only($this->username(), 'remember'))
					->withErrors(['active' => 'Your company and/or account is not active!']);
			}
		}

		// If the login attempt was unsuccessful we will increment the number of attempts
		// to login and redirect the user back to the login form. Of course, when this
		// user surpasses their maximum number of attempts they will get locked out.
		$this->incrementLoginAttempts($request);

		return $this->sendFailedLoginResponse($request);
	}

	protected function authenticated(Request $request, $user)
	{
		$this->setUserSession($user);
	}

	protected function setUserSession($user)
	{
		session(['user_brand' => 'peertxt']);
	}

}
