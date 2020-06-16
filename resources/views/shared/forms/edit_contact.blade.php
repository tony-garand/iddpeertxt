<br/>
<form id="frm" class="form-horizontal" method="post" action="/contacts/update/{{ $contact->id }}">
    {{ csrf_field() }}

    <div class="form-group">
        <label for="verified" class="col-sm-3 control-label">Verified:</label>
        <div class="col-sm-4" style="padding-top: 7px;">
            {!! contact_verified($contact->verified_phone) !!}
            &nbsp;&nbsp;<a href="javascript:;" data-toggle="tooltip"
                           title="Verified records have been validated as mobile phone numbers.">What's this?</a>
        </div>
        <div class="col-sm-4">
            <button class="btn btn-success verify_now" type="button" data-contact_id="{{ $contact->id }}">Verify Now</button>
        </div>

    </div>

    @if (Auth::user()->hasRole(['administrator']))
        <div class="form-group">
            <label for="company_id" class="col-sm-3 control-label">Company:</label>
            <div class="col-sm-8">
                <select class="form-control" id="company_id" name="company_id">
                    @foreach ($companies as $company)
                        <option {{ (($company->id == $contact->company_id) ? " selected " : "") }} value="{{ $company->id }}">{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <div class="form-group">
        <label for="status" class="col-sm-3 control-label">Status:</label>
        <div class="col-sm-8">
            <select class="form-control" id="status" name="status">
                <option {{ (($contact->status == 1) ? " selected " : "") }} value="1">Active</option>
                <option {{ (($contact->status == 0) ? " selected " : "") }} value="0">Inactive</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="first_name" class="col-sm-3 control-label">First Name:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="first_name" name="first_name"
                   value="{{ $contact->first_name }}"/>
        </div>
    </div>

    <div class="form-group">
        <label for="last_name" class="col-sm-3 control-label">Last Name:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $contact->last_name }}"/>
        </div>
    </div>

    <div class="form-group">
        <label for="phone" class="col-sm-3 control-label">Phone:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $contact->phone }}"/>
        </div>
    </div>

    <div class="form-group">
        <label for="email" class="col-sm-3 control-label">Email:</label>
        <div class="col-sm-8">
            <input type="email" class="form-control" id="email" name="email" value="{{ $contact->email }}"/>
        </div>
    </div>

    <div class="form-group">
        <label for="address1" class="col-sm-3 control-label">Address 1:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="address1" name="address1" value="{{ $contact->address1 }}"/>
        </div>
    </div>

    <div class="form-group">
        <label for="address2" class="col-sm-3 control-label">Address 2:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="address2" name="address2" value="{{ $contact->address2 }}"/>
        </div>
    </div>

    <div class="form-group">
        <label for="city" class="col-sm-3 control-label">City:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="city" name="city" value="{{ $contact->city }}"/>
        </div>
    </div>

    <div class="form-group">
        <label for="state" class="col-sm-3 control-label">State:</label>
        <div class="col-sm-8">
            <select class="form-control" id="state" name="state">
                <option value=""></option>
                @foreach ($states as $k=>$v)
                    <option {{ (($k == $contact->state) ? " selected " : "") }} value="{{ $k }}">{{ $k }}
                        - {{ $v }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="zip" class="col-sm-3 control-label">Zip:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="zip" name="zip" value="{{ $contact->zip }}"/>
        </div>
    </div>

    <div class="form-group">
        <label for="url" class="col-sm-3 control-label">URL:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="url" name="url" value="{{ $contact->url }}"/>
        </div>
    </div>

    <div class="form-group">
        <label for="tags" class="col-sm-3 control-label">Tags:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="tags" name="tags" value="{{ $contact->tagsToString() }}"/>
        </div>
    </div>

    <div class="form-group">
        <label for="created_at" class="col-sm-3 control-label">Created:</label>
        <div class="col-sm-8" style="padding-top: 7px;">
            {{ $contact->created_at }}
        </div>
    </div>

    <div class="form-group">
        <label for="updated_at" class="col-sm-3 control-label">Updated:</label>
        <div class="col-sm-8" style="padding-top: 7px;">
            {{ $contact->updated_at }}
        </div>
    </div>

    @if (Auth::user()->hasRole(['administrator']))
        @if ($contact->deleted_at)
            <div class="form-group">
                <label for="deleted_at" class="col-sm-3 control-label">Deleted:</label>
                <div class="col-sm-8" style="padding-top: 7px;">
                    {{ $contact->deleted_at }}
                </div>
            </div>
        @endif
    @endif

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button id="add_btn" type="submit" class="btn btn-primary">Update</button>
            @if (Auth::user()->hasRole(['administrator']))
                @if ($contact->deleted_at)
                    <button data-href="/contacts/undelete/{{ $contact->id }}" id="delete_btn" type="button"
                            class="btn btn-delete">Undelete Contact
                    </button>
                @else
                    <button data-href="/contacts/delete/{{ $contact->id }}" id="delete_btn" type="button"
                            class="btn btn-delete">Delete Contact
                    </button>
                @endif
            @else
                <button data-href="/contacts/delete/{{ $contact->id }}" id="delete_btn" type="button"
                        class="btn btn-delete">Delete Contact
                </button>
            @endif
        </div>
    </div>

</form>

@push('scripts')
    <script>
        $(document).ready(function () {
            $('input[name="tags"]').amsifySuggestags();
        });
    </script>
@endpush
