@extends('layouts.app')

@section('title', ' - Groups - #' . $group->id)

@section('content')

	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1><a href="/groups">Groups</a> &gt; View Group</h1>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#editgroup"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> &nbsp; Edit Group</a></li>
							<li><a data-toggle="tab" href="#groupusers"><i class="fa fa-users" aria-hidden="true"></i> &nbsp; Group Users</a></li>
						</ul>

						<div class="tab-content">
							<div id="editgroup" class="tab-pane fade in active">
								@include('shared.forms.edit_group')
							</div>
							<div id="groupusers" class="tab-pane fade">
								@include('shared.forms.edit_group_users')
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
