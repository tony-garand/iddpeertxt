@extends('layouts.app')

@section('title', ' - Custom Labels')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('shared.messages')

                <form id="frm" class="form-horizontal" method="post" action="/customLabels/update/{{ $label->id }}">
                    {{ csrf_field() }}

                    @if (Auth::user()->hasRole(['administrator']))
                        <div class="form-group">
                            <label for="company_id" class="col-sm-3 control-label">Company:</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="company_id" name="company_id">
                                    @foreach ($companies as $company)
                                        <option {{ (($company->id == $label->company_id) ? " selected " : "") }} value="{{ $company->id }}">{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="label" class="col-sm-3 control-label">Label:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="label" name="label" placeholder="" value="{{ $label->label }}" />
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
@endsection