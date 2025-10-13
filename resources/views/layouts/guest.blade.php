<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') {{ config('app.name', 'Laravel') }}</title>

    <x-fav-icon/>
    @livewireStyles
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap"
          rel="stylesheet">
    <!--end::Fonts-->
    @vite(['resources/sass/master.scss', 'resources/js/master.js','resources/css/app.css'])
    @yield('styles')
</head>
<!--begin::Body-->
<body>
<!--begin::Theme mode setup on page load-->

<!--end::Theme mode setup on page load-->
<!--begin::App-->
<div class="d-flex flex-column justify-content-between min-vh-100">
    <div class="flex-grow-1">

        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container">
                <ul class="navbar-nav mx-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                    <li>
                            <span class="h4">
    <a class="navbar-brand" href="{{ route('home') }}">
                    <img alt="Logo" src="{{ asset('assets/media/logos/logo.png') }}"
                         class="tw-h-16  tw-border-primary"/>
                </a>
                            </span>
                    </li>

                </ul>

            </div>
        </nav>

        <div class="container mt-10 card card-body">
            @include('client.partials._flash')
            @yield('content')
        </div>
    </div>
    <div class="container">
        @include('layouts._footer')
    </div>
</div>
<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
@livewireScripts
<script>
    // initialize pikaday .datepicker
    document.querySelectorAll('.datepicker').forEach(function (el) {
        new Pikaday({
            field: el,
            format: 'YYYY-MM-DD',
        });
    });
    document.querySelectorAll('.datetimepicker').forEach(function (el) {
        new Pikaday({
            field: el,
            format: 'YYYY-MM-DD HH:mm',
            timeFormat: 'HH:mm',
            defaultDate: new Date(),
            setDefaultDate: true,
        });
    });


    $(document).ready(function () {
        $(document).on('click', '.js-delete', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let method = 'DELETE';
            let token = $('meta[name="csrf-token"]').attr('content');
            let data = {
                _token: token,
                _method: method
            };

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        data: data,
                        success: function (response) {
                            if (window.dt) {
                                window.dt.ajax.reload();
                            } else {
                                window.location.reload();
                            }
                        },
                        error: function (response) {
                            console.log(response);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong, please try again',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            })
                        }
                    })
                }
            })

        })
    });
</script>
@stack('scripts')
</body>
<!--end::Body-->
</html>
