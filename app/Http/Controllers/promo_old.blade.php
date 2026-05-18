<div>
@push('ajax-loader')

  <div id="loading-image1" style="    position: fixed;

    display: none;

    z-index: 111;

    text-align: center;

    height: 100vh;

    width: 100%;

        background-color: #0B0B0B;

    padding-top: 15.5555%;

    font-size: 22px;

    font-weight: bold;

    color: #fff;"> <img id="" src="{{asset('images/ajax-loader1.gif')}}" style="" alt="Ajax Loader"/> <br>Processing...</div>

    @endpush
  <form wire:Submit.prevent="addToCart({{$document->document_id}})" id="checkout_form">

    @if($document->document_full->document_price!='0.00')

    <div class="form-group">

      <label>Enter Promocode</label>

      <input type="text" style="@if($promo_flag=='no') border: 1px solid red; @endif @if($promo_flag=='yes') border: 1px solid green; @endif width: 95%;" name="promo_code" id="promo_code" placeholder="Enter Promocode" class="form-control" wire:model.debounce.300ms="promo">

      @if($promo_flag=='no') <img style="    margin-top: -30px;
    width: 23px;
    left: 88%;
    margin-right: 20px;
    color: green;
    /* min-width: 37px; */
    text-align: center;
    position: absolute;" src="{{asset('images/cross.svg')}}" alt=""> @endif

      

      @if($promo_flag=='yes') <img style="    margin-top: -30px;
    width: 23px;
    left: 88%;
    margin-right: 20px;
    color: green;
    /* min-width: 37px; */
    text-align: center;
    position: absolute;" src="{{asset('images/circle-check.svg')}}" alt="">  @endif </div>

    @endif

    <input type="hidden" name="token_id" id="token_id">

    <div id="online_buy_buttons_block" class="kobe-modal__control-btn custom_button_margin" style="display: none;"></div>

    <div id="download_buy_buttons_block" class="kobe-modal__control-btn custom_button_margin"> <br>

      <div class="downbtn">

        <select name="type" wire:model="type" class="form-control" id="attachement_type">

          

          

                    @if($document->document_full->englishattachment) 

                    

          

          <option value="englishattachment">English</option>

          

           

                    @endif

                    @if($document->document_full->arabicattachment) 

                    

          

          <option value="arabicattachment">English</option>

          

           

                    @endif

                    @if($document->document_full->combineattacment) 

                    

          

          <option value="combineattacment">English & Arabic</option>

          

           

                    @endif 

                

        

        </select>

        @error('type') <span style="color: red;" class="error">{{$message}}</span> @enderror </div>

    </div>

    <div class="modal-footer"> @if(auth()->user())

      <?php if($free){ ?>

      <a href="javascript:;" style="line-height: 30px; @if(isMobileDevice())     position: absolute;
    right: 0;
    left: 0;
    margin-top: 25px; @endif" wire:loading.attr="disabled" onclick="checkoutFree()" class="button button-add-to-cart btn btn-primary" id="checkout"><i class="fi-rs-shopping-cart" ></i>Download</a>

      <?php }else{ ?>

      <a href="javascript:;" style="line-height: 30px;  @if(isMobileDevice())     position: absolute;
    right: 0;
    left: 0;
    margin-top: 25px; @endif" wire:loading.attr="disabled" onclick="checkout()" class="button button-add-to-cart btn btn-primary" id="checkout"><i class="fi-rs-shopping-cart" ></i>Buy Now</a>

      <?php } ?>

      @else


       <a href="javascript:;" onclick="setCurrentUrl()" style="line-height: 30px;  @if(isMobileDevice())     position: absolute;
    right: 0;
    left: 0;
    margin-top: 25px; @endif" wire:loading.attr="disabled"  class="button button-add-to-cart btn btn-primary" id="checkout"><i class="fi-rs-shopping-cart" ></i>

      <?php if($free){ ?>

      Download

      <?php }else{ ?>

      Buy Now

      <?php } ?>

      </a> @endif </div>

  </form>

  <form id="checoutform" style="display: none;">

    <div class="group">

      <label>

        <input id="card_name" class="field" placeholder="Card Holder Name" />

      </label>

    </div>

    <div class="group">

      <label>

      <div id="card-element" class="field"></div>

      </label>

    </div>

    <button type="submit">Buy Now</button>

    <div class="outcome">

      <div class="error"></div>

      <div class="success"> Success! Your Stripe token is <span class="token"></span> </div>

    </div>

  </form>

  @push('js')

  <style>

    form {

  width: 480px;

  margin: 20px 0;

}





label {

  position: relative;

  color: #8898AA;

  font-weight: 300;

  height: 40px;

  line-height: 40px;

  margin-left: 20px;

  display: flex;

  flex-direction: row;

}



.group label:not(:last-child) {

  border-bottom: 1px solid #F0F5FA;

}



label > span {

  width: 80px;

  text-align: right;

  margin-right: 30px;

}



.field {

  background: transparent;

  font-weight: 300;

  border: 0;

  color: #31325F;

  outline: none;

  flex: 1;

  padding-right: 10px;

  padding-left: 10px;

  cursor: text;

}



