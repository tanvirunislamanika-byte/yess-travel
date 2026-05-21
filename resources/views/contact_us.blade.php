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

<style>
    .submitquery{
       background: #0e52a5;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(14, 82, 165, 0.25);
    }

    .submitquery h3{
        color: #fff;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 25px;
    }

    .submitquery .form-control{
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        color: #fff;
        height: 55px;
        border-radius: 12px;
        padding: 12px 18px;
        box-shadow: none;
    }

    .submitquery textarea.form-control{
        height: auto;
        min-height: 140px;
        resize: none;
    }

    .submitquery .form-control::placeholder{
        color: rgba(255,255,255,0.75);
    }

    .submitquery .form-control:focus{
        background: rgba(255,255,255,0.20);
        border-color: #fff;
        color: #fff;
        box-shadow: none;
    }

    .submitquery .btn-primary{
        background: #fff;
        color: #0d6efd;
        border: none;
        padding: 14px 35px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 12px;
        transition: 0.3s;
    }

    .submitquery .btn-primary:hover{
        background: #f1f5ff;
        transform: translateY(-2px);
    }

    .submitquery .help-block{
        color: #fff;
    }

    @media(max-width: 767px){
        .submitquery{
            padding: 25px;
            margin-top: 30px;
        }

        .submitquery h3{
            font-size: 26px;
        }
    }
</style>

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

                <h3 class="text-center mt-4">
                    Thank You for taking the time to complete this form
                </h3>

                <p>{!! session('message.content') !!}</p>

                <a href="{{url('/')}}" class="btn btn-primary mt-4">
                    Back To Home
                </a>
            </div>
        </div>
        @endif

        <div class="row">
            
            <!-- Left Contact Info -->
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
                            <img src="images/mail.png" alt="Mail">
                        </div>

                        <div class="tpinfo">
                            <h6>Send Email</h6>

                            <a href="mailto:{{widget(1)->extra_field_3}}">
                                {{widget(1)->extra_field_3}}
                            </a>
                        </div>
                    </div>

                    <div class="ctinfobox ctlast">
                        <div class="tpicon">
                            <img src="images/phone.png" alt="Phone">
                        </div>

                        <div class="tpinfo">
                            <h6>Call Us</h6>

                            <a href="tel:{{widget(1)->extra_field_2}}">
                                {{widget(1)->extra_field_2}}
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Form -->
            <div class="col-lg-6">
                <div class="submitquery">

                    <h3>Request a call back</h3>

                    <form id="contact-form" method="post" action="{{route('contact.post')}}">
                        @csrf

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <input 
                                    id="form_name"
                                    type="text"
                                    name="first_name"
                                    class="form-control"
                                    placeholder="{{__('First Name')}}"
                                    required="required"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <input 
                                    id="form_lastname"
                                    type="text"
                                    name="last_name"
                                    class="form-control"
                                    placeholder="{{__('Last Name')}}"
                                    required="required"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <input 
                                    id="form_email"
                                    type="email"
                                    name="email_address"
                                    class="form-control"
                                    placeholder="{{__('Email')}}"
                                    required="required"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <input 
                                    id="form_phone"
                                    type="tel"
                                    name="phone_number"
                                    class="form-control"
                                    placeholder="{{__('Phone')}}"
                                    required="required"
                                >
                            </div>

                            <div class="col-md-12 mb-3">
                                <textarea 
                                    id="form_message"
                                    name="message"
                                    class="form-control"
                                    placeholder="{{__('Message')}}"
                                    rows="4"
                                    required="required"
                                ></textarea>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary">
                            {{__('Send Message')}}
                        </button>

                    </form>

                </div>
            </div>

        </div>

    </div>
</div>







</x-app-layout>

