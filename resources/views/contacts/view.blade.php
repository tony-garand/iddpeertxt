@extends('layouts.app')

@section('title', ' - Contacts - #' . $contact->id)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @include('shared.messages')

                <div class="actions">
                    <div class="pull-left info">
                        <h1><a href="/contacts">Contacts</a> &gt; View Contact</h1>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">

                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#editcontact"><i class="fa fa-pencil-square-o"
                                                                                           aria-hidden="true"></i>
                                    &nbsp; Edit Contact</a></li>
                            <li><a data-toggle="tab" href="#linkedcampaigns"><i class="fa fa-link"
                                                                                aria-hidden="true"></i> &nbsp; Linked
                                    Campaigns</a></li>
                            <li><a data-toggle="tab" href="#auditlog"><i class="fa fa-list" aria-hidden="true"></i>
                                    &nbsp; Audit Log</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="editcontact" class="tab-pane fade in active">
                                @include('shared.forms.edit_contact')
                            </div>
                            <div id="linkedcampaigns" class="tab-pane fade">
                                <table class="table table-striped" id="campaigns">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Updated</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($campaigns as $campaign)
                                        <tr>
                                            <td>{{ $campaign->Campaign->campaign_name }}</td>
                                            <td>{{ campaign_status($campaign->Campaign->campaign_status) }}</td>
                                            <td>{{ $campaign->Campaign->updated_at->format('m-d-Y') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                @push ('scripts')
                                    <script>
                                        $(function () {
                                            $(document).ready(function () {
                                                $('table#campaigns').dataTable({
                                                    pageLength: 10,
                                                    order: [[2, 'desc']],
                                                    dom: '<"top">rt<"bottom"p><"clear">'
                                                });
                                            });
                                        });
                                    </script>
                                @endpush
                            </div>
                            <div id="auditlog" class="tab-pane fade">
                                <table class="table table-striped" id="audits">
                                    <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>By</th>
                                        <th>Updated</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($contact->ActionLog as $audit)
                                        <tr>
                                            <td>{{ $audit->action }}</td>
                                            <td>{{ @$audit->User->name }}</td>
                                            <td>{{ $audit->updated_at }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                @push ('scripts')
                                    <script>
                                        $(function () {
                                            $(document).ready(function () {
                                                $('table#audits').dataTable({
                                                    pageLength: 10,
                                                    order: [[2, 'desc']],
                                                    dom: '<"top">rt<"bottom"p><"clear">'
                                                });
                                            });
                                        });
                                    </script>
                                @endpush
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('input[name="tags"]').amsifySuggestags();


        });
    </script>
@endpush
