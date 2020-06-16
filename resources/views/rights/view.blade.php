@extends('layouts.app')

@section('title', ' - Rights - #' . $right->id)

@section('content')

	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1><a href="/rights">Rights</a> &gt; View Right</h1>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#editright"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> &nbsp; Edit Right</a></li>
							<li><a data-toggle="tab" href="#rightusers"><i class="fa fa-users" aria-hidden="true"></i> &nbsp; Right Users</a></li>
							<li><a data-toggle="tab" href="#rightgroups"><i class="fa fa-cubes" aria-hidden="true"></i> &nbsp; Right Groups</a></li>
						</ul>

						<div class="tab-content">
							<div id="editgroup" class="tab-pane fade in active">
								@include('shared.forms.edit_right')
							</div>
							<div id="rightusers" class="tab-pane fade">
								@include('shared.forms.edit_right_users')
							</div>
							<div id="rightgroups" class="tab-pane fade">
								@include('shared.forms.edit_right_groups')
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
