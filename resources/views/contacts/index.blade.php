@extends('layouts.app')

@section('title', ' - Contacts')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @include('shared.messages')

                <div class="actions">
                    <div class="pull-left info">
                        <h1>Contacts</h1>
                    </div>
                    <div class="pull-right action">
                        <a href="javascript:;" class="btn btn-primary small_btn" data-toggle="modal"
                           data-target="#addContact">Add New Contact</a>
                        <a href="javascript:;" class="btn btn-primary small_btn" data-toggle="modal"
                           data-target="#uploadContacts">Upload Contacts</a>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <select id="tagFilter" name="tagFilter" class="select" data-placeholder="Filter tags..."
                                    multiple="" style="width: 100%;">
                                @foreach ($tags as $k=>$tag)
                                    <option value="{{ $tag }}">{{ $tag }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-10" style="margin-top: 10px;">
                            <input type="text" id="newTag" name="newTag" placeholder="Add new tag..."
                                   class="form-control"/>
                        </div>
                        <div class="col-md-2" style="margin-top: 10px;">
                            <button class="form-control btm btn-primary" id="addTag">Add Tag</button>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">

                        <table class="table table-striped" id="contacts">
                            <thead>
                            <tr>
                                <th>ID</th>
                                @if (Auth::user()->hasRole(['administrator']))
                                    <th>Company</th>
                                @endif
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Zip</th>
                                <th>Created Date</th>
                                <th>Modified Date</th>
                            </tr>
                            </thead>

                            @push ('scripts')
                                <script>
                                    $(function () {
                                        $(document).ready(function () {
                                            let options = $.extend(true, {}, $.fn.dataTable.defaults, {
                                                processing: true,
                                                serverSide: true,
                                                responsive: true,
                                                pageLength: 10,   // always only show 10 records at a time
                                                ajax: {
                                                    url: '{!! route('contacts.index.table') !!}',
                                                    data: function (d) {
                                                        d.tagFilter = $('#tagFilter').val()
                                                    }
                                                },

												columns: [
													{data: 'id', name: 'id'},
													@if (Auth::user()->hasRole(['administrator']))
                                                    {
                                                        data: 'company.company_name',
                                                        render: function (data, display, row) {
                                                            var parentCompany = "";

                                                            if (row.company && row.company.parent_company) {
                                                                parentCompany = row.company.parent_company.company_name + " -> "
                                                            }
                                                            return parentCompany + data;
                                                        },

                                                        name: 'Company.company_name'
                                                    },
													@endif
													{data: 'first_name', name: 'first_name'},
													{data: 'last_name', name: 'last_name'},
                                                    {
                                                        data: 'phone', render: function (data, display, row) {
                                                            var icon = "";
                                                            if (row.verified_phone == 2) {
                                                                icon = "<i class='fa fa-check' title='Verified phone'></i>";
                                                            }
                                                            return data + icon;
                                                        }, name: 'phone'
                                                    },
													{data: 'email', name: 'email'},
													{data: 'city', name: 'city'},
													{data: 'state', name: 'state'},
													{data: 'zip', name: 'zip'},
													{data: 'created_at' , render:function(data){
													return moment(data).format('MM-DD-YYYY');
													}, name: 'created_at'},
													{data: 'updated_at', render:function(data){
													return moment(data).format('MM-DD-YYYY');
													}, name: 'updated_at'}
												],

                                                order: [[3, 'desc']],   // double brackets so default sort is displayed correctly

                                                oLanguage: {
                                                    'sSearch': 'Filter:'
                                                },
                                                dom: '<"top"lf>rti<"bottom"p><"clear">'
                                            });

                                            let oTable = $('#contacts').DataTable(options);

                                            $('#tagFilter').chosen()
                                                .on('change', function (e, v) {
                                                    verificationButtonToggle();
                                                    oTable.draw();
                                                });

                                            $('#addTag').on('click', function (e) {
                                                let newTag = $('#newTag').val();

                                                if (newTag === "") {
                                                    alert('Enter a new tag first');
                                                    $('#newTag').focus();
                                                } else {

                                                    $.getJSON("{!! route('contacts.addTag') !!}", {
                                                        'filters': $('#tagFilter').val(),
                                                        'newTag': newTag
                                                    }, function (data) {
                                                        if (data.result === true) {
                                                            location.reload(true);
                                                        } else {
                                                            alert('Error adding new tag!');
                                                        }
                                                    });
                                                }

                                            });
                                        });


                                        function verificationButtonToggle() {
                                            var tagValues = $('#tagFilter').val();
                                            if (tagValues.length > 0) {
                                                $(".verify_now_list").show();
                                            } else {
                                                $(".verify_now_list").hide();
                                            }
                                        }
                                    });
                                </script>
                            @endpush


                        </table>

                        <div class="col-sm-12">
                            <button class="btn btn-success verify_now_list" type="button" style="display: none;">Verify Contacts</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addContact" tabindex="-1" role="dialog" aria-labelledby="addContactLabel">
        <div class="modal-dialog modal_med" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    @include('shared.forms.add_contact')
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadContacts" tabindex="-1" role="dialog" aria-labelledby="uploadContactsLabel">
        <div class="modal-dialog modal_med" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    @include('shared.forms.import_contact')
                </div>
            </div>
        </div>
    </div>

@endsection

