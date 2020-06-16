<!DOCTYPE html>
<html lang="en" class="brand_{{ Session::get('user_brand') }}">
@include('shared.'.Session::get('user_brand').'_head')

<body>

<div id="app">
    @if (env('IS_DEV_MODE')=='1')
        <div class="warning_lbl_top">
            <div class="container">
                Warning: You're on the development server - go to <a href="https://{{ env('APP_DOMAIN') }}/"
                                                                     target="_new">Production</a>.
            </div>
        </div>
    @endif

    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
                    <center><img src="/images/{{ Session::get('user_brand') }}_logo.png" width="110"/></center>
                </a>
            </div>
            @include('shared.menu')
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @yield('hero')
            </div>
        </div>
    </div>
    @yield('content')

</div>

@include('shared.footer')

<script src="{{ mix('/js/app.js') }}"></script>
@stack('scripts')
<script>
    window.Echo.private('user.{{ auth()->user()->id }}')
        .listen(".user-test", function (e) {
            toastr.info(e.message);
        })
        .listen('.job-finished', function (e) {
            toastr.info(e.message);
        })
        .listen('.verification-finished', function (e) {
            toastr.info(e.message);
            verificationFinished(e);
        });


    function verificationFinished(e) {

        if ($("#contacts").length) {
            $("#contacts").DataTable().ajax.reload();
        } else {
            if (e.verified === 0) {
                $('.verified_phone_label').removeClass('verified').removeClass('part_verified').addClass('not_verified').text('Not Verified')
            }
            else if (e.verified === 1) {
                $('.verified_phone_label').removeClass('not_verified').removeClass('verified').addClass('part_verified').text('Phone Format Valid')
            }
            else if (e.verified === 2) {
                $('.verified_phone_label').removeClass('not_verified').removeClass('part_verified').addClass('verified').text('Mobile Number Verified')
            }
        }
        $('.verify_now').removeAttr('disabled');
        $('.verify_now_list').removeAttr('disabled');
    }
</script>
</body>
</html>
