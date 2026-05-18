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
        </style>



<div class="pageheader"">            
            <div class="container">            
                <h1>Service Overview</h1>
            </div>
      </div>




      <div id="content" role="main" class="py-5">
        <div class="container">
            <div class="txtdata">
                <div class="row">
                    <div class="col-lg-6">
                        <h2>{!!$service->getTranslatedExtraField(1)!!} {{$service->getTranslatedTitle()}}</h2>
                        <p>{!!$service->getTranslatedDescription()!!}</p>
                    </div>
                    <div class="col-lg-6">
                        <div class="secimg"><img src="{{asset('images/'.$service->image)}}"></div>
                    </div>
                </div>
            </div>

            <div class="readmore mt-5">
                <a href="{{url('services')}}" class="btn btn-primary">Back To Services</a>
                <a href="{{url('contact-us')}}" class="btn btn-white ms-3">Contact Us</a>
            </div>


        </div>
      </div>





</x-app-layout>