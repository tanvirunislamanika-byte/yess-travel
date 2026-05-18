<x-app-layout>

<!-- Page title start -->
<div class="pageheader">            
    <div class="container">
        <h1>{{$flight->getTranslatedTitle()}}</h1>
    </div>
</div>
<!-- Page title end -->

<!-- Top Hotels Section -->
<div class="innerpagewrap">
  <div class="container">
    

        <div class="row">
            <div class="col-lg-8">
               
                <div class="flightinfowrap">
                    <div class="d-flex justify-content-between">
                    <h1>{{$flight->getTranslatedExtraField(3)}} <i class="fas fa-arrows-alt-h"></i> {{$flight->getTranslatedExtraField(6)}}</h1>
                    <div class="fdate">{{date('d M, Y', strtotime($flight->getTranslatedExtraField(9)))}}</div>
                    </div>
                    <div class="dflightcom">            
                    @if($flight->getTranslatedExtraField(20) == 1)  
                                            <img src="{{$flight->getTranslatedExtraField(21)}}" alt="{{ __('frontend.airline_logo') }}">
                                            @else                                                 
                                                @php
                                                    $airline = \App\Models\ModulesData::find($flight->getTranslatedExtraField(1));
                                                @endphp
                                                @if($airline && $airline->image)
                                                    <img src="{{asset('images/'.$airline->image)}}" alt="{{title($flight->getTranslatedExtraField(1))}}">
                                                @else
                                                    <img src="{{asset('images/no-image.jpg')}}" alt="{{ __('frontend.default_airline_logo') }}">
                                                @endif
                                            @endif    
                        <div class="">
                            <p>{{ __('frontend.airplane') }}: {{title($flight->getTranslatedExtraField(1))}}</p>
                            <p>{{ __('frontend.plan_type') }}: {{title($flight->getTranslatedExtraField(2))}}</p>
                        </div>                        
                    </div>



                    <div class="flightmoveinfo">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="ftakeinfo">
                                    <div class="takeicon"><i class="fas fa-plane-departure"></i></div>
                                    <div class="taketext">
                                        <strong>{{ __('frontend.take_off') }}</strong>
                                        <h5>{{ \Carbon\Carbon::parse($flight->getTranslatedExtraField(5))->format('d M, Y h:i A') }}</h5>
                                        <p>{{$flight->getTranslatedExtraField(3)}}, {{title($flight->getTranslatedExtraField(4))}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="ftakeinfo">
                                    <div class="takeicon"><i class="fas fa-plane-arrival"></i></div>
                                    <div class="taketext">
                                        <strong>{{ __('frontend.landing') }}</strong>
                                        <h5>{{ \Carbon\Carbon::parse($flight->getTranslatedExtraField(8))->format('d M, Y h:i A') }}</h5>

                                        <p>{{$flight->getTranslatedExtraField(6)}}, {{title($flight->getTranslatedExtraField(7))}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="economytxt">{{ __('frontend.round_trip_price') }}: <strong>${{$flight->getTranslatedExtraField(11)}}</strong></div>


                </div>

                       

                <div class="detcontent contentbullets">
                    <h3>{{ __('frontend.overview') }}</h3>
                    <p>{{$flight->getTranslatedExtraField(19)}}</p>

                    <h3>{{ __('frontend.guidelines') }}</h3>
                    {!!$flight->description!!}
                </div>

                
                
                <div class="detcontent">
                    <h3>{{ __('frontend.whats_included') }}</h3>
                    <ul class="flightincl">
                        <li><span class="text-fst"><i class="fas fa-suitcase-rolling"></i> {{ __('frontend.checked_baggage') }}: <strong>{{title($flight->getTranslatedExtraField(12))}}</strong></span></li>
                        <li><span class="text-fst"><i class="fas fa-briefcase"></i> {{ __('frontend.carry_on_baggage') }}: <strong>{{title($flight->getTranslatedExtraField(13))}}</strong></span></li>
                        <li><span class="text-fst"><i class="fas fa-tag"></i> {{ __('frontend.change_fees') }}: <strong>{{title($flight->getTranslatedExtraField(14))}}</strong></span></li>
                        <li><span class="text-fst"><i class="fas fa-ban"></i> {{ __('frontend.refundable') }}: <strong>{{title($flight->getTranslatedExtraField(15))}}</strong></span></li>
                        <li><span class="text-fst"><i class="fas fa-cookie"></i> {{ __('frontend.snacks') }}: <strong>{{title($flight->getTranslatedExtraField(16))}}</strong></span></li>
                        <li><span class="text-fst"><i class="fas fa-bolt"></i> {{ __('frontend.usb_power_outlet') }}: <strong>{{title($flight->getTranslatedExtraField(17))}}</strong></span></li>
                    </ul>
                </div>


                <div class="galleryview owl-carousel owl-theme">
                    <?php $images = explode(',', $flight->images); ?>
                    @if(null!==($images))
                    @foreach($images as $image)
                    <?php $src = asset('images/'.$image); ?>
                    <div class="item"><a href="{{$src}}" class="image-popup"><img src="{{$src}}"></a></div>
                    @endforeach
                    @endif
                </div>

            </div>
            <div class="col-lg-4">

                <div class="flightprices">From: ${{$flight->getTranslatedExtraField(10)}}<span></span></div>
                @if(!auth()->user())
                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#registerModal" class="btn btn-primary w-100 mb-3">{{ __('frontend.book_now') }}</a>
                @endif
                @if(auth()->user())
                <!-- Show Form when logged In -->
                <div class="bookingsidebar sticky-top">
                    <h4>{{ __('frontend.book_now') }}</h4>
                    <form action="{{route('flight-booking')}}" method="post">
                        @csrf
                        <input type="hidden" name="price" id="price">
                        <input type="hidden" name="flight_id" id="flight_id" value="{{$flight->id}}">
                        <div class="mb-3 pt-4">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="one_way_or_two_way" id="onewayflight" checked="" value="One Way">
                                <label class="form-check-label" for="onewayflight">
                                    One Way
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="one_way_or_two_way" id="twowayflight" value="Two Way">
                                <label class="form-check-label" for="twowayflight">
                                    Two Way
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="one_way_price" value="{{$flight->getTranslatedExtraField(10)}}">
                        <input type="hidden" name="two_way_price" value="{{$flight->getTranslatedExtraField(11)}}">

                        <div class="mb-3">
                            <label for="travelling_on">Travelling On</label>
                            <input type="date" class="form-control" id="travelling_on" name="travelling_on" required>
                        </div>

                        <div class="mb-3 return-date" style="display: none;">
                            <label for="return">Return</label>
                            <input type="date" class="form-control" id="return" name="return">
                        </div>

                        <div class="mb-3">
                            <label for="flight_from">Flight From</label>
                            <input type="text" class="form-control" id="flight_from" name="flight_from" value="{{$flight->getTranslatedExtraField(3)}}, {{title($flight->getTranslatedExtraField(4))}}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="flight_to">Flight To</label>
                            <input type="text" class="form-control" id="flight_to" name="flight_to" value="{{$flight->getTranslatedExtraField(6)}}, {{title($flight->getTranslatedExtraField(7))}}" readonly>
                        </div>



                    
                        <div class="mb-3">
                            <label for="">Adult (18+ years)</label>
                            <select class="form-control" name="adults" id="" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="">Children (0-12 years)</label>
                            <select class="form-control" name="childrens" id="">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="">Payment Method</label>
                            <select class="form-control" name="payment_method" id="payment_method" required>
                                <option value="stripe">Credit Card</option>
                                <option value="paypal">PayPal</option>
                                <option value="paystack">Paystack</option>
                            </select>
                        </div>
                       

                        <div class="mb-3 totalpay">
                            {{ __('frontend.total_payment') }}
                            <strong>$<span id="totalAmount"></span></strong>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">{{ __('frontend.book_now') }}</button>
                    </form>
                </div>
                @endif


                <div class="helpbox">
                    <h4>{{ __('frontend.need_help') }}</h4>    
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>    
                    <div class="phone"><i class="fas fa-phone-alt"></i> +9212345678</div>    
                    <div class="phone"><i class="fas fa-envelope"></i> info@travelin.com</div>    
                </div>

            </div>
        </div>
    

  </div>
</div>


<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
       <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Login/Register</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <div class="modal-body p-5">
       <div class="text-center">
          <a href="login.html" class="btn btn-white me-2">Login</a>
          <a href="register.html"  class="btn btn-primary">Register</a>
       </div>
       </div>
       
    </div>
    </div>
 </div>

 @push('js')
 <script>
    $(document).ready(function () {
        // Initialize datepickers
        var today = new Date().toISOString().split('T')[0];
        $('#travelling_on').attr('min', today);
        $('#return').attr('min', today);

        // Handle one-way/two-way selection
        $('input[name="one_way_or_two_way"]').change(function() {
            if ($(this).val() === 'Two Way') {
                $('.return-date').show();
                $('#return').prop('required', true);
            } else {
                $('.return-date').hide();
                $('#return').prop('required', false);
                $('#return').val('');
            }
            calculateTotalAmount();
        });

        // Update return date min date when travelling date changes
        $('#travelling_on').change(function() {
            $('#return').attr('min', $(this).val());
            if ($('#return').val() && $('#return').val() < $(this).val()) {
                $('#return').val($(this).val());
            }
        });

        // Function to calculate the total amount
        function calculateTotalAmount() {
            var oneWayPrice = parseFloat($('[name="one_way_price"]').val());
            var twoWayPrice = parseFloat($('[name="two_way_price"]').val());
            var selectedPrice = ($('[name="one_way_or_two_way"]:checked').val() === 'One Way') ? oneWayPrice : twoWayPrice;
            var adults = parseInt($('[name="adults"]').val());
            var childrens = parseInt($('[name="childrens"]').val());
            
            var totalAmount = (selectedPrice * adults) + (selectedPrice * 0.75 * childrens);
            $('#totalAmount').text(totalAmount.toFixed(2));
            $('#price').val(totalAmount.toFixed(2));
        }

        // Calculate total when passenger numbers change
        $('[name="adults"], [name="childrens"]').change(function() {
            calculateTotalAmount();
        });

        // Initial calculation
        calculateTotalAmount();
    });
</script>

 @endpush


</x-app-layout>