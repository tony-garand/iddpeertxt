@extends('layouts.app')

@section('title', ' - Custom Replies')

@push('scripts')
	<script src="{{ asset('js/pages/custom-replies.js') }}"></script>
@endpush

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
              <h1><a href="{{ route('customReplies') }}">Custom Replies</a> > Reply</h1>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">
						<form id="frm" class="form-horizontal" method="post" action="/customReplies/update/{{ $reply->id }}">
							{{ csrf_field() }}

							@if (Auth::user()->hasRole(['administrator']))
								<div class="form-group">
									<label for="company_id" class="col-sm-3 control-label">Company:</label>
									<div class="col-sm-8">
										<select class="form-control" id="company_id" name="company_id">
											@foreach ($companies as $company)
												<option
													{{ (($company->id == $reply->company_id) ? " selected " : "") }} value="{{ $company->id }}">{{ $company->company_name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							@endif

							<div class="form-group">
								<label for="reply_name" class="col-sm-3 control-label">Name:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="reply_name" name="reply_name" placeholder="" value="{{ $reply->reply_name }}" required />
								</div>
							</div>
							<div class="form-group">
								<label for="label" class="col-sm-3 control-label">Body:</label>
								<div class="col-sm-8">
									<textarea class="form-control" rows="3" id="reply_body" name="reply_body" required>{{ $reply->reply_body }}</textarea>
									<div class="row" style="margin-top: 5px;">
										<div class="col-md-4">
											<select class="form-control" id="fields" name="fields">
												@foreach (customReplyFields() as $field=>$label)
													<option value="{{ $field }}">{{ $label }}</option>
												@endforeach
											</select>
										</div>
										<div class="col-md-8">
											<button id="add_field" type="button" class="btn btn-primary">Add Field</button>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-9">
									<button id="add_btn" type="submit" class="btn btn-primary">Save</button>
								</div>
							</div>

						</form>
					</div>
				</div>

			</div>
		</div>
	</div>
@endsection