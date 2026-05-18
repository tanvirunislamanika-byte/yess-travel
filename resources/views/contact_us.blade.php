<x-app-layout>
@section('content')
<!-- Inner Heading Start -->

<!-- Page title start -->
<div class="pageheader">            
    <div class="container">
        <h1>{{__('Contact Us')}}</h1>
    </div>
</div>
<!-- Page title end -->



<div class="section contactform greybg">
        <div class="container">

        

        @if(session()->has('message.added'))
        <div class="thankyourmsgwrap">
        <div class="thankyoumsg">
               <div class="success-animation">
                  <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                     <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                     <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                  </svg>
               </div>

               <h3 class="text-center mt-4">Thank You for taking the time to complete this form</h3>
               <p>{!! session('message.content') !!}</p>
               <a href="{{url('/')}}" class="btn btn-primary mt-4">Back To Home</a>               
            </div>
        </div>

              @endif


            <div class="row">
                <div class="col-lg-6">
                  <div class="companycontact">
                     <h4>Get In Touch With Us !</h4>
                     <h3>Let's talk your business to move forward.</h3>


                     <div class="ctinfobox">
                        <div class="tpicon">
                            <img src="images/map.png" alt="Map">
                        </div>
                        <div class="tpinfo">
                           <h6>Corporate Headquarters</h6>
                           <p>{{widget(1)->extra_field_4}}</p>
                        </div>
                     </div>

                  


                     
                    <div class="ctinfobox">
                     <div class="tpicon">
                        <img src="images/mail.png" alt="Map">
                     </div>
                     <div class="tpinfo">
                     <h6>Send Email</h6>
                     <a href="mailto:{{widget(1)->extra_field_3}}">{{widget(1)->extra_field_3}}</a>
                        </div>
                     </div>

                     <div class="ctinfobox ctlast">
                        <div class="tpicon">
                           <img src="images/phone.png" alt="Map">
                        </div>
                        <div class="tpinfo">
                        <h6>Call Us</h6>
                        <a href="tel:{{widget(1)->extra_field_2}}">{{widget(1)->extra_field_2}}</a>
                        </div>
                    </div>
                    
      
                     
                  </div>
                </div>
                <div class="col-lg-6">
                    <div class="submitquery">
                        <h3>Request a call back</h3>

                        <form id="contact-form" method="post" action="{{route('contact.post')}}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                            <input id="form_name" type="text" name="first_name" class="form-control" placeholder="{{__('First Name')}}" required="required" data-error="Firstname is required.">
                            <div class="help-block with-errors"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                            <input id="form_lastname" type="text" name="last_name" class="form-control" placeholder="{{__('Last Name')}}" required="required" data-error="Last Name is required.">
                            <div class="help-block with-errors"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                            <input id="form_email" type="email" name="email_address" class="form-control" placeholder="{{__('Email')}}" required="required" data-error="Valid email is required.">
                            <div class="help-block with-errors"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                            <input id="form_phone" type="tel" name="phone_number" class="form-control" placeholder="{{__('Phone')}}" required="required" data-error="Phone is required">
                            <div class="help-block with-errors"></div>
                            </div>
                            <div class="col-md-12 mb-3">
                            <textarea id="form_message" name="message" class="form-control" placeholder="{{__('Message')}}" rows="4" required="required" data-error="Please,leave us a message."></textarea>
                            <div class="help-block with-errors"></div>
                            </div> 
                        </div>                       
                        <button type="submit" class="btn btn-primary">{{__('Send Message')}}</button>
                        </form>

                    </div>
                </div>
            </div>
            
        </div>
    </div>









</x-app-layout>

