<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-compact layout-menu-fixed" dir="ltr" data-theme="theme-default">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>{{ @$title }} {{ $attributes->get('title') }} - {{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pikaday.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/css/theme-semi-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/tempus/tempus-dominus.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <link rel="stylesheet" href="{{ asset('css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/swiper/swiper.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/lightbox.min.css') }}" />
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.3/css/dataTables.dataTables.min.css" />
    <!-- Tempus Dominus Styles -->
  

    @livewireStyles

    @if (isset($styles))
        {!! $styles !!}
    @endif

    {{-- @dd($jsData) --}}
    @if (isset($jsData))
        {!! jsVar($jsData) !!}
    @endif

    <script src="{{ asset('vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('js/config.js') }}"></script>

</head>

<body>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.base.menu')
            <div class="layout-page">
                @include('layouts.base.header')
                <div class="content-wrapper">
                    <div class="mt-3"
                         style="margin-left:20px; width: 98%;">
                        @include('includes.notifications')
                    </div>
                    {{ $slot }}
                    @include('layouts.base.footer')
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>

    <div id="footerModal"
         class="modal fade footerModal"
         tabindex="-1"
         role="dialog"
         aria-labelledby="modelTitleId"
         aria-hidden="true"></div>

    <script src="{{ asset('vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('vendor/js/menu.js') }}"></script>
    <script src="{{ asset('vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('vendor/libs/swiper/swiper.js') }}"></script>
    <script src="{{ asset('vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('vendor/mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('vendor/mask/bindings/inputmask.binding.js') }}"></script>
    <script src="{{ asset('vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('js/pikaday.min.js') }}"></script>
    <script src="{{ asset('js/lightbox.min.js') }}"></script>
    <script src="//cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
    <!-- Tempus Dominus JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
            crossorigin="anonymous"></script>
    <script src="{{ asset('vendor/libs/tempus/tempus-dominus.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script> --}}
  <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>



    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/general.js') }}"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>

    @if (isset($modals))
        {!! $modals !!}
    @endif
    @stack('stack-modals')
    <div id="stack-modals"></div>

    @if (isset($scripts))
        {!! $scripts !!}
    @endif
    @stack('stack-scripts')

    @livewireScripts

    <x-livewire-alert::scripts />

  
    <script>
        (function($, window, undefined) {
            $(function() {
                let table = new DataTable('.dTables', {
                    columnDefs: [{
                        "sortable": false
                    }, ]
                });


                $(".ajaxTable").ajaxtable();
                $('.select2').select2({
                    placeholder: 'Select',
                    dropdownPosition: 'below'
                });
                $(".select2-tags").select2({
                    tags: true,
                    placeholder: "Select or Add new"
                });
                var bsDatepickerBasic = $('.date');
                var bsDatepickerRange = $('#bs-datepicker-daterange');

                if (bsDatepickerBasic.length) {
                    bsDatepickerBasic.datepicker({
                        todayHighlight: true,
                        format: 'mm-dd-yyyy',
                        autoclose: true,
                        orientation: 'auto left'
                    });
                }
                if (bsDatepickerRange.length) {
                    bsDatepickerRange.datepicker({
                        todayHighlight: true,
                        format: 'mm-dd-yyyy',
                        orientation: 'auto left'
                    });
                }
                const phoneMaskList = document.querySelectorAll('.phone-mask');
                if (phoneMaskList) {
                    phoneMaskList.forEach(function(phoneMask) {
                        new Cleave(phoneMask, {
                            phone: true,
                            phoneRegionCode: 'US'
                        });
                    });
                }
            });
        })(jQuery, window);
    </script>
</body>

</html>
