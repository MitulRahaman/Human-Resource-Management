<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>OneUI - Bootstrap 4 Admin Template &amp; UI Framework</title>

    <meta name="description" content="OneUI - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="OneUI - Bootstrap 4 Admin Template &amp; UI Framework">
    <meta property="og:site_name" content="OneUI">
    <meta property="og:description" content="OneUI - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="{{asset('backend/media/favicons/favicon.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('backend/media/favicons/favicon-192x192.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('backend/media/favicons/apple-touch-icon-180x180.png')}}">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- Fonts and OneUI framework -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" id="css-main" href="{{asset('backend/css/oneui.min.css')}}">

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="{{asset('backend/css/themes/amethyst.min.css')}}"> -->
    <!-- END Stylesheets -->
</head>
<body>
<!-- Page Container -->
<!--
    Available classes for #page-container:

GENERIC

    'enable-cookies'                            Remembers active color theme between pages (when set through color theme helper Template._uiHandleTheme())

SIDEBAR & SIDE OVERLAY

    'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
    'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
    'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
    'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
    'sidebar-dark'                              Dark themed sidebar

    'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
    'side-overlay-o'                            Visible Side Overlay by default

    'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

    'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

HEADER

    ''                                          Static Header if no class is added
    'page-header-fixed'                         Fixed Header

HEADER STYLE

    ''                                          Light themed Header
    'page-header-dark'                          Dark themed Header

MAIN CONTENT LAYOUT

    ''                                          Full width Main Content if no class is added
    'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
    'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)
-->
<div id="page-container">

    <!-- Main Container -->
    <main id="main-container">
        <!-- Page Content -->
        <div class="hero-static d-flex align-items-center">
            <div class="w-100">
                <!-- Sign In Section -->
                <div class="bg-white">
                    <div class="content content-full">
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6 col-xl-4 py-4">
                                <!-- Header -->
                                <div class="text-center">
                                    <p class="mb-2">
                                        <i class="fa fa-2x fa-circle-notch text-primary"></i>
                                    </p>
                                    <h1 class="h4 mb-1">
                                        Change Password
                                    </h1>
                                    {{--<h2 class="h6 font-w400 text-muted mb-3">--}}
                                        {{--A perfect match for your project--}}
                                    {{--</h2>--}}
                                </div>
                                <!-- END Header -->

                                <!-- Sign In Form -->
                                <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.min.js which was auto compiled from _js/pages/op_auth_signin.js) -->
                                <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                <form class="js-validation" action="{{url('change_password')}}" method="POST" id="form" onsubmit="return validate_inputs(event)">
                                    @csrf
                                    <div class="py-3">
                                        <div class="form-group">
                                            <label>Current Password<span class="text-danger">*</span></label>
                                            <input type="password" class="form-control form-control-lg form-control-alt" id="current_password" name="current_password" placeholder="Enter Current Password" required>
                                            <span id="error_current_password" class="m-2" style="color:red;  font-size: 14px;"></span>
                                        </div>
                                        <div class="form-group">
                                            <label>New Password<span class="text-danger">*</span></label>
                                            <input type="password" minlength="5" class="form-control form-control-lg form-control-alt" id="new_password" name="new_password" placeholder="Enter New Password" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password<span class="text-danger">*</span></label>
                                            <input type="password" class="form-control form-control-lg form-control-alt" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required>
                                            <span id="error_confirm_password" minlength="5" class="m-2" style="color:red;  font-size: 14px;"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row justify-content-center mb-0">
                                        <div class="col-md-6 col-xl-5">
                                            <button type="submit" class="btn btn-block btn-primary">
                                                <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Change Password
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!-- END Sign In Form -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Sign In Section -->

                <!-- Footer -->
                <div class="font-size-sm text-center text-muted py-3">
                    <strong>Appnap</strong> &copy; <span data-toggle="year-copy"></span>
                </div>
                <!-- END Footer -->
            </div>
        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->
</div>
<!-- END Page Container -->

<!--
    OneUI JS Core

    Vital libraries and plugins used in all pages. You can choose to not include this file if you would like
    to handle those dependencies through webpack. Please check out assets/_js/main/bootstrap.js for more info.

    If you like, you could also include them separately directly from the assets/js/core folder in the following
    order. That can come in handy if you would like to include a few of them (eg jQuery) from a CDN.

    assets/js/core/jquery.min.js
    assets/js/core/bootstrap.bundle.min.js
    assets/js/core/simplebar.min.js
    assets/js/core/jquery-scrollLock.min.js
    assets/js/core/jquery.appear.min.js
    assets/js/core/js.cookie.min.js
-->
<script src="{{asset('backend/js/oneui.core.min.js')}}"></script>

<!--
    OneUI JS

    Custom functionality including Blocks/Layout API as well as other vital and optional helpers
    webpack is putting everything together at assets/_js/main/app.js
-->
<script src="{{asset('backend/js/oneui.app.min.js')}}"></script>

<!-- Page JS Plugins -->
<script src="{{asset('backend/js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>

<!-- Page JS Code -->
<script src="{{asset('backend/js/pages/op_auth_signin.min.js')}}"></script>
<script>
    function validate_inputs(e) {
        var currentPassword = $('#current_password').val();
        var newPassword = $('#new_password').val();
        var confirmPassword = $('#confirm_password').val();
        if ( currentPassword && newPassword && confirmPassword) {
            $.ajax({
                type: 'POST',
                async:false,
                url: '{{ url('change_password/validate_inputs') }}',
                data: $('#form').serialize(),
                success: function (response) {
                    var current_password_msg = response.current_password_msg;
                    var confirm_password_msg = response.confirm_password_msg;
                    var success = response.success;
                    if (!success) {
                        if (current_password_msg) {
                            document.getElementById('error_current_password').innerHTML = current_password_msg;
                        }
                        else {
                            document.getElementById('error_current_password').innerHTML = '';
                        }
                        if (confirm_password_msg) {
                            document.getElementById('error_confirm_password').innerHTML = confirm_password_msg;
                        }
                        else {
                            document.getElementById('error_confirm_password').innerHTML = '';
                        }
                        e.preventDefault();
                        return false;
                    } else {
                        return true;
                    }
                },
                error: function() {
                    e.preventDefault();
                    return false;
                }
            });
        }
    }
</script>
</body>
</html>
