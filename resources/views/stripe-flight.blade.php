<x-app-layout>
    <?php $hotelM = App\Models\ModulesData::where('id',$flight->flight_id)->first(); ?>
   <!-- Page title start -->

<div class="pageheader">            
    <div class="container">
        <h1>Pay Now ({{$hotelM->title}} USD.{{$flight->price}})</h1>
    </div>
</div>
<!-- Page title end -->


<!-- Page content start -->
<div class="innerpagewrap">
    <div class="container register-form">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-10">
<style>
  .group{
        margin-bottom: 10px;
  }
</style>
        {!! Form::open(array('method' => 'GET', 'id' => 'stripe-form', 'url' => url('/flight-payment/'.$flight->id), 'class' => 'form')) !!}
        <div class="account-main"><div class="row">
                                    <div class="col-md-12" id="error_div"></div>
                                    <div class="col-md-12">
                                        <div class="group">
                                            <label>{{__('Name on Credit Card')}}</label>
                                            <input class="form-control" id="card_name" placeholder="{{__('Name on Credit Card')}}" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="group">
                                            <label>{{__('Credit card Number')}}</label>
                                            <input class="form-control" id="card_no" placeholder="{{__('Credit card Number')}}" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="group">
                                            <label>{{__('Credit card Expiry Month')}}</label>                     
                                            <select class="form-control" id="ccExpiryMonth">                    
                                                @for ($counter = 1; $counter <= 12; $counter++)
                                                @php
                                                $val = str_pad($counter, 2, '0', STR_PAD_LEFT);
                                                @endphp
                                                <option value="{{$val}}">{{$val}}</option>
                                                @endfor
                                            </select>                    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="group">
                                            <label>{{__('Credit card Expiry Year')}}</label>                    
                                            <select class="form-control" id="ccExpiryYear">
                                                @php
                                                $ccYears = array();
                                                  for ($counter = date('Y'); $counter < date('Y') + 50; $counter++) {
                                                      $ccYears[$counter] = $counter;
                                                  }
                                                @endphp
                                                @foreach($ccYears as $year)
                                                <option value="{{$year}}">{{$year}}</option>
                                                @endforeach
                                            </select>                    
                                        </div>
                                    </div>                  
                                    <div class="col-md-12">
                                        <div class="group">
                                            <label>{{__('CVV Number')}}</label>
                                            <input class="form-control" id="cvvNumber" placeholder="{{__('CVV number')}}" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="group">
                                            <button type="submit" class="btn btn-primary">{{__('Pay with Stripe')}} <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div></div>

        </div>
        </div>
    </div>
    </div>
<!--  end -->

@push('js')
<style>
#checoutform{

  width:400px;

  margin:0 auto;

}

#checoutform label{

  padding:0;

  margin:0;

    margin-bottom: 25px;



}



#checoutform #card_name{

  background:#fff;

  margin-bottom:15px !important;

  height:55px;

  

}

#card-element{

  background:#fff;

  height:55px;

  margin-bottom:25px;

  padding-top: 8px;

}

#price_modal {

    font-size: 30px !important;

    position: relative;

    top: -13px;

}



#card-element::-webkit-input-placeholder { /* Edge */

  color:#000;

}



#card-element:-ms-input-placeholder { /* Internet Explorer 10-11 */

  color:#000;

}


.kanoony-modal .modal-content {
    border: none;
    border-radius: 0;
    -webkit-box-shadow: 0 3px 7px rgb(0 0 0 / 13%);
    box-shadow: 0 3px 7px rgb(0 0 0 / 13%);
    background: #EAEAEA;
    color: #2d3d44;
}

.kanoony-modal .kobe-modal__title {
    margin-top: 10px;
}

.kanoony-modal .kobe-modal__title {
    margin-top: 2rem;
    font-size: 1.5rem;
    text-transform: uppercase;
    font-family: 'montserrat', sans-serif;
}

.kanoony-modal .kobe-modal__text {
    margin-top: 0;
}
.kanoony-modal .kobe-modal__text {
    margin-top: 1rem;
    padding: 0 4rem;
    color: #252525;
    font-size: 16px;
}



#card-element::placeholder {

  color:#000;

}

.kanoony-modal .modal-content{

  border-radius:15px;

}

</style>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script> 
<script type="text/javascript">
Stripe.setPublishableKey('pk_test_qZrL4iEhRiW0xVy1X3HRDtnp');
var $form = $('#stripe-form');
$form.submit(function (event) {
    $('#error_div').hide();
    $form.find('button').prop('disabled', true);
    Stripe.card.createToken({
        number: $('#card_no').val(),
        cvc: $('#cvvNumber').val(),
        exp_month: $('#ccExpiryMonth').val(),
        exp_year: $('#ccExpiryYear').val(),
        name: $('#card_name').val()
    }, stripeResponseHandler);
    return false;
});
function stripeResponseHandler(status, response) {
    if (response.error) {
        $('#error_div').show();
        $('#error_div').text(response.error.message);
        $form.find('button').prop('disabled', false);
    } else {
        var token = response.id;
        $form.append($('<input type="hidden" name="token" />').val(token));
        // Submit the form:
        $form.get(0).submit();
    }
}
</script> 






@endpush
</x-app-layout>
