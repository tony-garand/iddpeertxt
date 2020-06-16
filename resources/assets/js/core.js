var getTotals;
var inboxRefresh;
var currentChatUuid;
var currentChatThreadUuid;

import Echo from "laravel-echo"
import Pusher from "pusher-js"

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '81d067031a79cb7fc482',
    cluster: 'us2',
    forceTLS: true
});

$(document).ready(function () {
    $.fn.autoResize = function (options) {

        // Just some abstracted details,
        // to make plugin users happy:
        var settings = $.extend({
            onResize: function () {
            },
            animate: true,
            animateDuration: 150,
            animateCallback: function () {
            },
            extraSpace: 20,
            limit: 1000
        }, options);

        // Only textarea's auto-resize:
        this.filter('textarea').each(function () {

            // Get rid of scrollbars and disable WebKit resizing:
            var textarea = $(this).css({resize: 'none', 'overflow-y': 'hidden'}),

                // Cache original height, for use later:
                origHeight = textarea.height(),

                // Need clone of textarea, hidden off screen:
                clone = (function () {

                    // Properties which may effect space taken up by chracters:
                    var props = ['height', 'width', 'lineHeight', 'textDecoration', 'letterSpacing'],
                        propOb = {};

                    // Create object of styles to apply:
                    $.each(props, function (i, prop) {
                        propOb[prop] = textarea.css(prop);
                    });

                    // Clone the actual textarea removing unique properties
                    // and insert before original textarea:
                    return textarea.clone().removeAttr('id').removeAttr('name').css({
                        position: 'absolute',
                        top: 0,
                        left: -9999
                    }).css(propOb).attr('tabIndex', '-1').insertBefore(textarea);

                })(),
                lastScrollTop = null,
                updateSize = function () {

                    // Prepare the clone:
                    clone.height(0).val($(this).val()).scrollTop(10000);

                    // Find the height of text:
                    var scrollTop = Math.max(clone.scrollTop(), origHeight) + settings.extraSpace,
                        toChange = $(this).add(clone);

                    // Don't do anything if scrollTip hasen't changed:
                    if (lastScrollTop === scrollTop) {
                        return;
                    }
                    lastScrollTop = scrollTop;

                    // Check for limit:
                    if (scrollTop >= settings.limit) {
                        $(this).css('overflow-y', '');
                        return;
                    }
                    // Fire off callback:
                    settings.onResize.call(this);

                    // Either animate or directly apply height:
                    settings.animate && textarea.css('display') === 'block' ?
                        toChange.stop().animate({height: scrollTop}, settings.animateDuration, settings.animateCallback)
                        : toChange.height(scrollTop);
                };

            // Bind namespaced handlers to appropriate events:
            textarea
                .unbind('.dynSiz')
                .bind('keyup.dynSiz', updateSize)
                .bind('keydown.dynSiz', updateSize)
                .bind('change.dynSiz', updateSize);

        });

        // Chain:
        return this;

    };

    var toolTips = $('[data-toggle="tooltip"]');
    if (toolTips.length) {
        toolTips.tooltip({html: true});
    }

    $.fn.dataTable.moment('MM/DD/YYYY');
    $.fn.dataTable.moment('MM/DD/YYYY HH:mm:ss');

    var dataTable = $('.datatable');
    if (dataTable.length) {

        var loadin = $('.loadin');
        dataTable.DataTable({
            "lengthMenu": [[25, 50, 100, 250, 500, -1], [25, 50, 100, 250, "All"]],
            stateSave: true,

            "aaSorting": [
                [$('.datatable thead th.sort_by').index('.datatable thead th'), $('.datatable thead th.sort_by').attr('data-sort_order')]
            ],
            "fnPreDrawCallback": function () {
                dataTable.hide();
                loadin.show();
            },
            "fnDrawCallback": function () {
                dataTable.show();
                loadin.hide();
            },
            "fnInitComplete": function () {
                dataTable.show();
                loadin.hide();
            }
        });
    }

    $('.clean').keypress(function (e) {
        var regex = new RegExp("^[a-zA-Z0-9-_]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        e.preventDefault();
        return false;
    });

    $('.numonly').keypress(function (e) {
        var regex = new RegExp("^[0-9]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        e.preventDefault();
        return false;
    });

    var colorField = $('input#color');
    if (colorField.length) {
        colorField.spectrum({
            showPalette: true,
            showInput: true,
            color: colorField.val(),
            palette: [
                ['#61bd4f', '#f2d600', '#ff9f1a', '#d8242e', '#c377e0'],
                ['#0079bf', '#00c2e0', '#51e898', '#ff78cb', '#355263']
            ]
        });
    }

    $('input.colorpick').spectrum({
        showInput: true,
        showPalette: true,
//		color: colorF.val(),
        preferredFormat: "hex",
        appendTo: "#addHT"
    });

    var contactType = $('#contact-type');
    if (contactType.length) {
        contactType.each(function () {
            $(this).selectize({
                persist: false,
                maxItems: null,
                options: $(this).data('options'),
                create: function (input) {
                    return {text: input, value: input};
                }
            });
        });
    }

	$('#frm').on('submit', function(e) {
		if ($('#tags').val() === "") {
			alert("Please enter at least one tag first!");
			$('.amsify-suggestags-input').focus();
			return false;
		}

		$('#add_btn').html('<i class="fa fa-circle-o-notch fa-spin"></i> Processing..');
		// $('#add_server_btn').attr('disabled', true);
	});

    $('button#edit_lead_btn').on('click', function (e) {
        $('#edit_lead_btn').html('<i class="fa fa-circle-o-notch fa-spin"></i> Reprocessing..');
    });

    $('button#add_lead_btn').on('click', function (e) {
        $('#add_lead_btn').html('<i class="fa fa-circle-o-notch fa-spin"></i> Processing..');
    });

    $('#delete_btn').on('click', function () {
        var conf = confirm('Are you sure you want to do this?');
        if (conf) {
            var href = $(this).attr('data-href');
            window.location.href = href;
        }
    });

    $('.frm').on('submit', function (e) {
        $('.add_btn').html('<i class="fa fa-circle-o-notch fa-spin"></i> Processing..');
    });

    $(document).on("click", ".begin_campaign_btn", function (e) {
        var id = $(this).attr('data-id');
        if (confirm('Are you sure you want to begin this campaign? \n\nOnce this campaign begins you will no longer be able to make modifications.')) {
            window.location.href = '/campaigns/begin/' + id;
        }
    });

    $(document).on("click", ".golive_campaign_btn", function (e) {
        var id = $(this).attr('data-id');
        if (confirm('Are you sure you want to go live with this campaign? \n\nOnce this campaign goes live, SMS can be processed and go out live to your contacts.')) {
            window.location.href = '/campaigns/go_live/' + id;
        }
    });

	$(document).on("click", ".sendlive_campaign_btn", function (e) {
		var id = $(this).attr('data-id');
		if (confirm('Are you sure you want to send this campaign? \n\nOnce this campaign begins sending, SMS will go out live to your contacts.')) {
			window.location.href = '/campaigns/go_send/' + id;
		}
	});

    // $('.golive_campaign_btn').on('click', function() {
    // 	var id = $(this).attr('data-id');
    // 	if (confirm('Are you sure you want to go live with this campaign? \n\nOnce this campaign goes live, SMS can be processed and go out live to your contacts.')) {
    // 		window.location.href = '/campaigns/go_live/' + id;
    // 	}
    // });

    $('.chat_reply_btn').on('click', function () {
        var chat_uuid = $('.chat_reply_box').data('uuid');
        var msg = $('.chat_reply_box').val().trim();

        if (chat_uuid) {
            if (msg) {
                $('.chat_reply_btn').val('Processing..').prop('disabled', true);
                $.ajax({
                    url: '/campaigns/ajax_inbox_send',
                    data: {chat_uuid: chat_uuid, message: msg, _token: window.Laravel.csrfToken},
                    type: 'post',
                    dataType: 'json',
                    success: function (json) {
                        $('.chat_reply_box').val('');
                        refreshInbox(chat_uuid);
                    },
                    complete: function () {
                        $('.chat_reply_btn').val('Send Message').prop('disabled', false);
                    }
                });
            }
        } else {
            alert('An error has occurred, try again later.');
        }
    });

    $('.inbox_item').on('click', function () {
        var chat_uuid = $(this).attr('data-uuid');
        currentChatUuid = chat_uuid;
        $('.chat_reply_box').data('uuid', chat_uuid);
        $('textarea.chat_reply_box').val('');

        $('.inbox_item').removeClass('inbox_item_on');
        $(this).addClass('inbox_item_on');

        clearTimeout(inboxRefresh);
        refreshInbox(chat_uuid);
    });


    $(document).on('click', '#attach_submit', function () {
        var field_id = $('#attach_fields').val();
        if (currentChatThreadUuid) {
            if (field_id) {
                $('#attach_submit').val('Processing..').prop('disabled', true);
                $.ajax({
                    url: '/campaigns/ajax_attach_contact_field',
                    data: {thread_uuid: currentChatThreadUuid, field_id: field_id, _token: window.Laravel.csrfToken},
                    type: 'post',
                    dataType: 'json',
                    success: function (json) {
                        $('#addContactField').modal('hide');
                        if (currentChatUuid) {
                            clearTimeout(inboxRefresh);
                            refreshInbox(currentChatUuid);
                        }
                    },
                    complete: function () {
                        $('#attach_submit').val('Save Field').prop('disabled', false);
                    }
                });
            }
        }

    });

    $(document).on('click', '.save_ico', function () {
        var thread_uuid = $(this).attr('data-threadid');
        currentChatThreadUuid = thread_uuid;

        $('#attach_fields').html('');
        $('#attach_submit').val('Save Field').prop('disabled', false);

        // get data about this thread back from the ajax endpoint..
        if (thread_uuid) {
            $.ajax({
                url: "/campaigns/ajax_chat_thread_info?thread_uuid=" + thread_uuid,
                dataType: "JSON",
                success: function (json) {
                    console.log(json);

                    var contact = json['contact'];
                    var message = json['message'];
                    var fields = json['fields'];

                    $('#attach_contact').html(contact);
                    $('#attach_message').html(message);
                    $('#attach_fields').append('<option value=""></option>');

                    for (var i = 0; i < fields.length; i++) {
                        $('#attach_fields').append('<option value="' + fields[i].id + '">' + fields[i].val + '</option>');
                    }

                    $('#addContactField').modal('show');

                }
            });
        }

    });

    function refreshInbox(chat_uuid) {

        clearTimeout(inboxRefresh);

        if (chat_uuid) {
            $.ajax({
                url: "/campaigns/ajax_inbox_chat?chat_uuid=" + chat_uuid,
                dataType: "JSON",
                success: function (json) {
                    console.log(json);
                    $('.default_msg').hide();
                    $('.inbox_main').show();
                    $('.thread_info').html('');

                    var contact = json['contact'];
                    var messages = json['messages'];
                    var message_cnt = 0;

                    for (var i = 0; i < messages.length; i++) {

                        var append = '';
                        var save = '';

                        if (messages[i].direction === 1) {
                            // incoming
                            save = '<div class="save_ico" data-threadid="' + messages[i].uuid + '"><i class="fa fa-bookmark" aria-hidden="true"></i></div>';
                        }

                        if (messages[i].status === 0) {
                            append = ' (Unsent)';
                        }

                        $('.thread_info').append('<div class="message_line'+ ' message_direction_' + messages[i].direction + '">' + '<div class="info info_direction_' + messages[i].direction + '">' + ' <span class="messenger">' + messages[i].who + '</span>' + '<span class="save-message">' + save + '</span>' + ' <span class="message-date" title="' + messages[i].full_date + '"> about ' + messages[i].date + append + '</span></div>' + '<div class="message message_status_' + messages[i].status + '">' + messages[i].message + '</div>');
                        
                        if (contact) {
                            $('.contact_info').html('<div class="contact_info_name">' + contact.first_name + ' ' + contact.last_name + '<br/>' + contact.email + '</div><div class="contact_info_address">' + contact.address1 + '<br/>' + contact.city + ', ' + contact.state + ' ' + contact.zip + '</div>');
                        }
                        message_cnt++;
                    }

                    inboxRefresh = setTimeout(function () {
                        refreshInbox(chat_uuid);
                    }, 3000);

                }
            });
        }
    }

    $('input#loadReply').on('click', function (e) {
        let replyId = $('#custom_replies').val();

        if (replyId > 0) {
            $.ajax({
                url: "/campaigns/ajax_get_custom_reply?reply_id=" + replyId + "&chat_uuid=" + currentChatUuid,
                success: function (data) {
                    $('textarea.chat_reply_box').empty().val(data);
                }
            });
        } else {
            alert('Select a reply first!');
        }
    });

    $('.cancel_hold_btn').on('click', function () {
        if (confirm('Are you sure you want to release this message?\n\nSomeone else will be able to send it if you do.')) {
            var campaign_uuid = $(this).attr('data-campaign_uuid');
            var click_uuid = $(this).attr('data-uuid');

            clearTimeout(getTotals);

            $.ajax({
                url: "/campaigns/ajax_run_cancel_item?campaign_uuid=" + campaign_uuid + "&uuid=" + click_uuid,
                dataType: "JSON",
                success: function (json) {
                    console.log(json);
                    $('.begin_p2p_btn').val($('.begin_p2p_btn').attr('data-value')).prop('disabled', false);
                    $('.p2p_action').hide();
                    $('.intro').show();
                }
            });
        }
    });

    $('.send_txt_btn').on('click', function () {
        $('.send_txt_btn').val('Processing..').prop('disabled', true);
        var campaign_uuid = $(this).attr('data-campaign_uuid');
        var click_uuid = $(this).attr('data-uuid');
        $.ajax({
            url: "/campaigns/ajax_run_send_item?campaign_uuid=" + campaign_uuid + "&uuid=" + click_uuid,
            dataType: "JSON",
            success: function (json) {
                console.log(json);
                if (json.status === 'ok') {
                    $('.send_txt_btn').val($('.send_txt_btn').attr('data-value')).prop('disabled', false);
                    getCampaignItem(campaign_uuid);
                } else {
                    $('.begin_p2p_btn').val($('.begin_p2p_btn').attr('data-value')).prop('disabled', false);
                    $('.p2p_action').hide();
                    $('.intro').show();
                }
            }
        });
    });

    $('.begin_p2p_btn').on('click', function () {
        $('.begin_p2p_btn').val('Processing..').prop('disabled', true);

        var campaign_uuid = $(this).attr('data-uuid');

        getTotals = setTimeout(function () {
            getCampaignTotals(campaign_uuid);
        }, 5000);

        getCampaignItem(campaign_uuid);

    });

    if ($('.callback_watch_campaign').length > 0) {
        var campaign_id = $('.callback_watch_campaign').attr('data-id');
        setTimeout(function () {
            getCampaignUpdates(campaign_id);
        }, 5000);
    }

    $('.ajax_load_campaign_data').on('change', function () {

        var company_id = $(this).val();

        $.ajax({
            url: "/campaigns/ajax_get_campaign_creation_data?company_id=" + company_id,
            dataType: "JSON",
            success: function (json) {
                console.log(json);

                var contacts = json['tags'];
                var rights = json['rights'];
                var company = json['company'];
                var messagingServices = json['messaging_services'];

                console.log(contacts);
                $('#tags_list').html('');
                $('#rights_list').html('');
                $('#messaging_service').empty();

                var contact_cnt = 0;
                for (var i = 0; i < contacts.length; i++) {
                    $('#tags_list').append('<option value="' + contacts[i].tag + '">' + contacts[i].data + '</option>');
                    contact_cnt++;
                }

                var right_cnt = 0;
                for (var i = 0; i < rights.length; i++) {
                    $('#rights_list').append('<option value="' + rights[i].id + '">' + rights[i].name + ' ( #' + rights[i].id + ' )</option>');
                    right_cnt++;
                }

                var ms_cnt = 0;
                $('#messaging_service').append('<option value="none"></option>');
                for (var i = 0; i < messagingServices.length; i++) {
                    $('#messaging_service').append('<option value="' + messagingServices[i].id + '">' + messagingServices[i].name + '; ' + messagingServices[i].number_list + '</option>');
                    ms_cnt++;
                }

                $('#areacode').val(company.default_areacode);
                $('#nearphone').val(company.default_nearphone);
                $('#zipcode').val(company.default_zipcode);

                $('#tags_list').filterByText($('#filter_tags'));
            }
        });

    });

    $('.rights_type_toggle').on('change', function () {
        var v = $(this).val();
        if (v === '2') {
            $('.rights_restricted').show();
        } else {
            $('.rights_restricted').hide();
        }

    });

    $('.magic_tag').on('click', function () {
        var id = $(this).attr('data-id');
        var field = $(this).attr('data-field');
        var content = $(this).attr('data-content');
        var new_content = $('#' + field + id).val() + content;
        $('#' + field + id).focus().val('').val(new_content);
    });

    $('.node_add_key').on('click', function () {
        var node_ip = $(this).attr('data-node_ip');
        if (node_ip) {
            $('#node_ip').val(node_ip);
            jQuery('#addNodeKey').modal('show', {backdrop: 'static'});
        }
    });

    $('body').on('click', '.verify_now_list', function () {
        var o = $(this);
        o.attr('disabled', true);
        var tagValues = $('#tagFilter').val();
        if (tagValues.length == 0) {
            o.removeAttr('disabled');
            return false;
        }

        $.ajax({
            url: "/contacts/verify-list",
            method: 'POST',
            data: {
                _token: window.Laravel.csrfToken,
                tagValues: tagValues
            },
            success: function (response) {
                if (!response.result)
                    o.removeAttr('disabled');
            }
        })
    })
    $('body').on('click', '.verify_now', function () {
        var o = $(this);
        var contact_id = o.data('contact_id');
        if (!contact_id)
            return false;

        o.attr('disabled', true);
        $.ajax({
            url: "/contacts/verify/" + contact_id,
            method: 'POST',
            data: {
                _token: window.Laravel.csrfToken
            },
            success: function (response) {
                // o.removeAttr('disabled');
            }
        })
    })

    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-pills a[href="#' + url.split('#')[1] + '"]').tab('show');
    }

    $(document).on('click', '#note-list a[href*="#"]', function () {
        var href = $(this).attr('href');
        window.location = href.replace('#', '?' + Date.now() + '#');
        return false;

    }).on('click', '.credential-list .edit-btn', function () {
        var row = $(this).closest('tr');
        row.toggleClass('active').find('input,textarea').prop('disabled', !row.hasClass('active'));
        row.find('textarea').autoResize();
        $(this).find('i.fa').toggleClass('fa-edit fa-undo');
        $(this).prev('.save-btn').toggle();

    }).on('click', '.credential-list .save-btn', function () {
        var saveBtn = $(this),
            row = saveBtn.closest('tr'),
            label = row.find('input').val(),
            value = row.find('textarea').val();

        $.ajax({
            url: '/client-credentials/update/' + row.data('id'),
            data: {label: label, client_id: $(this).data('client'), value: value, _token: window.Laravel.csrfToken},
            type: 'post',
            dataType: 'json',
            success: credMsg,
            complete: function () {
                saveBtn.hide();
                saveBtn.siblings('.edit-btn').trigger('click');
            }
        });

    }).on('click', '.credential-list .delete-btn', function () {
        if (confirm('Are you sure you want to delete the "' + $(this).data('title') + '" credential?')) {
            var row = $(this).closest('tr');
            $.ajax({
                url: '/client-credentials/delete/' + $(this).data('id'),
                data: {
                    cred_type: $(this).data('type'),
                    client_id: $(this).data('client'),
                    _token: window.Laravel.csrfToken
                },
                type: 'post',
                dataType: 'json',
                success: credMsg,
                complete: function () {
                    row.remove();
                }
            });
        }

    }).on('click', '.note-list .delete-btn', function () {
        if (confirm('Are you sure you want to delete this note?')) {
            var row = $(this).closest('tr');
            $.ajax({
                url: '/client-notes/delete/' + $(this).data('id'),
                data: {client_id: $(this).data('client'), _token: window.Laravel.csrfToken},
                type: 'post',
                dataType: 'json',
                success: function (response) {
                    var noteMsgBox = $('.note-list').find('.msg-box');
                    noteMsgBox.slideDown();
                    if (response.success) {
                        noteMsgBox.addClass('alert-success').removeClass('alert-error');
                    } else {
                        noteMsgBox.addClass('alert-error').removeClass('alert-success');
                    }
                    noteMsgBox.text(response.message);

                    setTimeout(function () {
                        noteMsgBox.slideUp();
                    }, 4000);
                },
                complete: function () {
                    row.remove();
                }
            });
        }
        return false;

    }).on('click', 'a.delete-link', function () {
        return confirm('Are you sure you want to delete: ' + $(this).data('title') + '? This will also remove it from any clients.');

    }).on('click', '.modal-checkbox-field .btn-primary', function () {
        var modalField = $(this).closest('.modal'),
            resultField = $('.select-multiple-results[data-target="#' + modalField.attr('id') + '"]');

        resultField.html('');
        modalField.find('input[type="checkbox"]:checked').each(function () {
            var id = $(this).attr('value'),
                color = $(this).data('color'),
                text = $(this).next('span').text();

            resultField.append('<span style="background-color: ' + color + '">' + text + '<button class="remove-cat" data-id="' + id + '"><i class="fa fa-close"></i></button></span>');
        });
        modalField.modal('hide');

    }).on('click', '.select-multiple-results .remove-cat', function () {
        var id = $(this).data('id'),
            modalField = $($(this).closest('.select-multiple-results').data('target'));

        modalField.find('input[type="checkbox"]:checked').each(function () {
            if ($(this).attr('value') == id) {
                $(this).prop('checked', false);
            }
        });
        $(this).closest('span').remove();

    }).on('hidden.bs.modal', '.modal-checkbox-field', function (e) {
        var id = $(this).attr('id'),
            cbx = $(this).find('input[type="checkbox"]'),
            catIds = [];

        $('.select-multiple-results[data-target="#' + id + '"]').find('span').each(function () {
            catIds.push($(this).find('button').data('id').toString());
        });

        cbx.each(function () {
            if (catIds.indexOf($(this).attr('value')) > -1) {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }
        });

    }).on('change', 'input.tab-radio', function () {
        $(this).tab('show');

    }).on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;

        if (e.target.hash === '#credentials') {
            var txtArs = credentialList.find('textarea');

            setTimeout(function () {
                txtArs.each(function () {
                    $(this).height($(this)[0].scrollHeight);
                })
            }, 300);
        }

    }).on('change', 'select.node_client_id', function () {
        var sel = $(this),
            val = sel.val(),
            id = sel.data('id');

        if (val === 'none') {
            val = null;
        }
        sel.siblings('i.fa').remove();
        sel.after('<i class="fa fa-circle-o-notch fa-pulse"></i>');

        $.ajax({
            url: '/nodes/update-node-client/' + id,
            type: 'post',
            data: {client_id: val, _token: window.Laravel.csrfToken},
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    sel.siblings('i.fa').toggleClass('fa-circle-o-notch fa-pulse fa-check success');
                    console.log(response.message);
                } else {
                    sel.siblings('i.fa').toggleClass('fa-circle-o-notch fa-pulse fa-close error');
                    console.log(response.message);
                }
                setTimeout(function () {
                    sel.siblings('i.fa').remove();
                }, 5000);
            }
        });
    }).on('show.bs.modal', '.client-contact-modal', function (e) {
        $(this).find('#contact-type').selectize({
            persist: false,
            maxItems: null,
            options: $(this).data('options'),
            create: function (input) {
                return {text: input, value: input};
            }
        });
    });

    function getCampaignItem(campaign_uuid) {

        $.ajax({
            url: "/campaigns/ajax_run_get_item?campaign_uuid=" + campaign_uuid,
            dataType: "JSON",
            success: function (json) {
                console.log(json);

                var campaign = json['campaign'];
                var item = json['item'];

                if (campaign.rollup_total) {
                    console.log('setting the rollup total...');
                    $('.total_records').fadeOut().html(campaign.rollup_total).fadeIn();
                } else {
                    $('.total_records').fadeOut().html(0).fadeIn();
                }

                if (campaign.rollup_completed) {
                    console.log('setting the rollup completed...');
                    $('.completed_records').fadeOut().html(campaign.rollup_completed).fadeIn();
                } else {
                    $('.completed_records').fadeOut().html(0).fadeIn();
                }

                var pct = Math.round((campaign.rollup_completed * 100) / campaign.rollup_total);

                $('.complete_percent').fadeOut().html(pct + '%').fadeIn();

                $('.progress-bar').css('width', pct + '%');

                if (!item.uuid) {
                    $('.campaign_msg').html('No work found - campaign may be closing soon!').show();
                    $('.begin_p2p_btn').val($('.begin_p2p_btn').attr('data-value')).prop('disabled', false);
                    $('.p2p_action').hide();
                    $('.intro').show();
                } else {
                    $('.to_phone').text(item.phone);
                    $('.to_name').text(item.first_name + ' ' + item.last_name);
                    $('.text_message').html(item.content_sent);
                    $('.uuid').html(item.uuid);

                    $('.send_txt_btn').attr('data-uuid', item.uuid);
                    $('.send_txt_btn').attr('data-campaign_uuid', campaign.uuid);
                    $('.cancel_hold_btn').attr('data-uuid', item.uuid);
                    $('.cancel_hold_btn').attr('data-campaign_uuid', campaign.uuid);

                    $('.campaign_msg').hide();
                    $('.intro').hide();
                    $('.p2p_action').show();
                }

            }
        });

    }

    function getCampaignTotals(campaign_uuid) {

        $.ajax({
            url: "/campaigns/ajax_run_get_totals?campaign_uuid=" + campaign_uuid,
            dataType: "JSON",
            success: function (json) {
                var campaign = json['campaign'];
                var item = json['item'];

                if (campaign.rollup_total) {
                    $('.total_records').fadeOut().html(campaign.rollup_total).fadeIn();
                } else {
                    $('.total_records').fadeOut().html(0).fadeIn();
                }

                if (campaign.rollup_completed) {
                    $('.completed_records').fadeOut().html(campaign.rollup_completed).fadeIn();
                } else {
                    $('.completed_records').fadeOut().html(0).fadeIn();
                }

                var pct = Math.round((campaign.rollup_completed * 100) / campaign.rollup_total);

                $('.complete_percent').fadeOut().html(pct + '%').fadeIn();

                $('.progress-bar').css('width', pct + '%');

                getTotals = setTimeout(function () {
                    getCampaignTotals(campaign_uuid);
                }, 5000);

            }
        });

    }

    function getCampaignUpdates(campaign_id) {
        console.log('getCampaignUpdates() for campaign_id = ' + campaign_id);

        $.ajax({
            url: "/campaigns/ajax_get_campaign_work_data?campaign_id=" + campaign_id,
            dataType: "JSON",
            success: function (json) {
                console.log(json);

                var recent = json['recent'];
                var campaign = json['campaign'];

                if (campaign.rollup_total) {
                    console.log('setting the rollup total...');
                    $('.total_records').fadeOut().html(campaign.rollup_total).fadeIn();
                } else {
                    $('.total_records').fadeOut().html(0).fadeIn();
                }

                if (campaign.rollup_completed) {
                    console.log('setting the rollup completed...');
                    $('.completed_records').fadeOut().html(campaign.rollup_completed).fadeIn();
                } else {
                    $('.completed_records').fadeOut().html(0).fadeIn();
                }

                var pct = Math.round((campaign.rollup_completed * 100) / campaign.rollup_total);

                $('.complete_percent').fadeOut().html(pct + '%').fadeIn();

                $('.progress-bar').css('width', pct + '%');

                setTimeout(function () {
                    getCampaignUpdates(campaign_id);
                }, 5000);

            }
        });


    }

    $('.alert').on('click', function (e) {
        $(this).fadeOut();
    });

    $(document).on('click', '.confirm', function (e) {
        let c = confirm($(this).attr('data-msg'));

        if (!c) {
            return false;
        }
    });
});

