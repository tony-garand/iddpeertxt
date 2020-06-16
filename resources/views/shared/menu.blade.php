<div class="collapse navbar-collapse" id="app-navbar-collapse">
	<ul class="nav navbar-nav main_nav">
		@if (!Auth::guest())
			@if (Auth::user()->hasRole(['administrator','manager']))
				<li class="dropdown @if (Request::is('contacts')){{ "active" }}@endif"><a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fa fa-address-book" aria-hidden="true"></i>    Contacts</a>
					<ul class="dropdown-menu mm-dd" role="menu">
						<li><a href="{{ url('/contacts') }}">List</a></li>
						<li><a href="{{ url('/customLabels') }}">Custom Labels</a></li>
					</ul>
				</li>
			@endif

			<li class="dropdown @if (Request::is('campaigns')){{ "active" }}@endif"><a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fa fa-book" aria-hidden="true"></i> &nbsp; Campaigns
				</a>
				<ul class="dropdown-menu mm-dd" role="menu">
					<li><a href="{{ url('/campaigns') }}">Current</a></li>
					<li><a href="{{ url('/campaigns/completed') }}">Completed</a></li>
				</ul>

			</li>

{{--			<li class="@if (Request::is('conversations')){{ "active" }}@endif"><a href="{{ url('/conversations') }}"><i class="fa fa-comments" aria-hidden="true"></i> &nbsp; Conversations</a></li>--}}

			@if (Auth::user()->hasRole(['administrator','manager']))
				<li class="@if (Request::is('reporting')){{ "active" }}@endif"><a href="{{ url('/reporting') }}"><i class="fa fa-area-chart" aria-hidden="true"></i> &nbsp; Reporting</a></li>
			@endif

			@if (Auth::user()->hasRole(['administrator','manager']))
				<li class="dropdown user_menu @if (Request::is('users','groups','rights')){{ "active" }}@endif"><a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fa fa-users" aria-hidden="true"></i> &nbsp; Access</a>
					<ul class="dropdown-menu mm-dd" role="menu">
						<li class="@if (Request::is('users')){{ "active" }}@endif"><a href="{{ url('/users') }}">Users</a></li>
						<li class="@if (Request::is('groups')){{ "active" }}@endif"><a href="{{ url('/groups') }}">Groups</a></li>
						<li class="@if (Request::is('rights')){{ "active" }}@endif"><a href="{{ url('/rights') }}">Rights</a></li>
					</ul>
				</li>
			@endif

			@if (Auth::user()->hasRole(['administrator','manager']))
				<li class="dropdown user_menu @if (Request::is('tools/*') || Request::is('customReplies*')){{ "active" }}@endif"><a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fa fa-cog" aria-hidden="true"></i> &nbsp; Tools</a>
					<ul class="dropdown-menu mm-dd" role="menu">
						<li class="@if (Request::is('tools/companies')){{ "active" }}@endif"><a href="/tools/companies">Companies</a></li>
						<li class="@if (Request::is('tools/roles')){{ "active" }}@endif"><a href="/tools/roles">Roles</a></li>
						<li class="@if (Request::is('tools/messaging_services')){{ "active" }}@endif"><a href="/tools/messaging_services">Messaging Services</a></li>
						<li class="@if (Request::is('customReplies*')){{ "active" }}@endif"><a href="{{ route('customReplies') }}">Custom Replies</a></li>
					</ul>
				</li>
			@endif
		@endif
	</ul>
	<ul class="nav navbar-nav navbar-right">
		@if (Auth::guest())
			<li><a href="{{ url('/login') }}">Login</a></li>
		@else
			<li class="dropdown user_menu">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					<strong>{{ Auth::user()->name }}</strong>
					&nbsp;
					<img class="user_ico" width="30" src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(Auth::user()->email))) }}?d={{ urlencode('https://www.peertxt.co/images/peertxt_user.png') }}" />
				</a>

				<ul class="dropdown-menu" role="menu">
					<li><a href="/user_profile">Profile</a></li>
					<li>
						<a href="{{ url('/logout') }}"
							onclick="event.preventDefault();
									 document.getElementById('logout-form').submit();">
							Logout
						</a>
						<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
							{{ csrf_field() }}
						</form>
					</li>
				</ul>
			</li>
		@endif
	</ul>
</div>
