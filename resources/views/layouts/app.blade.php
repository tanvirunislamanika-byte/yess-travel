<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        
        <?php 

        $seo = seo (collect(request()->segments())->last());

        ?>
        <title>{{@$seo['title']}}</title>
        <meta name="description" content="{{@$seo['meta_description']}}">
        <meta name="keywords" content="{{@$seo['meta_keywords']}}">

        <!-- Fav Icon -->

        <link rel="shortcut icon" href="{{asset('favicon.ico') }}">

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
        
        <!-- RTL CSS for RTL languages -->
        @php
            use App\Helpers\TranslationHelper;
            $currentLanguage = TranslationHelper::getCurrentLanguage();
        @endphp
        @if($currentLanguage && $currentLanguage->is_rtl)
            <link rel="stylesheet" href="{{asset('css/rtlstyle.css') }}">
        @endif
        
        <!-- Styles -->
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
        @include('livewire.common.navbar')
        {{ $slot }}
        @include('livewire.common.footer')
        @stack('modals')
        <script src="{{asset('js/jquery.min.js') }}"></script> 
        <script src="{{asset('js/bootstrap.bundle.min.js') }}"></script> 

        <!-- Popup --> 
        <script src="{{asset('js/jquery.magnific-popup.min.js') }}"></script> 
        <script src="{{asset('js/magnific-popup-options.js') }}"></script> 

        <!-- Carousel --> 
        <script src="{{asset('js/owl.carousel.min.js') }}"></script> 


        <!-- Google Map --> 
        @if(empty($disableGmapAndCustomJs))
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMqMG_n4C0aAi3F8a82Q6s3hxDLrTXxe8&callback=initMap" async defer></script> 
        <script src="{{asset('js/gmap.js') }}"></script> 
        @endif

        <!-- Custom --> 
        @if(empty($disableGmapAndCustomJs))
        <script src="{{asset('js/custom.js') }}"></script>
        @endif

        @stack('scripts')
        @stack('js')
    </body>
</html>
