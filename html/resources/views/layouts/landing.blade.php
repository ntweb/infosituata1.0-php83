<!doctype html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />

    <link rel="icon" href="{{ url('favicon.png') }}" type="image/png" />

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="stylesheet" href="{{ url('assets/css/base.css') }}">

    <style>
        img {
            width: 100% !important;
        }
    </style>

</head>

<body>
<div class="app-container app-theme-white">
    <div class="app-container">
        <div class="h-100 bg-animation">
            <div class="d-flex h-100 justify-content-center">
                <div class="mx-auto col-md-8">
                    <div class="w-100 mx-auto pt-4">

                        @yield('content')

                    </div>
                    <div class="text-center text-white opacity-8 mt-3">Copyright © {{ config('app.name') }} {{ date('Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--SCRIPTS INCLUDES-->

<!--CORE-->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/metismenu"></script>
<script src="{{ url('assets/js/scripts-init/app.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/demo.js') }}"></script>

<!--CHARTS-->

<!--Apex Charts-->
<script src="{{ url('assets/js/vendors/charts/apex-charts.js') }}"></script>

<script src="{{ url('assets/js/scripts-init/charts/apex-charts.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/charts/apex-series.js') }}"></script>

<!--Sparklines-->
<script src="{{ url('assets/js/vendors/charts/charts-sparklines.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/charts/charts-sparklines.js') }}"></script>

<!--Chart.js-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script src="{{ url('assets/js/scripts-init/charts/chartsjs-utils.js') }}"></script>

<!--FORMS-->

<!--Clipboard-->
<script src="{{ url('assets/js/vendors/form-components/clipboard.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/form-components/clipboard.js') }}"></script>

<!--Datepickers-->
<script src="{{ url('assets/js/vendors/form-components/datepicker.js') }}"></script>
<script src="{{ url('assets/js/vendors/form-components/daterangepicker.js') }}"></script>
<script src="{{ url('assets/js/vendors/form-components/moment.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/form-components/datepicker.js') }}"></script>

<!--Multiselect-->
<script src="{{ url('assets/js/vendors/form-components/bootstrap-multiselect.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="{{ url('assets/js/scripts-init/form-components/input-select.js') }}"></script>

<!--Form Validation-->
<script src="{{ url('assets/js/vendors/form-components/form-validation.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/form-components/form-validation.js') }}"></script>

<!--Form Wizard-->
<script src="{{ url('assets/js/vendors/form-components/form-wizard.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/form-components/form-wizard.js') }}"></script>

<!--Input Mask-->
<script src="{{ url('assets/js/vendors/form-components/input-mask.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/form-components/input-mask.js') }}"></script>

<!--RangeSlider-->
<script src="{{ url('assets/js/vendors/form-components/wnumb.js') }}"></script>
<script src="{{ url('assets/js/vendors/form-components/range-slider.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/form-components/range-slider.js') }}"></script>

<!--Textarea Autosize-->
<script src="{{ url('assets/js/vendors/form-components/textarea-autosize.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/form-components/textarea-autosize.js') }}"></script>

<!--Toggle Switch -->
<script src="{{ url('assets/js/vendors/form-components/toggle-switch.js') }}"></script>


<!--COMPONENTS-->

<!--BlockUI -->
<script src="{{ url('assets/js/vendors/blockui.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/blockui.js') }}"></script>

<!--Calendar -->
<script src="{{ url('assets/js/vendors/calendar.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/calendar.js') }}"></script>

<!--Slick Carousel -->
<script src="{{ url('assets/js/vendors/carousel-slider.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/carousel-slider.js') }}"></script>

<!--Circle Progress -->
<script src="{{ url('assets/js/vendors/circle-progress.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/circle-progress.js') }}"></script>

<!--CountUp -->
<script src="{{ url('assets/js/vendors/count-up.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/count-up.js') }}"></script>

<!--Cropper -->
<script src="{{ url('assets/js/vendors/cropper.js') }}"></script>
<script src="{{ url('assets/js/vendors/jquery-cropper.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/image-crop.js') }}"></script>

<!--Maps -->
<script src="{{ url('assets/js/vendors/gmaps.js') }}"></script>
<script src="{{ url('assets/js/vendors/jvectormap.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/maps-word-map.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/maps.js') }}"></script>

<!--Guided Tours -->
<script src="{{ url('assets/js/vendors/guided-tours.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/guided-tours.js') }}"></script>

<!--Ladda Loading Buttons -->
<script src="{{ url('assets/js/vendors/ladda-loading.js') }}"></script>
<script src="{{ url('assets/js/vendors/spin.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/ladda-loading.js') }}"></script>

<!--Rating -->
<script src="{{ url('assets/js/vendors/rating.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/rating.js') }}"></script>

<!--Perfect Scrollbar -->
<script src="{{ url('assets/js/vendors/scrollbar.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/scrollbar.js') }}"></script>

<!--Toastr-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>
<script src="{{ url('assets/js/scripts-init/toastr.js') }}"></script>

<!--SweetAlert2-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="{{ url('assets/js/scripts-init/sweet-alerts.js') }}"></script>

<!--Tree View -->
<script src="{{ url('assets/js/vendors/treeview.js') }}"></script>
<script src="{{ url('assets/js/scripts-init/treeview.js') }}"></script>


<!--TABLES -->
<!--DataTables-->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs4@1.10.19/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js" crossorigin="anonymous"></script>

<!--Bootstrap Tables-->
<script src="{{ url('assets/js/vendors/tables.js') }}"></script>
<script src="{{ url('assets/js/vendors/summernote/dist/summernote.min.js') }}"></script>
<script src="{{ url('assets/tags-input/dist/jquery.tagsinput.min.js') }}"></script>

<!--Tables Init-->
<script src="{{ url('assets/js/scripts-init/tables.js') }}"></script>
<script src="{{ url('assets/js/vendors/FitVids/jquery.fitvids.js') }}"></script>
<script src="{{ url('js/plugins.js?v=').time() }}"></script>

</body>
</html>
