<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr"
        data-theme="theme-default"
        data-template="vertical-menu-template-no-customizer">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>{{ @$title }} {{ $attributes->get('title') }} - {{ config('app.name') }}</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/css/theme-semi-dark.css') }}"  />
    <link rel="stylesheet" href="{{ asset('css/page-auth.css') }}" />
</head>
<body>

 {{$slot}}

<script src="{{ asset('vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('vendor/js/bootstrap.js') }}"></script>

@yield('scripts')
@push('scripts')
    <script>
        (function($, window, undefined) {
            $(function() {

            });
        })(jQuery, window);
    </script>
</body>
</html>