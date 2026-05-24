<x-app-layout>

@section('content')
<!-- Inner Heading Start -->


<div class="pageheader" style="background: url('{{ asset('images/background/background.jpg') }}') no-repeat center center; background-size: cover;">
            <div class="container">            
                <h1>{{$cms->getTranslatedTitle()}}</h1>
            </div>
      </div>



<section class="innerPages">
  <div class="container">
    <div class="row">
      <div class="col-lg-7 col-md-12">
        <div class="cmspagecontent">           
      		{!!$cms->getTranslatedDescription()!!}
        </div>
      </div>
      <aside class="col-lg-5">
		    @if($cms->image!='' && null!==($cms->image))
          <div class="about_img"><img src="{{asset('images/'.$cms->image)}}" class="d-block w-100" /></div>
          @endif
		  
		    <div class="callWrp">
          <div class="getquoteBx">
            <div class="icon">
              <i class="fas fa-phone-alt"></i>
            </div>
            <p>{{__('Contact us for more information or to start a project with us')}}.</p>
            <a href="{{url('contact-us')}}" class="btn btn-white mt-4">{{__('Contact Us')}}</a>
          </div>
        </div>		  
		  
      </aside>






    </div>
  </div>
</section>


</x-app-layout>


