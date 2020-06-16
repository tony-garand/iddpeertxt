<form id="frm" class="form-horizontal" method="post" action="/campaigns/save" enctype="multipart/form-data">
	{{ csrf_field() }}
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#details"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> &nbsp; Details</a></li>
		<li><a data-toggle="tab" href="#content"><i class="fa fa-file-text" aria-hidden="true"></i> &nbsp; Content</a></li>
		<li><a data-toggle="tab" href="#contacts"><i class="fa fa-users" aria-hidden="true"></i> &nbsp; Contacts</a></li>
		<li><a data-toggle="tab" href="#rights"><i class="fa fa-flag" aria-hidden="true"></i> &nbsp; Rights</a></li>
		<li><a data-toggle="tab" href="#delivery"><i class="fa fa-clock-o" aria-hidden="true"></i> &nbsp; Delivery Time</a></li>
		<div class="create-button">
			<button id="add_btn" type="submit" class="btn btn-primary">Create</button>
		</div>
	</ul>

	<div class="tab-content">
		<div id="details" class="tab-pane fade in active">
			<br/>

			@if (Auth::user()->hasRole(['administrator']))
			<div class="form-group">
				<label for="company_id" class=" control-label">Company:</label>
				<div>
					<select required class="form-control ajax_load_campaign_data" id="company_id" name="company_id">
						<option value=""></option>
						@foreach ($companies as $company)
							<option value="{{ $company->id }}">{{ $company->company_name }}</option>
						@endforeach
					</select>
				</div>
			</div>
			@endif

			<div class="form-group">
				<label for="campaign_status" class=" control-label">Status:</label>
				<div>
					<select class="form-control" id="campaign_status" name="campaign_status">
						<option value="0">Draft</option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label for="campaign_type" class=" control-label">Type:</label>
				<div>
					<select class="form-control" id="campaign_type" name="campaign_type">
						<option value="1">Peer to Peer</option>
						<option value="2">Direct Delivery (Opt-In)</option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label for="campaign_name" class=" control-label">Campaign Name:</label>
				<div>
					<input required type="text" class="form-control" id="campaign_name" name="campaign_name" placeholder="" />
				</div>
			</div>

			<div class="form-group">
				<label for="description" class=" control-label">Description:</label>
				<div>
					<textarea class="form-control" rows="5" id="description" name="description"></textarea>
				</div>
			</div>

			<div class="form-group">
				<label for="messaging_service" class="control-label">Messaging Service:</label>
				<div>
					<select class="form-control" id="messaging_service" name="messaging_service">
						<option value="none"></option>
						@if ($messaging_services->count() > 0)
							@foreach ($messaging_services as $service)
								<option value="{{ $service->id }}">{{ $service->name . '; ' . $service->number_list }}</option>
							@endforeach
						@endif
					</select>
				</div>
			</div>

			<div class="form-group">
				<label for="areacode" class=" control-label">Preferred Area Code:</label>
				<div>
					<input required type="text" class="form-control" id="areacode" name="areacode" value="{{ $company->default_areacode }}" maxlength="3" />
				</div>
			</div>

			<div class="form-group">
				<label for="nearphone" class=" control-label">Near Phone Number:</label>
				<div>
					<input required type="text" class="form-control" id="nearphone" name="nearphone" value="{{ $company->default_nearphone }}" maxlength="15" />
				</div>
			</div>

			<div class="form-group">
				<label for="zipcode" class=" control-label">Preferred Zip Code:</label>
				<div>
					<input required type="text" class="form-control" id="zipcode" name="zipcode" value="{{ $company->default_zipcode }}" maxlength="5" />
				</div>
			</div>

			<div class="form-group">
				<label for="tags" class=" control-label">Tags:</label>
				<div>
					<input type="text" class="form-control" id="tags" name="tags" />
					<small id="fileHelp" class="form-text text-muted">Comma-delimited list of tags to apply to the campaign</small>
				</div>
			</div>

		</div>
		<div id="content" class="tab-pane fade">
			<br/>

			<div class="form-group">
				<div>
					Content options are different text options for A/B testing of your campaign. You need at least one content option. If a conversion link is included that
					link will be appended to the end of the text message.
				</div>
			</div>
			<hr>
			@for ($i = 1; $i < 5; $i++)
			<div class="content-option-{{ $i }}">
				<div class="form-group">
					<label for="content_template_{{ $i }}" class=" control-label">Content Option {{ $i }}:</label>
					<div>
						<textarea {{ (($i == 1) ? " required " : "") }} class="form-control" rows="3" id="content_template_{{ $i }}" name="content_template_{{ $i }}"></textarea>
					</div>
					<div>
						<div class="tag magic_tag" data-id="{{ $i }}" data-field="content_template_" data-content="[[first_name]]">first_name</div>
						<div class="tag magic_tag" data-id="{{ $i }}" data-field="content_template_" data-content="[[last_name]]">last_name</div>
					</div>
				</div>

				<div class="form-group">
					<label for="file_upload" class=" control-label">MMS File {{ $i }}:</label>
					<div>
						<input type="file" accept=".png,.jpg,.jpeg" class="form-control-file" name="file_upload_{{ $i }}" id="file_upload_{{ $i }}" aria-describedby="fileHelp">
						<small id="fileHelp" class="form-text text-muted">Uploading an image will attach it to outgoing messaging. Images should be JPEG or PNG.</small>
					</div>
				</div>

				<div class="form-group">
					<label for="conversion_link_1" class=" control-label">Conversion Link {{ $i }}:</label>
					<div>
						<input type="text" class="form-control" id="conversion_link_{{ $i }}" name="conversion_link_{{ $i }}" placeholder="https://" />
					</div>
					<div>
						<div class="tag magic_tag" data-id="{{ $i }}" data-field="conversion_link_" data-content="[[first_name]]">first_name</div>
						<div class="tag magic_tag" data-id="{{ $i }}" data-field="conversion_link_" data-content="[[email]]">email</div>
						<div class="tag magic_tag" data-id="{{ $i }}" data-field="conversion_link_" data-content="[[phone]]">phone</div>
						<div class="tag magic_tag" data-id="{{ $i }}" data-field="conversion_link_" data-content="[[contact_uuid]]">contact uuid</div>
						<div class="tag magic_tag" data-id="{{ $i }}" data-field="conversion_link_" data-content="[[campaign_uuid]]">campaign uuid</div>
					</div>
				</div>
			</div>
			@endfor
		</div>
		<div id="contacts" class="tab-pane fade">
			<br/>

			<div class="form-group">
				<label for="filter_tags" class=" control-label">Filter Tags:</label>
				<div>
					<input type="text" class="form-control" id="filter_tags" name="filter_tags" placeholder="Filter tags..." />
				</div>
			</div>

			<div class="form-group">
				<label for="tags_list" class=" control-label">Contact Tags:</label>
				<div>
					<select multiple class="form-control" id="tags_list" size="10" name="tags_list[]">
						@foreach ($contact_tags as $tag)
							<option value="{{ $tag['tag'] }}">{{ $tag['data'] }}</option>
						@endforeach
					</select>
				</div>
			</div>

		</div>
		<div id="rights" class="tab-pane fade">
			<br/>

			<div class="form-group">
				<label for="rights_type" class=" control-label">Rights Type:</label>
				<div>
					<select class="form-control rights_type_toggle" id="rights_type" name="rights_type">
						<option value="1">Open Access (All Users)</option>
						<option value="2">Rights-Restricted</option>
					</select>
				</div>
			</div>

			<div class="rights_restricted" style="display:none;">
				<div class="form-group">
					<label for="filter_rights" class=" control-label">Filter Rights:</label>
					<div>
						<input type="text" class="form-control" id="filter_rights" name="filter_rights" placeholder="Search for names, etc.. " />
					</div>
				</div>

				<div class="form-group">
					<label for="rights_list" class=" control-label">Rights:</label>
					<div>
						<select multiple class="form-control" id="rights_list" size="10" name="rights_list[]">
							@foreach ($my_rights as $my_right)
								<option value="{{ $my_right->id }}">{{ $my_right->name }} ( # {{ $my_right->id }} )</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>

		</div>
		<div id="delivery" class="tab-pane fade">
			<br>
			<div id="delivery_errors" class="alert alert-danger" style="display: none;">
			</div>
			@foreach($day_list as $dayKey => $dayValue)
				<input type="hidden" name="day[{{$dayKey}}]" value="{{$dayKey}}">
				<div class="form-group">
					<label for="campaign_status" class="col-sm-2 control-label">{{ $dayValue }}:</label>

					<div class="col-sm-3">
						<label for="whole_day[{{$dayKey}}]">
							Whole day delivery <input type="checkbox" class="whole_day_cb" checked
							name="whole_day[{{$dayKey}}]" id="whole_day[{{$dayKey}}]" value="1">
						</label>
					</div>
					<div class="col-sm-3 time-holder" style="display: none">
						<select class="form-control" id="from_time_{{$dayKey}}" name="from_time[{{$dayKey}}]">
							<option value="">Choose from time</option>
							@foreach($time_list as $timeKey => $timeValue)
								<option value="{{ $timeKey }}">{{ $timeValue }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-sm-3 time-holder" style="display: none;">
						<select class="form-control" id="to_time_{{$dayKey}}" name="to_time[{{$dayKey}}]">
							<option value="">Choose to time</option>
							@foreach($time_list as $timeKey => $timeValue)
								<option value="{{ $timeKey }}">{{ $timeValue }}</option>
							@endforeach
						</select>
					</div>
				</div>
			@endforeach
		</div>
	</div>
</form>

@push('scripts')
	<script>
		$(document).ready(function() {
			$('input[name="tags"]').amsifySuggestags();


            const form = document.getElementById("frm");
            form.addEventListener('submit', function (event) {
                $("#delivery_errors").hide().html('');

                var errors = false;
                $(".whole_day_cb").each(function (k, v) {
                    if (!$(v).is(":checked")) {
                        var from_time = parseInt($($(".from_time")[k]).val());
                        var to_time = parseInt($($(".to_time")[k]).val());
                        if (!from_time || !to_time || (from_time >= to_time)) {
                            errors = true;
                        }
                    }
                })
                if (errors) {
                    $("#delivery_errors").show().html('Make sure you have chosen correct from and to time. To time cannot be smaller than from time.');
                    $('#add_btn').html('Create');
                    event.preventDefault();
                }
            });

			jQuery.fn.filterByText = function(textbox) {
				return this.each(function() {
					var select = this;
					var options = [];
					$(select).find('option').each(function() {
						options.push({
							value: $(this).val(),
							text: $(this).text()
						});
					});
					$(select).data('options', options);

					$(textbox).bind('change keyup', function() {
						var options = $(select).empty().data('options');
						var search = $.trim($(this).val());
						var regex = new RegExp(search, "gi");

						$.each(options, function(i) {
							var option = options[i];
							if (option.text.match(regex) !== null) {
								$(select).append(
									$('<option>').text(option.text).val(option.value)
								);
							}
						});
					});
				});
			};

			$(function() {
				$('#tags_list').filterByText($('#filter_tags'));
			});
		});
	</script>
@endpush
