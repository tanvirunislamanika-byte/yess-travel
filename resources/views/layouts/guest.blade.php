<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Travel In</title>
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css') }}">

        <!-- Fontawesome css -->
        <link rel="stylesheet" href="{{asset('css/all.css') }}">

        <!-- Magnific-popup css -->
        <link rel="stylesheet" href="{{asset('css/magnific-popup.css') }}">

        <!-- Owl Carousel css -->
        <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Main css -->
        <link rel="stylesheet" href="{{asset('css/style.css') }}">
        
        <!-- Styles -->
        @livewireStyles
        <script type="text/javascript">
          var base_url = "{!!url('/')!!}"
          var images_limit = 1
        </script>
        <!-- Scripts -->
        @stack('css')

        <style type="text/css">
            .display-block{
                display: block;
            }
        </style>
        
    </head>
    <body class="nav-fixed">
        <livewire:common.navbar />
        {{ $slot }}
        <livewire:common.footer />
        @stack('modals')

        @livewireScripts
        <script src="{{asset('js/jquery.min.js') }}"></script> 
        <script src="{{asset('js/bootstrap.bundle.min.js') }}"></script> 

        <!-- Popup --> 
        <script src="{{asset('js/jquery.magnific-popup.min.js') }}"></script> 
        <script src="{{asset('js/magnific-popup-options.js') }}"></script> 

        <!-- Carousel --> 
        <script src="{{asset('js/owl.carousel.min.js') }}"></script> 


        <!-- Google Map --> 
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMqMG_n4C0aAi3F8a82Q6s3hxDLrTXxe8&callback=initMap" async defer></script> 
        <script src="{{asset('js/gmap.js') }}"></script> 

        <!-- Custom --> 
        <script src="{{asset('js/custom.js') }}"></script>

        @stack('js')
    </body>
</html>
