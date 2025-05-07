<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta name="description" content="" />

<title>{{ config('app.name', 'NombreSistema') }}</title>

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('storage/'.$company->logotype) }}"/>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet"/>

<!-- Icons -->
<link rel="stylesheet" href="{{ asset('System/assets/vendor/fonts/tabler-icons.css') }}" />
<link rel="stylesheet" href="{{ asset('System/assets/vendor/fonts/fontawesome.css') }}" />
<link rel="stylesheet" href="{{ asset('System/assets/vendor/fonts/flag-icons.css') }}" />

<!-- Core CSS -->
<link rel="stylesheet" href="{{ asset('System/assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
<link rel="stylesheet" href="{{ asset('System/assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
<link rel="stylesheet" href="{{ asset('System/assets/css/demo.css') }}" />
<link rel="stylesheet" href="{{ asset('System/assets/vendor/css/pages/front-page.css') }}" />
<link rel="stylesheet" href="{{ asset('System/assets/vendor/css/pages/front-page-landing.css') }}" />

<!-- Vendors CSS -->
<link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/node-waves/node-waves.css') }}" />
<link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/toastr/toastr.css') }}" />
<link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/animate-css/animate.css') }}" />
<link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/nouislider/nouislider.css') }}" />
<link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/swiper/swiper.css') }}" />
<link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />

<link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
<link rel="stylesheet" href="{{ asset('System/assets/css/custom.css') }}" />

<!-- Helpers -->
<script src="{{ asset('System/assets/vendor/js/helpers.js') }}"></script>
<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
<!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
<script src="{{ asset('System/assets/vendor/js/template-customizer.js') }}"></script>
<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
<script src="{{ asset('System/assets/js/config.js') }}"></script>
