<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/icomoon@1.0.0/style.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link
        rel="stylesheet"href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>



    {{-- <link rel="stylesheet" href="../../assets/css/bootstrap.css"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/icon-font.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auction.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/rs-plugin/css/settings.css') }}">





    <title>@yield('title')</title>
</head>



<body>

    <div class="sidebar-menu-container" id="sidebar-menu-container">
        <div class="sidebar-menu-push">

            <div class="sidebar-menu-overlay"></div>

            <div class="sidebar-menu-inner">

                @include('sections.header')                

               
                <main>
                    @yield('content')
                </main>

                @include('sections.footer')

                <a href="#" class="go-top"><i class="fa fa-angle-up"></i></a>

            </div>
        </div>


    @include('sections.sidebar')
</div>

</body>

<script type="text/javascript" src="{{ asset('assets/js/jquery-1.11.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<!-- SLIDER REVOLUTION 4.x SCRIPTS  -->
<script src="{{ asset('assets/rs-plugin/js/jquery.themepunch.tools.min.js') }}"></script>
<script src="{{ asset('assets/rs-plugin/js/jquery.themepunch.revolution.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/js/plugins.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/custom.js') }}"></script>


</html>
