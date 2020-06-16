<form id="frm" class="form-horizontal" method="post" action="/campaigns/update/{{ $campaign->id }}"
      enctype="multipart/form-data">
    {{ csrf_field() }}

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#details"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                &nbsp; Details</a></li>
        <li><a data-toggle="tab" href="#content"><i class="fa fa-file-text" aria-hidden="true"></i> &nbsp; Content</a>
        </li>
        <li><a data-toggle="tab" href="#contacts"><i class="fa fa-users" aria-hidden="true"></i> &nbsp; Contacts</a>
        </li>
        <li><a data-toggle="tab" href="#rights"><i class="fa fa-flag" aria-hidden="true"></i> &nbsp; Rights</a></li>
        <li><a data-toggle="tab" href="#phones"><i class="fa fa-server" aria-hidden="true"></i> &nbsp; Phones</a></li>
        <li><a data-toggle="tab" href="#delivery"><i class="fa fa-clock-o" aria-hidden="true"></i> &nbsp; Delivery Time</a>
        </li>
    </ul>

    <div class="tab-content">
        <div id="details" class="tab-pane fade in active">
            <br/>

            @if (Auth::user()->hasRole(['administrator']))
                <div class="form-group">
                    <label for="company_id" class="col-sm-3 control-label">Company:</label>
                    <div class="col-sm-8">
                        <select required class="form-control ajax_load_campaign_data" id="company_id" name="company_id">
                            <option value=""></option>
                            @foreach ($companies as $company)
                                <option {{ (($company->id == $campaign->company_id) ? " selected " : "") }} value="{{ $company->id }}">{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label for="campaign_status" class="col-sm-3 control-label">Status:</label>
                <div class="col-sm-8">
                    <select class="form-control" id="campaign_status" name="campaign_status">
                        @if ($campaign->campaign_status > 1)
                            <option value="{{ $campaign->campaign_status }}">{{ campaign_status($campaign->campaign_status) }}</option>
                        @else
                            <option {{ (($campaign->campaign_status == 0) ? " selected " : "") }} value="0">Draft
                            </option>
                            <option {{ (($campaign->campaign_status == 1) ? " selected " : "") }} value="1">Ready
                            </option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="campaign_type" class="col-sm-3 control-label">Type:</label>
                <div class="col-sm-8">
                    <select class="form-control" id="campaign_type" name="campaign_type">
                        <option {{ (($campaign->campaign_type == 1) ? " selected " : "") }} value="1">Peer to Peer
                        </option>
                        <option {{ (($campaign->campaign_type == 2) ? " selected " : "") }} value="2">Direct Delivery
                            (Opt-In)
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="campaign_name" class="col-sm-3 control-label">Campaign Name:</label>
                <div class="col-sm-8">
                    <input required type="text" class="form-control" id="campaign_name" name="campaign_name"
                           value="{{ $campaign->campaign_name }}"/>
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="col-sm-3 control-label">Description:</label>
                <div class="col-sm-8">
                    <textarea class="form-control" rows="5" id="description"
                              name="description">{{ $campaign->description }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="messaging_service" class="col-sm-3 control-label">Messaging Service:</label>
                <div class="col-sm-8">
                    <select class="form-control" id="messaging_service" name="messaging_service" @if ($campaign->campaign_status !== 0) disabled @endif>
                        <option value="none"></option>
                        @if ($campaign->messaging_service_id != 0)
                            <option selected value="{{ $campaign->messaging_service_id }}">{{ $campaign->MessagingService->name . '; ' . $campaign->MessagingService->number_list }}</option>
                        @endif
                        @foreach ($messaging_services as $service)
                            <option value="{{ $service->id }}">{{ $service->name . '; ' . $service->number_list }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="areacode" class="col-sm-3 control-label">Preferred Area Code:</label>
                <div class="col-sm-8">
                    <input required type="text" class="form-control" id="areacode" name="areacode"
                           value="{{ $campaign->areacode }}" maxlength="3"/>
                </div>
            </div>

            <div class="form-group">
                <label for="nearphone" class="col-sm-3 control-label">Near Phone Number:</label>
                <div class="col-sm-8">
                    <input required type="text" class="form-control" id="nearphone" name="nearphone"
                           value="{{ $campaign->nearphone }}" maxlength="15"/>
                </div>
            </div>

            <div class="form-group">
                <label for="zipcode" class="col-sm-3 control-label">Preferred Zip Code:</label>
                <div class="col-sm-8">
                    <input required type="text" class="form-control" id="zipcode" name="zipcode"
                           value="{{ $campaign->zipcode }}" maxlength="5"/>
                </div>
            </div>

            <div class="form-group">
                <label for="tags" class="col-sm-3 control-label">Tags:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="tags" name="tags"
                           value="{{ $campaign->tagsToString() }}"/>
                </div>
            </div>
        </div>
        <div id="content" class="tab-pane fade">
            <br/>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-8">
                    Content options are different text options for A/B testing of your campaign. You need at least one
                    content option. If a conversion link is included that
                    link will be appended to the end of the text message.
                </div>
            </div>

            @for ($i = 1; $i < 5; $i++)
                <div class="form-group">
                    <label for="content_template_{{ $i }}" class="col-sm-3 control-label">Content Option {{ $i }}
                        :</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" rows="3" id="content_template_{{ $i }}"
                                  name="content_template_{{ $i }}">{{ ${'campaign'}->{'content_template_' . $i} }}</textarea>
                    </div>
                    <div class="col-sm-offset-3 col-sm-9">
                        <div class="tag magic_tag" data-id="{{ $i }}" data-content="[[first_name]]">first_name</div>
                        <div class="tag magic_tag" data-id="{{ $i }}" data-content="[[last_name]]">last_name</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="file_upload" class="col-sm-3 control-label">MMS File {{ $i }}:</label>
                    @if (${'campaign'}->{'content_media_' . $i})
                        <div class="col-sm-6">
                            <input type="file" accept=".png,.jpg,.jpeg" class="form-control-file"
                                   name="file_upload_{{ $i }}" id="file_upload_{{ $i }}" aria-describedby="fileHelp">
                            <small id="fileHelp" class="form-text text-muted">Uploading an image will replace the
                                existing image and attach it to this campaign option. To remove this image, click the
                                checkbox below.
                            </small>
                            <br/><br/>
                            <input type="checkbox" name="delete_mms_img_{{ $i }}" value="{{ $i }}"/> Delete MMS image
                        </div>
                        <div class="col-sm-2">
                            <a href="{{ ${'campaign'}->{'content_media_' . $i} }}" target="_blank"><img
                                        src="{{ ${'campaign'}->{'content_media_' . $i} }}" width="100%"/></a>
                        </div>
                    @else
                        <div class="col-sm-8">
                            <input type="file" accept=".png,.jpg,.jpeg" class="form-control-file"
                                   name="file_upload_{{ $i }}" id="file_upload_{{ $i }}" aria-describedby="fileHelp">
                            <small id="fileHelp" class="form-text text-muted">Uploading an image will attach it to
                                outgoing messaging. Images should be JPEG or PNG.
                            </small>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="conversion_link_1" class="col-sm-3 control-label">Conversion Link {{ $i }}:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="conversion_link_{{ $i }}"
                               name="conversion_link_{{ $i }}" value="{{ ${'campaign'}->{'conversion_link_' . $i} }}"/>
                    </div>
                    <div class="col-sm-offset-3 col-sm-9">
                        <div class="tag magic_tag" data-id="{{ $i }}" data-field="conversion_link_"
                             data-content="[[first_name]]">first_name
                        </div>
                        <div class="tag magic_tag" data-id="{{ $i }}" data-field="conversion_link_"
                             data-content="[[email]]">email
                        </div>
                        <div class="tag magic_tag" data-id="{{ $i }}" data-field="conversion_link_"
                             data-content="[[phone]]">phone
                        </div>
                        <div class="tag magic_tag" data-id="{{ $i }}" data-field="conversion_link_"
                             data-content="[[contact_uuid]]">contact uuid
                        </div>
                        <div class="tag magic_tag" data-id="{{ $i }}" data-field="conversion_link_"
                             data-content="[[campaign_uuid]]">campaign uuid
                        </div>
                    </div>
                </div>
            @endfor

        </div>
        <div id="contacts" class="tab-pane fade">
            <br/>

            <div class="form-group">
                <label for="filter_tags" class="col-sm-3 control-label">Filter Tags:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="filter_tags" name="filter_tags"
                           placeholder="Filter tags.. "/>
                </div>
            </div>

            <div class="form-group">
                <label for="tags_list" class="col-sm-3 control-label">Contact Tags:</label>
                <div class="col-sm-8">
                    <select multiple class="form-control" id="tags_list" size="10" name="tags_list[]">
                        @foreach ($contact_tags as $tag)
                            <option {{ (in_array($tag['tag'], $selected_tags)) ? " selected " : "" }} value="{{ $tag['tag'] }}">{{ $tag['data'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
        <div id="rights" class="tab-pane fade">
            <br/>

            <div class="form-group">
                <label for="rights_type" class="col-sm-3 control-label">Rights Type:</label>
                <div class="col-sm-8">
                    <select class="form-control rights_type_toggle" id="rights_type" name="rights_type">
                        <option {{ (($campaign->rights_type == 1) ? " selected " : "") }} value="1">Open Access (All
                            Users)
                        </option>
                        <option {{ (($campaign->rights_type == 2) ? " selected " : "") }} value="2">Rights-Restricted
                        </option>
                    </select>
                </div>
            </div>

            <div class="rights_restricted">
                <div class="form-group">
                    <label for="filter_rights" class="col-sm-3 control-label">Filter Rights:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="filter_rights" name="filter_rights"
                               placeholder="Search for names, etc.. "/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="rights_list" class="col-sm-3 control-label">Rights:</label>
                    <div class="col-sm-8">
                        <select multiple class="form-control" id="rights_list" size="10" name="rights_list[]">
                            @foreach ($my_rights as $my_right)
                                <option {{ ((in_array($my_right->id, $selected_rights)) ? " selected " : "") }} value="{{ $my_right->id }}">{{ $my_right->name }}
                                    ( # {{ $my_right->id }} )
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

        </div>
        <div id="phones" class="tab-pane fade">
            <br/>

            @if ($campaign->campaign_status === 10 && Auth::user()->hasRole(['administrator','manager']))
                <div class="actions">
                    <div class="pull-right action">

                        <a href="javascript:;" class="btn btn-primary small_btn" data-toggle="modal"
                           data-target="#addNumbers">Add
                            More Numbers</a>
                    </div>
                </div>
            @endif

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Phone #</th>
                    <th>Created</th>
                </tr>
                </thead>
                <tbody>
                @if ($campaign->MessagingService)
                    @foreach($campaign->MessagingService->Numbers as $number)
                        <tr>
                            <td>{{ $number->number }}</td>
                            <td>{{ $number->created_at }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div id="delivery" class="tab-pane fade">
            <br>
            <div id="delivery_errors" class="alert alert-danger" style="display: none;">
            </div>

            @foreach($day_list as $dayKey => $dayValue)
                @php $existingDayDeliveryRule = $campaign_delivery_rules->firstWhere('day',$dayKey) @endphp

                <input type="hidden" name="day[{{$dayKey}}]" value="{{$dayKey}}">
                <div class="form-group">
                    <label for="campaign_status" class="col-sm-2 control-label">{{ $dayValue }}:</label>

                    <div class="col-sm-3">
                        <label for="whole_day[{{$dayKey}}]">
                            Whole day delivery <input type="checkbox" class="whole_day_cb"
                                                      {{ $existingDayDeliveryRule && $existingDayDeliveryRule->whole_day ? "checked" : ""}}
                                                      name="whole_day[{{$dayKey}}]" id="whole_day[{{$dayKey}}]"
                                                      value="1">
                        </label>
                    </div>
                    <div class="col-sm-3 time-holder"
                         style="display: {{ $existingDayDeliveryRule && $existingDayDeliveryRule->whole_day ? "none" : "display" }};">
                        <select class="form-control from_time" id="from_time_{{$dayKey}}"
                                name="from_time[{{$dayKey}}]">
                            <option value="">Choose from time</option>
                            @foreach($time_list as $timeKey => $timeValue)
                                <option value="{{ $timeKey }}" {{ $existingDayDeliveryRule && $existingDayDeliveryRule->from_time ==  $timeKey? "selected" : ""}}>{{ $timeValue }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3 time-holder"
                         style="display: {{ $existingDayDeliveryRule && $existingDayDeliveryRule->whole_day ? "none" : "display" }};">
                        <select class="form-control to_time" id="to_time_{{$dayKey}}"
                                name="to_time[{{$dayKey}}]">
                            <option value="">Choose to time</option>
                            @foreach($time_list as $timeKey => $timeValue)
                                <option value="{{ $timeKey }}" {{ $existingDayDeliveryRule && $existingDayDeliveryRule->to_time ==  $timeKey? "selected" : ""}}>{{ $timeValue }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if ($campaign->campaign_status < 3)
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <button id="add_btn" type="submit" class="btn btn-primary">Update</button>
                <button data-href="/campaigns/delete/{{ $campaign->id }}" id="delete_btn" type="button"
                        class="btn btn-delete">Delete Campaign
                </button>
            </div>
        </div>
    @endif

</form>

@push('scripts')
    <script>
        $(document).ready(function () {
            $('input[name="tags"]').amsifySuggestags();
            $("body").on("change", ".whole_day_cb", function () {

                if ($(this).is(":checked")) {
                    $(this).closest('.form-group').find('.time-holder').hide();
                } else {
                    $(this).closest('.form-group').find('.time-holder').show();
                }
            });

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
                    $('#add_btn').html('Update');
                    event.preventDefault();
                }
            });

            jQuery.fn.filterByText = function (textbox) {
                return this.each(function () {
                    var select = this;
                    var options = [];
                    $(select).find('option').each(function () {
                        options.push({
                            value: $(this).val(),
                            text: $(this).text()
                        });
                    });
                    $(select).data('options', options);

                    $(textbox).bind('change keyup', function () {
                        var options = $(select).empty().data('options');
                        var search = $.trim($(this).val());
                        var regex = new RegExp(search, "gi");

                        $.each(options, function (i) {
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


            $(function () {
                $('#tags_list').filterByText($('#filter_tags'));
            });
        });
    </script>
@endpush
