<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet" />
        <link href="{{asset('admin_assets/css/styles.css?v=2')}}" rel="stylesheet" />
        <link href="{{asset('admin_assets/css/app.css')}}" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
        <link rel="stylesheet" href="{{asset('admin_assets/plugins/summernote/summernote-bs4.min.css')}}">
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" crossorigin="anonymous"></script>

            <link href="{{asset('admin_assets/bower_components/jquery.filer/css/jquery.filer.css')}}" type="text/css" rel="stylesheet" />
        <link href="{{asset('admin_assets/bower_components/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css')}}" type="text/css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- RTL CSS for RTL languages -->
        @php
            use App\Helpers\TranslationHelper;
            $currentLanguage = TranslationHelper::getCurrentLanguage();
        @endphp
        @if($currentLanguage && $currentLanguage->is_rtl)
            <link rel="stylesheet" href="{{asset('css/rtlstyle.css') }}">
        @endif
        
        <!-- Styles -->
        @livewireStyles
        <script type="text/javascript">
          var base_url = "{!!url('/')!!}"
          var images_limit = 1
        </script>
        <!-- Scripts -->
        @stack('css')
        
    </head>
    <body class="nav-fixed">
        <input type="hidden" id="user-type" value="{!! Auth::user()->role !!}">
        @if(auth()->user() && auth()->user()->hasRole('admin'))
        <livewire:admin.common.navbar />
        @else
        <livewire:common.navbar />
        @endif
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
            @if(auth()->user() && auth()->user()->hasRole('admin'))
            <livewire:admin.common.sidebar />
            @else
            <livewire:common.sidebar />
            @endif    
            </div>
            <div id="layoutSidenav_content">
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div> 

        @stack('modals')

        
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{asset('admin_assets/js/scripts.js')}}"></script>
        <script src="{{asset('admin_assets/js/sidebar.js')}}"></script>
        <script src="{{asset('admin_assets/assets/js/dynamic-form.js')}}"></script>
        
        <!-- Custom Dashboard Styles -->
        <style>
            .dashboard-card {
                transition: all 0.3s ease;
            }
            .dashboard-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }
            
            .chart-container {
                position: relative;
                height: 300px;
            }
            
            .stats-card {
                background: linear-gradient(135deg, var(--tw-gradient-stops));
                transition: all 0.3s ease;
            }
            
            .stats-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            }
            
            .recent-booking-item {
                transition: all 0.2s ease;
            }
            
            .recent-booking-item:hover {
                background-color: #f8fafc;
                transform: translateX(2px);
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="{{asset('admin_assets/plugins/summernote/summernote-bs4.min.js')}}"></script>
        <script src="{{asset('admin_assets/bower_components/jquery.filer/js/jquery.filer.min.js')}}"></script>
        <script src="{{asset('admin_assets/js/jquery.dataTables.min.js')}}"></script> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

        @stack('js')
        
        @livewireScripts
    </body>
</html>
