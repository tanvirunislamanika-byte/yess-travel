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
    
    <div class="container" style="    margin-top: 145px;">
        <div class="row">
            <aside class="col-md-12 sidebar" role="complementary">
                <h1 class="entry-content">
                    <blockquote>
                        Paul Jennings Analysis / Team Channel
                    </blockquote>
                </h1>
                <!-- End .entry-content -->
            </aside>
        </div>
    </div>
    <div class="container">

                                <!-- print page -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="entry-content text-right" id="printToHide">
                           <i class="fa fa-print" aria-hidden="true" onclick="doPrint()" id="printPageBtn" style="padding-right:20px;"></i>
                        </div>
                    </div>
                </div> 
                <!-- end row -->
                
                <div class="mb20"></div> <!--space -->

                <div class="row">
                    <div class="col-md-12">

                        <article class="entry entry-box entry-box-blockquote" style="height:auto; overflow:hidden; margin-right:15px;">

                            <div class="entry-content" style="padding:15px;">
                                <blockquote>
                                    <p>
                                       {{$analysis->title}}                                 </p>
                                </blockquote>
                            </div><!-- End .entry-content -->

                            <div class="col-sm-6 col-sm-6 col-md-4">

                                <div class="entry-media" style="width:282px !important; height:auto !important;">
                                                                         <video controls width="640" height="360" autoplay="false">
                                                                    <source src="{{ asset('images/'.$analysis->image) }}" type="video/mp4">
                                                                    Your browser does not support the video tag.
                                                                </video>
                                                                        <div style="position:relative; margin-top:-15px; width:286px;">
                        
                                        <div style="float:right; width:26px; height:26px;">
                                        
                                                <a
                                                                href="{{route('video_detail',$analysis->slug)}}"
                                                                title="{{$analysis->title}}"
                                                            >
                                                                
                                                            </a>
                                        
                                        </div>
                                
                                                                        
                                        <div style="clear:both; height:1px; width:125px;"></div>
                                    
                                    </div>
                            
                                </div>

                            </div>
                            <div class="col-sm-6 col-sm-6 col-md-8">

                                <table class="table table-condensed table-hover" style="border-bottom:solid 3px #ffffff;">
                                    <tbody>
                                        <tr><td width="100">
                                            Section
                                        </td><td>
                                            {!!title($analysis->extra_field_1)!!}                                        </td></tr>
                                        <tr><td>
                                            Category 
                                        </td><td>
                                            {!!title($analysis->category)!!}                                     </td></tr>
                                        <tr><td>
                                            Event Date 
                                        </td><td>
                                            {!!date('d/m/Y',strtotime($analysis->extra_field_2))!!}
                                                                                </td></tr>
                                        <tr><td>
                                            Plays
                                        </td><td>
                                            {!!$analysis->extra_field_3!!}                                       </td></tr>

                                                                            </tbody>
                                </table>

                                
                            </div>

                        </article>

                    </div>
                </div>

        <!-- this div encapsulates the printable area only -->  
        <div id="printContents">

            <!--  match analysis -->
                            
                <div class="row">
                    <div class="col-md-12">
                        <div class="mt25"></div><!-- space -->
                                            
                            <style>

        .rounded {
            
            border-radius:          5px;
            -webkit-border-radius:  5px;
            -moz-border-radius:     5px;
        }​

        td { 

            padding: 4px;
        }

    </style>

    <script src="https://s3-eu-west-1.amazonaws.com/web.imedialibrarysports.com/iml-player/javascript/ISA-UserPlayerSnippets-v2.js"></script>
    
    <div style="margin:0px auto; padding: 0px 0px 30px 0px;">
        {!!$analysis->description!!}
    </div>
                                
                    </div>
                    <!-- end show details -->
                                    
                                        <!--  end match analysis -->    

                </div>
                
                                
        <!-- end printable area -->
        </div>

    <div class="mt65"></div><!-- space -->
</div>
</x-app-layout>
