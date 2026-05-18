<x-app-layout>
    <style>
        
        .navbar-transparent {
            background-color: #2a2a2a !important;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 299;
            transition: all 0.25s;
            -webkit-transition: all 0.25s;
        }
        /* CSS Document */

        .profile-thum-wrapper {
            width: 100%;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .thum-holder {
            text-align: center;
            position: relative;
            border-radius: 10px;
            width: 230px;
            height: 90px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            /*margin-bottom: 20px;*/
        }

        .thum-holder .thum {
            height: 100%;
            width: 100%;
            -o-object-fit: cover;
            object-fit: cover;
            -o-object-position: center;
            object-position: center;
        }

        .thum-holder .upload-file-block,
        .thum-holder .upload-loader {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(90, 92, 105, 0.7);
            color: #f8f9fc;
            font-size: 12px;
            font-weight: 600;
            opacity: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .thum-holder .upload-file-block {
            cursor: pointer;
        }

        .thum-holder:hover .upload-file-block,
        .uploadProfileInput:focus ~ .upload-file-block {
            opacity: 1;
        }

        .thum-holder.uploadInProgress .upload-file-block {
            display: none;
        }

        .thum-holder.uploadInProgress .upload-loader {
            opacity: 1;
        }
    </style>
       <!-- Page title start -->
<div class="pageheader">            
    <div class="container">
        <h1> {{ __('Change Password') }}</h1>
    </div>
</div>
<!-- Page title end -->


    

    <div>



       <div class="innerpagewrap">
    <div class="container">

    <div class="row">
            <div class="col-lg-3">
              @include('components.user-sidebar')
            </div>


            
            <div class="col-lg-9">

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border />
            @endif
            



            </div></div>
        </div>
        </div>




    </div>
</x-app-layout>
