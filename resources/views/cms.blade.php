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
    
    <div class="container" style="    margin-top: 145px;margin-bottom: 20px;">
        <div class="row">
            <aside class="col-md-12 sidebar" role="complementary">
                <h1 class="entry-content">
                    <blockquote>
                        {{$cms->title}}
                    </blockquote>
                </h1>
                <!-- End .entry-content -->
            </aside>
        </div>
    </div>
    @if($cms->extra_field_1==50)
    <div class="bg-lightergray pt50 pb20" id="testimonials">
        <div class="container">
    {!!$cms->description!!}
</div></div>
    @else
    {!!$cms->description!!}
    @endif
        

    
    <div class="bg-lightergray pt50 pb20" id="testimonials">
        <div class="container">
            <h2 class="title text-uppercase text-center mb40"><span class="light">Happy Clients</span> Testimonials</h2>

            <div class="row">
                @if(null !== ($testimonials = module(5)))
                @php $number = 1; @endphp
                @foreach($testimonials as $testimonial)
                <div class="col-md-6">
                    <div class="testimonial wow @if($number % 2 == 0) reverse @endif zoomIn" data-wow-delay="0.2s">
                        <figure>
                            <img src="{{asset('images/'.$testimonial->image)}}" style="max-height:75px; width:auto;" alt="{{$testimonial->title}}">
                        </figure>
                        <div class="testimonial-content">
                            <p>{!!$testimonial->description!!}</p>
                            <h5>{{$testimonial->title}} - <a href="#" title="{{$testimonial->title}}">{{$testimonial->extra_field_1}}</a></h5> 
                        </div><!-- End .testimonial-content -->
                    </div><!-- End .testimonial -->
                </div><!-- End .col-md-6 -->
                @php $number++; @endphp
                @endforeach
                @endif
                <!-- End .col-md-6 -->
            </div>

        </div><!-- End .container -->
    </div>
    <div class="callout gray no-border no-margin" style="text-align:center;    background-color: #152a40;">
        <div class="container">
            <div class="callout-wrapper pb10">
                <i class="fa fa-share" style="font-size:26px; color: #fff;"></i><h4 class="callout-title" style="font-size:26px;color: #fff">Code, Analyse &amp; Share performance Insights</h4>
            </div><!-- End .callout-wrapper -->
            <form class="form-inline" action="{{route('login')}}" method="post">
                <button type="submit" class="btn btn-success btn-lg"  style="color: #fff;background-color: #337ab7;border-color: #2e6da4;">Try Analysis for Free</button>
            </form>
        </div><!-- End .container -->
    </div>
</x-app-layout>