.field::-webkit-input-placeholder { color: #CFD7E0; }

.field::-moz-placeholder { color: #CFD7E0; }



#checoutform button {

  float: left;

  display: block;

  background: #666EE8;

  color: white;

  box-shadow: 0 7px 14px 0 rgba(49,49,93,0.10),

              0 3px 6px 0 rgba(0,0,0,0.08);

  border-radius: 4px;

  border: 0;

  margin-top: 20px;

  font-size: 15px;

  font-weight: 400;

  width: 100%;

  height: 40px;

  line-height: 38px;

  outline: none;

}



button:focus {

  background: #555ABF;

}



button:active {

  background: #43458B;

}



.outcome {

  float: left;

  width: 100%;

  padding-top: 8px;

  min-height: 20px;

  text-align: center;

}



.success, .error {

  display: none;

  font-size: 13px;

}



.success.visible, .error.visible {

  display: inline;

}



.error {

  color: #E4584C;

}



.success {

  color: #666EE8;

}



.success .token {

  font-weight: 500;

  font-size: 13px;

}

.stripe-logo{

background-color: #fff;

    margin-bottom: 15px;

    border-radius: 50%;

    width: 150px;

    height: 150px;

    margin: 0 auto;

    padding-top: 5%;

    margin-bottom: 15px;

    margin-top: -83px;

    border: 3px solid #6772e5;

}

.kanoony-modal .kobe-modal__title {

    margin-top: 10px;

}

.kanoony-modal .kobe-modal__text{

	margin-top:0;

}

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



#card-element::placeholder {

  color:#000;

}

.kanoony-modal .modal-content{

	border-radius:15px;

}

</style>

  @endpush 

  <script src="https://js.stripe.com/v3/"></script> 

  <script>



















      function setCurrentUrl(save) {
        if(!save=='save'){
          fbq('track', 'InitiateCheckout');
        }
        

        $.ajax({

          url: "{{route('set-current-url')}}",

          method: 'GET',

          data: {

            current_url: '{{url()->current()}}',
            save: save,

          },

          success: function(response) {

            // Handle the successful response

            window.location.href = "{{route('login')}}";

          },

          error: function(error) {

            // Handle the error

            console.log(error);

          }

        });

      }




          //var stripe = Stripe('pk_test_51N2xNsBozUf5joGRDVYeGaZT6CEgvN3IIshXGO8W83N2UhF48afcuZfsXDQCpy3e8OEozBd0JAG5CgfWX3ZXlJUm00zR2arFlu');


            var stripe = Stripe('pk_live_51N2xNsBozUf5joGRiOgvueUCjE9WvX0SYBKX3AI2cDSG9ZCWUTPh7MgVrNGC3C3rNPEa3Be5ov8b5W2FHpnansWb00mF9cUYze');

            var elements = stripe.elements();



            var card = elements.create('card', {

              style: {

                base: {

                  iconColor: '#666EE8',

                  color: '#31325F',

                  lineHeight: '40px',

                  fontWeight: 300,

                  fontFamily: 'Helvetica Neue',

                  fontSize: '15px',



                  '::placeholder': {

                    color: '#CFD7E0',

                  },

                },

              }

            });

            card.mount('#card-element');

            card.update({ hidePostalCode: true });



            function setOutcome(result) {

              var successElement = document.querySelector('.success');

              var errorElement = document.querySelector('.error');





              if (result.error) {

                successElement.classList.remove('visible');

                errorElement.textContent = result.error.message;

                errorElement.classList.add('visible');

              } else {

                errorElement.classList.remove('visible');

                // Use the token to create a charge or a customer

                // https://stripe.com/docs/charges

                //successElement.querySelector('.token').textContent = result.token.id;

                $('#promoModal').modal('hide');

                $('#loading-image1').css('display','block');

                $('#token_id').val(result.token.id);

                $('#checkout_form').attr('action', "{{route('document.purchase.direct',[$document->document_id])}}");

                $('#checkout_form').submit();



                successElement.classList.add('visible');

              }

            }



            card.on('change', function(event) {

              if (event.error) {

                setOutcome(event);

              }

            });



            document.querySelector('#checoutform').addEventListener('submit', function(e) {

              e.preventDefault();

              var card_name = document.getElementById('card_name').value;

              stripe.createToken(card, {name: card_name}).then(setOutcome);

            });



            







        



     function checkout(){









        //alert($('#attachement_type').val());



        fbq('track', 'InitiateCheckout');



        //return false;





        if($('#attachement_type').val()!=''){

            $('#checkout_form').hide();

            $('#checoutform').show();

            $('.stripe-logo').show();



        }else{







            Swal.fire({







                  icon: 'error',







                  title: 'Oops...',







                  text: 'Please Choose Document type before checkout',







                }) 







        }







    

    }

</script> 

  <script>

    

function checkoutFree(){







            

     
  fbq('track', 'InitiateCheckout');




        if($('#attachement_type').val()!=''){



            $('#checkout_form').attr('action', "{{route('document.purchase.direct',[$document->document_id])}}");

            $('#checkout_form').submit();



            







        }else{







            Swal.fire({







                  icon: 'error',







                  title: 'Oops...',







                  text: 'Please Choose Document type before checkout',







                }) 







        }







    

    

}



</script> 

  @if($added) 

  <script type="text/javascript">

    var button = $('#add-to-basket');

        var cart = $('#cart');

        var cartTotal = cart.attr('data-totalitems');

        var newCartTotal = parseInt(cartTotal) + 1;

        

        button.addClass('sendtocart');

        setTimeout(function(){

          button.removeClass('sendtocart');

          cart.addClass('shake').attr('data-totalitems', newCartTotal);

          setTimeout(function(){

          cart.removeClass('shake');

          setTimeout(function(){

              $('#promoModal').modal('hide');

          },500)

          },500)

        },1000)





</script> 

  @endif </div>