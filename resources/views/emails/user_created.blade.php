@component('mail::message')
# An account has been created

An account has been created for you on PeerTxt for {{$user->Company->company_name}}

To verify and activate your account, click below

@component('mail::button', ['url' => route('user.verify', $user->uuid)])
Activate your account
@endcomponent

Thanks,<br>
PeerTxt
@endcomponent
