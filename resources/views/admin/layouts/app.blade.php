<html lang="ru" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Admin panel GoodFood - @yield('title', '')</title>
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset("admin/images/brand/favicon.ico") }}" />
    <link id="style" href="{{ asset("admin/plugins/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" />

    <link href="{{ asset("admin/css/style.css") }}" rel="stylesheet" />
    <link href="{{ asset("admin/css/dark-style.css") }}" rel="stylesheet" />
    <link href="{{ asset("admin/css/transparent-style.css") }}" rel="stylesheet" />
    <link href="{{ asset("admin/css/skin-modes.css") }}" rel="stylesheet" />

    <link href="{{ asset("admin/css/icons.css") }}" rel="stylesheet" />

    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{ asset("admin/colors/color1.css") }}" />

    <link href="{{ asset("admin/css/tailwind-2.0.1.min.css") }}" rel="stylesheet" />
{{--    <link href="https://unpkg.com/tailwindcss@1.8.1/dist/tailwind.min.css" rel="stylesheet">--}}
</head>

<body class="app sidebar-mini ltr light-mode">
    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="{{ asset("admin/images/loader.svg") }}" class="loader-img" alt="Loader">
    </div>
    <!-- /GLOBAL-LOADER -->

    <div class="page">
        <div class="page-main">
            @component('admin.components.common.header_navbar')@endcomponent
            @component('admin.components.common.header_sidebar')@endcomponent

            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <div class="main-container container-fluid">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>

        @component('admin.components.common.right_sidebar')@endcomponent

        @component('admin.components.common.country_selector_modal')@endcomponent

        @component('admin.components.common.footer')@endcomponent
    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="{{ asset("admin/js/jquery.min.js") }}"></script>

    <!-- BOOTSTRAP JS -->
    <script src="{{ asset("admin/plugins/bootstrap/js/popper.min.js") }}"></script>
    <script src="{{ asset("admin/plugins/bootstrap/js/bootstrap.min.js") }}"></script>

    <!-- SPARKLINE JS-->
    <script src="{{ asset("admin/js/jquery.sparkline.min.js") }}"></script>

    <!-- Sticky js -->
    <script src="{{ asset("admin/js/sticky.js") }}"></script>

    <!-- CHART-CIRCLE JS-->
    <script src="{{ asset("admin/js/circle-progress.min.js") }}"></script>

    <!-- PIETY CHART JS-->
    <script src="{{ asset("admin/plugins/peitychart/jquery.peity.min.js") }}"></script>
    <script src="{{ asset("admin/plugins/peitychart/peitychart.init.js") }}"></script>

    <!-- SIDEBAR JS -->
    <script src="{{ asset("admin/plugins/sidebar/sidebar.js") }}"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="{{ asset("admin/plugins/p-scroll/perfect-scrollbar.js") }}"></script>
    <script src="{{ asset("admin/plugins/p-scroll/pscroll.js") }}"></script>
    <script src="{{ asset("admin/plugins/p-scroll/pscroll-1.js") }}"></script>

    <!-- INTERNAL CHARTJS CHART JS-->
    <script src="{{ asset("admin/plugins/chart/Chart.bundle.js") }}"></script>
    <script src="{{ asset("admin/plugins/chart/rounded-barchart.js") }}"></script>
    <script src="{{ asset("admin/plugins/chart/utils.js") }}"></script>

    <!-- INTERNAL SELECT2 JS -->
    <script src="{{ asset("admin/plugins/select2/select2.full.min.js") }}"></script>
    <script src="{{ asset("admin/js/select2.js") }}"></script>

    <!-- INTERNAL Data tables js-->
    <script src="{{ asset("admin/plugins/datatable/js/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset("admin/plugins/datatable/js/dataTables.bootstrap5.js") }}"></script>
    <script src="{{ asset("admin/plugins/datatable/dataTables.responsive.min.js") }}"></script>

    <!-- INTERNAL APEXCHART JS -->
    <script src="{{ asset("admin/js/apexcharts.js") }}"></script>
    <script src="{{ asset("admin/plugins/apexchart/irregular-data-series.js") }}"></script>

    <!-- INTERNAL Flot JS -->
    <script src="{{ asset("admin/plugins/flot/jquery.flot.js") }}"></script>
    <script src="{{ asset("admin/plugins/flot/jquery.flot.fillbetween.js") }}"></script>
    <script src="{{ asset("admin/plugins/flot/chart.flot.sampledata.js") }}"></script>
    <script src="{{ asset("admin/plugins/flot/dashboard.sampledata.js") }}"></script>

    <!-- INTERNAL Vector js -->
    <script src="{{ asset("admin/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js") }}"></script>
    <script src="{{ asset("admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js") }}"></script>

    <!-- SIDE-MENU JS-->
    <script src="{{ asset("admin/plugins/sidemenu/sidemenu.js") }}"></script>

    <!-- TypeHead js -->
    <script src="{{ asset("admin/plugins/bootstrap5-typehead/autocomplete.js") }}"></script>
    <script src="{{ asset("admin/js/typehead.js") }}"></script>

    <!-- INTERNAL INDEX JS -->
    <script src="{{ asset("admin/js/index1.js") }}"></script>

    <!-- Color Theme js -->
    <script src="{{ asset("admin/js/themeColors.js") }}"></script>

    <!-- CUSTOM JS -->
    <script src="{{ asset("admin/js/custom.js") }}"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
