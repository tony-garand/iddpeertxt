@extends('layouts.app')

@section('title', ' - Campaigns - Inbox - #' . $campaign->id)

@section('content')

	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1><a href="/campaigns">Campaigns</a> &gt; {{ $campaign->campaign_name }} &gt; Inbox</h1>
					</div>
				</div>

				<div class="row">
					<div class="inbox-container">
						<div class="inbox-items-section">
							<div class="inbox_items">
								@foreach ($chats as $chat)
									<div class="inbox_item" data-uuid="{{ $chat->uuid }}">
										<div class="name">
											{{ $chat->Contact->first_name }} {{ $chat->Contact->last_name }}
										</div>
										<div class="last_date">
											{{ Carbon\Carbon::createFromTimeString($chat->updated_at)->diffForHumans() }}
										</div>
										<div class="snippet">
											@if (@$chat->LatestThread->message)
												{{ @$chat->LatestThread->message }}
											@else
												&lt;No recent message&gt;
											@endif
										</div>
									</div>
								@endforeach
							</div>
						</div>
						<div class="inbox-message-section">
							<div class="default_msg">
								Select a thread on the left to begin.
							</div>
							<div class="inbox_main" style="display:none;">
								<div class="contact_info"></div>
								<div class="thread_info"></div>
								<div class="reply">
									<textarea rows="4" class="chat_reply_box form-control" data-uuid="" placeholder="Reply here.."></textarea>
									<input type="button" class="chat_reply_btn btn btn-primary" value="Send Message">
									<input type="button" class="btn btn-primary pull-right" id="loadReply" name="loadReply" value="Load Reply"/>
									<select class="pull-right" id="custom_replies" name="custom_replies">
										@foreach ($customReplies as $k=>$reply)
											<option value="{{ $k }}">{{ $reply }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addContactField" tabindex="-1" role="dialog" aria-labelledby="addContactFieldLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">

					<div class="row">
						<h3>Attach to Contact Profile</h3>

						<div class="warning_info">You are about to attach this reply to a field on this contact profile. Only proceed if you want to do this - you can overwrite data by continuing!</div>

						<div class="form-group">
							<label for="role_id" class="col-sm-3 control-label">Contact:</label>
							<div class="col-sm-8">
								<span id="attach_contact"></span>
							</div>
						</div>

						<div class="form-group">
							<label for="role_id" class="col-sm-3 control-label">Content:</label>
							<div class="col-sm-8">
								<span id="attach_message"></span>
							</div>
						</div>

						<div class="form-group">
							<label for="role_id" class="col-sm-3 control-label">Field:</label>
							<div class="col-sm-8">
								<select class="form-control" id="attach_fields" name="field_id">
								</select>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<br/>
								<button id="attach_submit" data-uuid="" type="button" class="btn btn-primary attach_submit">Save Field</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

@endsection
