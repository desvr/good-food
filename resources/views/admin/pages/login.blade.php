<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sash – Bootstrap 5  Admin & Dashboard Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset("admin/images/brand/favicon.ico") }}" />

    <!-- TITLE -->
    <title>Sash – Bootstrap 5 Admin & Dashboard Template</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset("admin/plugins/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{ asset("admin/css/style.css") }}" rel="stylesheet" />
    <link href="{{ asset("admin/css/dark-style.css") }}" rel="stylesheet" />
    <link href="{{ asset("admin/css/transparent-style.css") }}" rel="stylesheet">
    <link href="{{ asset("admin/css/skin-modes.css") }}" rel="stylesheet" />

    <!--- FONT-ICONS CSS -->
    <link href="{{ asset("admin/css/icons.css") }}" rel="stylesheet" />

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{ asset("admin/colors/color1.css") }}" />

</head>

<body class="app sidebar-mini ltr login-img">

<!-- BACKGROUND-IMAGE -->
<div class="">

    <!-- GLOABAL LOADER -->
    <div id="global-loader">
        <img src="{{ asset("admin/images/loader.svg") }}" class="loader-img" alt="Loader">
    </div>
    <!-- /GLOABAL LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="">

            <!-- CONTAINER OPEN -->
            <div class="col col-login mx-auto mt-7">
                <div class="text-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset("admin/images/brand/logo-white.png") }}" class="header-brand-img" alt="">
                    </a>
                </div>
            </div>

            <div class="container-login100">
                <div class="wrap-login100 p-6">
                    <div class="login100-form validate-form">
                            <span class="login100-form-title pb-3">
                                С возвращением!
                            </span>
                        <div class="panel panel-primary">
                            <div class="panel-body tabs-menu-body p-0 pt-5">
                                <form method="POST" action="{{ route('admin.login_process') }}">
                                    @csrf

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            @foreach($errors->all() as $error)
                                                {{ $error }} <br>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="wrap-input100 validate-input input-group">
                                        <div class="input-group-text bg-white text-muted px-4">
                                            <i class="zmdi zmdi-email text-muted" aria-hidden="true"></i>
                                        </div>
                                        <input name="email" class="input100 border-start-0 form-control ms-0 @error('email') is-invalid @enderror" type="email" placeholder="Email" value="admin@example.com">
                                    </div>

                                    <div class="wrap-input100 validate-input input-group">
                                        <div class="input-group-text bg-white text-muted px-4">
                                            <i class="zmdi zmdi-key text-muted" aria-hidden="true"></i>
                                        </div>
                                        <input name="password" class="input100 border-start-0 form-control ms-0 @error('password') is-invalid @enderror" type="password" placeholder="Password" value="admin">
                                    </div>

                                    <div class="container-login100-form-btn">
                                        <button type="submit" class="login100-form-btn btn-primary">
                                            Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- CONTAINER CLOSED -->
        </div>
    </div>
    <!-- End PAGE -->

</div>
<!-- BACKGROUND-IMAGE CLOSED -->

<!-- JQUERY JS -->
<script src="{{ asset("admin/js/jquery.min.js") }}"></script>

<!-- BOOTSTRAP JS -->
<script src="{{ asset("admin/plugins/bootstrap/js/popper.min.js") }}"></script>
<script src="{{ asset("admin/plugins/bootstrap/js/bootstrap.min.js") }}"></script>

<!-- SHOW PASSWORD JS -->
<script src="{{ asset("admin/js/show-password.min.js") }}"></script>

<!-- GENERATE OTP JS -->
<script src="{{ asset("admin/js/generate-otp.js") }}"></script>

<!-- Perfect SCROLLBAR JS-->
<script src="{{ asset("admin/plugins/p-scroll/perfect-scrollbar.js") }}"></script>

<!-- Color Theme js -->
<script src="{{ asset("admin/js/themeColors.js") }}"></script>

<!-- CUSTOM JS -->
<script src="{{ asset("admin/js/custom.js") }}"></script>


</body>

</html>
