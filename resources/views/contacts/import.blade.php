@extends('layouts.app')

@section('title', ' - Import Contacts')

@push('scripts')
    <script src="{{ asset('js/pages/contacts.js') }}"></script>
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('shared.messages')

                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                @for($i = 1; $i <= $columnCount; $i++)
                                    <th>Column {{ $i }}</th>
                                @endfor
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($sample as $line)
                                <tr>
                                    @foreach ($line as $field=>$value)
                                        <td data-column="{{ $loop->iteration }}">{{ $value }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <form id="importAssign" method="post" action="/contacts/importFinish">
                            {{ csrf_field() }}
                            <input type="hidden" id="filename" name="filename" value="{{ $filename }}"/>
                            <input type="hidden" id="columnCount" name="columnCount" value="{{ $columnCount }}"/>
                            <input type="hidden" id="companyId" name="companyId" value="{{ $companyId }}"/>
                            <input type="hidden" id="tags" name="tags" value="{{ $tags }}"/>
                            <table class="table table-striped" id="build" data-filename="{{ $filename }}"
                                   data-column-count="{{ $columnCount }}">
                                <tbody>
                                @for($i = 1; $i <= $columnCount; $i++)
                                    <tr data-column="{{ $i }}" class="fieldColumn">
                                        <td>Column {{ $i }}</td>
                                        <td>
                                            <select id="contact_field_{{ $i }}" name="contact_field_{{ $i }}">
                                                @foreach ($contactFields as $key=>$value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select id="custom_field_{{ $i }}" name="custom_field_{{ $i }}">
                                                @foreach ($customFields as $key=>$value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" id="new_field_{{ $i }}" name="new_field_{{ $i }}" disabled/>
                                        </td>
                                    </tr>
                                @endfor
                                </tbody>
                            </table>

                            <button type="submit" class="btn btn-primary pull-right" id="finish" name="finish">Finish</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
