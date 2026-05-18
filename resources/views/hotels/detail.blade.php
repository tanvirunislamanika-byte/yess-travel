<x-app-layout>
<!-- Page title start -->
<div class="pageheader">            
    <div class="container">
        <h1>{{$hotel->getTranslatedTitle()}}</h1>
    </div>
</div>
<?php if(auth()->user()){
    $user = auth()->user();
    $booking_detail = App\Models\Hotels::where('user_id',$user->id)->where('hotel_id',$hotel->id)->where('booking_status','Confirmed')->where('rating','!=','completed')->first();
    //dd($booking_detail);
 } ?>
<!-- Page title end -->

<!-- Top Hotels Section -->
<div class="innerpagewrap">
  <div class="container">
    <div class="hotelmaininfo">

        <div class="row">
    
                <div class="col-md-8">    
                    <h1>{{$hotel->getTranslatedTitle()}}  <span class="badge badge-xs bg-success rounded-pill ms-2"><i class="fas fa-certificate"></i> {{title($hotel->getTranslatedExtraField(23))}} {{title($hotel->getTranslatedExtraField(2))}} </span></h1>    
                    <div class="hoteldtlts">
                    <div class="ins34"><i class="fas fa-hotel" aria-hidden="true"></i> {{title($hotel->getTranslatedExtraField(2))}}    </div>
                    <div class="ins34"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{$hotel->getTranslatedExtraField(18)}} <a href="#" target="_blank">{{ __('frontend.view_location') }}</a></div>
                    <div class="tprating">
                    
                    <span class="badge badge-warning badge-xs text-gray-9 fs-13 fw-medium me-2"><span class="average-ratings"></span> / 5.0</span> ({{ __('frontend.based_on') }}  <span style="padding: 0 5px 0 5px;" class="total-reviews"></span>  {{ __('frontend.reviews') }})</div>
                    </div>
                    
                      
                </div>  
    
                <div class="col-md-4"><div class="prices"><span>{{ __('frontend.price') }}</span> {{ \App\Helpers\CurrencyHelper::formatPrice($hotel->getTranslatedExtraField(1)) }}<span>/{{ __('frontend.room') }}</span></div></div>      
            </div>
    
         </div>


        <div class="row">
            <div class="col-lg-8">
            <div class="gallery-container">
                <div class="galleryview owl-carousel owl-theme">
                    <?php $images = explode(',', $hotel->images); ?>
                    @if($images)
                        @foreach($images as $image)
                            <?php $src = asset('images/'.$image); ?>
                            <div class="item">
                                <a href="{{$src}}" class="image-popup">
                                    <img src="{{$src}}" alt="Gallery Image">
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="gallery-thumbnails owl-carousel owl-theme">
                    @if($images)
                        @foreach($images as $image)
                            <?php $src = asset('images/'.$image); ?>
                            <div class="item">
                                <img src="{{$src}}" alt="Thumbnail Image">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
                   

                <div class="detcontent">
                    <h3>{{ __('frontend.overview') }}</h3>
                    <p>{!!$hotel->description!!}</p>
                </div>

                <div class="detcontent facilitiesbg">
                    <h3>{{ __('frontend.hotel_details_facilities') }}</h3>
                    <ul class="row align-items-center facilitieslist">                      
                     <li class="col-lg-3"><span class="text-fst"><i class="fas fa-users"></i>{{ __('frontend.adults_per_room') }}: <strong>{{$hotel->extra_field_11}}</strong></span></li>  
                        
                        @if(title($hotel->extra_field_3)=='Yes')
                        <li class="col-lg-3"><span class="text-fst"><i class="fas fa-city"></i>{{ __('frontend.city_view') }}</span></li>
                        @endif
                        @if(title($hotel->extra_field_4)=='Yes')
                        <li class="col-lg-3"><span class="text-fst"><i class="fas fa-bed"></i>{{ __('frontend.master_room') }}</span></li>
                        @endif
                        @if(title($hotel->extra_field_5)=='Yes')
                        <li class="col-lg-3"><span class="text-fst"><i class="fas fa-glass-martini-alt"></i>{{ __('frontend.bar') }}</span></li>
                        @endif
                        @if(title($hotel->extra_field_6)=='Yes')
                        <li class="col-lg-3"><span class="text-fst"><i class="fas fa-wifi"></i>{{ __('frontend.free_wifi') }}</span></li>
                        @endif
                        @if(title($hotel->extra_field_7)=='Yes')
                        <li class="col-lg-3"><span class="text-fst"><i class="fas fa-bath"></i>{{ __('frontend.private_bathroom') }}</span></li>
                        @endif
                        @if(title($hotel->extra_field_8)=='Yes')
                        <li class="col-lg-3"><span class="text-fst"><i class="fas fa-snowflake"></i>{{ __('frontend.air_conditioning') }}</span></li>
                        @endif
                        @if(title($hotel->extra_field_9)=='Yes')
                        <li class="col-lg-3"><span class="text-fst"><i class="fas fa-cash-register"></i>{{ __('frontend.refrigerator') }}</span></li>
                        @endif
                        @if(title($hotel->extra_field_10)=='Yes')
                        <li class="col-lg-3"><span class="text-fst"><i class="fas fa-phone-alt"></i>{{ __('frontend.telephone') }}</span></li>  
                        @endif
                        
                        
                       
                        @if(title($hotel->extra_field_12)=='Yes')
                        <li class="col-lg-3"><span class="text-fst"><i class="fas fa-dumbbell"></i>{{ __('frontend.gym') }}</span></li>  
                        @endif
                        @if(title($hotel->extra_field_13)=='Yes')
                        <li class="col-lg-3"><span class="text-fst"><i class="fas fa-smoking-ban"></i> {{ __('frontend.no_smoking') }}</span></li>
                        @endif
                        @if(title($hotel->extra_field_14)=='Yes')
                        <li class="col-lg-3"><span class="text-fst"><i class="fas fa-person-booth"></i> {{ __('frontend.room_services') }}</span></li>
                        @endif
                        @if(title($hotel->extra_field_15)=='Yes')
                        <li class="col-lg-3"><span class="text-fst"><i class="fas fa-car"></i> {{ __('frontend.pick_and_drop') }}</span></li>
                        @endif
                        @if(title($hotel->extra_field_16)=='Yes')
                        <li class="col-lg-3"><span class="text-fst"><i class="fas fa-swimming-pool"></i> {{ __('frontend.swimming_pool') }}</span></li>
                        @endif
                        @if(title($hotel->extra_field_17)=='Yes')
                        <li class="col-lg-3"><span class="text-fst"><i class="fas fa-concierge-bell"></i> {{ __('frontend.front_desk_24h') }} </span></li>
                        @endif
                    </ul>
                </div>

                <!-- Services -->
                <div class="detcontent hotelservices">
                    <h3>{{ __('frontend.services') }}</h3>
                    <?php 
                    $service_ids = isset($hotel) ? json_decode($hotel->services, true) ?? [] : [];
                    $cusines_ids = isset($hotel) ? json_decode($hotel->cusines, true) ?? [] : [];

                    $services = count($service_ids) > 0 ? App\Models\ModulesData::whereIn('id', $service_ids)->get() : collect();
                    $cusines = count($cusines_ids) > 0 ? App\Models\ModulesData::whereIn('id', $cusines_ids)->get() : collect();
                    ?>

                    <ul class="row hotelserv">
                        @if(null!==($services))
                        @foreach($services as $service)
                        <li class="col-lg-4 col-md-6"><i class="far fa-check-circle"></i> <span>{{$service->title}}</span></li>
                        @endforeach
                        @endif
                       
                    </ul>
                </div>

                <!-- Services -->
                <div class="detcontent hotelservices">
                    <h3>{{ __('frontend.cuisine') }}</h3>
                    <ul class="row hotelserv">
                        @if(null!==($cusines))
                        @foreach($cusines as $cusine)
                        <li class="col-lg-4 col-md-6"><i class="far fa-check-circle"></i> <span>{{$cusine->title}}</span></li>
                        @endforeach
                        @endif
                    </ul>
                </div>

                












                <!-- Hotel Rules -->
                 <div class="hotelrules detcontent">
                    <h3>{{ __('frontend.checkin_checkout_policy') }}</h3>
                        <p>{{$hotel->extra_field_21}}</p>

                        <div class="d-flex mt-3">
                            <i class="far fa-clock"></i>
                                <div class="ms-2">
                                    <p class="mb-1 mt-0">{{ __('frontend.check_out_time') }}</p>
                                    <strong>{{ date('h:i A', strtotime($hotel->extra_field_22)) }}</strong>

                                </div>
                            </div>
                   

                 </div>

                
                @if(session()->has('message.added'))
                    <div class="alert alert-{{ session('message.added') }} alert-dismissible fade show" role="alert">
                        <strong>{{__('Congratulations')}}!</strong> {!! session('message.content') !!}.
                    </div>
                @endif

                <!-- Reviews -->
                 <div class="reviewswrap">
                    <div class="revtop">
                        <h3>Reviews</h3>
                        <div class="overallrate">
                            <strong class="average-ratings">Loading...</strong> / 5.0 <span>Based On <strong class="total-reviews">Loading...</strong> Reviews</span>
                        </div>
                        @if(auth()->user() && $booking_detail)
                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#add_review" class="btn btn-primary write-review"><i class="fas fa-edit"></i> Write a Review</a>
                        @endif
                    </div>

                    <ul class="reviewslist mb-0 reviews-list" id="reviews-list">
                        <!-- Reviews will be dynamically loaded here -->
                    </ul>

                  
                </div>



            </div>
            <div class="col-lg-4">
                
                
                
                <div class="bookingsidebar">
                    <h4>{{ __('frontend.book_now') }}</h4>
                    <form action="{{route('hotel.booking.store', ['hotel_id' => $hotel->id])}}" method="post">
                        @csrf
                        <input type="hidden" name="hotel_id" value="{{$hotel->id}}">
                        <input type="hidden" id="price" name="price" value="">
                        <div class="mb-3">
                            <label for="">Travelling From</label>
                            <input type="text" class="form-control" placeholder="Your Location" name="travelling_from" required>
                        </div>
                        <div class="mb-3">
                            <label for="">{{ __('frontend.check_in') }}</label>
                            <input type="date" class="form-control" placeholder="Choose Date" name="check_in" required id="check_in">
                        </div>
                        <div class="mb-3">
                            <label for="">{{ __('frontend.check_out') }}</label>
                            <input type="date" class="form-control" placeholder="Choose Date" name="check_out" required id="check_out">
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
                            <select class="form-control" name="childrens" id="" required>
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
                            <label for="">{{ __('frontend.rooms') }}</label>
                            <div class="number">
                                <span class="minus" data-target="roomsQuantity">-</span>
                                <input id="roomsQuantity" type="text" value="1" name="rooms" />
                                <span class="plus" data-target="roomsQuantity">+</span>
                            </div>
                            <p>Charges are per night per room.</p>                         
                        </div>
                        <div class="mb-3 totalpay">
                            {{ __('frontend.total_payment') }}
                            <strong><span id="totalAmount">{{ \App\Helpers\CurrencyHelper::formatPrice($hotel->extra_field_1) }}</span></strong>
                        </div>
                        @if(auth()->user())
                        <button type="submit" class="btn btn-primary w-100">{{ __('frontend.book_now') }}</button>
                        @else
                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#registerModal" class="btn btn-primary w-100 mb-3">{{ __('frontend.book_now') }}</a>
                        @endif
                    </form>
                </div>


                <div class="detcontent mb-0 border-0 pb-0">
                    <h3>Find Us on Map</h3>

                    <iframe src="https://maps.google.it/maps?q={{ $hotel->extra_field_18 }}&output=embed" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>



                  

                </div>

                <div class="helpbox mt-5">
                    <h4>{{ __('frontend.need_help') }}</h4>    
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>    
                    <div class="phone"><i class="fas fa-phone-alt"></i> +9212345678</div>    
                    <div class="phone"><i class="fas fa-envelope"></i> info@travelin.com</div>    
                </div>

            </div>
        </div>
    




  </div>
</div>




 <!-- Review Modal -->
 
 @if(auth()->user() && $booking_detail)
 <div class="modal fade" id="add_review">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <h5>Write a Review</h5>
                <a href="javascript:void(0);" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x fs-16"></i></a>
            </div>
            <form id="review-form">
                <div class="modal-body pb-0">
                    <h5>You are leaving a review for <strong>{{ $hotel->getTranslatedTitle() }}</strong></h5>
                    <div class="row">
                        <!-- Rating -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Your Rating <span class="text-danger">*</span></label>
                                <div class="selection-wrap">
                                    <div class="d-inline-block">
                                        <div class="rating-selction">
                                            <input type="radio" name="rating" value="5" id="rating5">
                                            <label for="rating5"><i class="fas fa-star"></i></label>
                                            <input type="radio" name="rating" value="4" id="rating4">
                                            <label for="rating4"><i class="fas fa-star"></i></label>
                                            <input type="radio" name="rating" value="3" id="rating3">
                                            <label for="rating3"><i class="fas fa-star"></i></label>
                                            <input type="radio" name="rating" value="2" id="rating2">
                                            <label for="rating2"><i class="fas fa-star"></i></label>
                                            <input type="radio" name="rating" value="1" id="rating1">
                                            <label for="rating1"><i class="fas fa-star"></i></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Main Reason -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Main reason for your rating <span class="text-danger">*</span></label>
                                <select name="reason" class="form-control" id="reason">
                                    <option value="">Select Reason</option>
                                    <option value="Comfortable Beds">Comfortable Beds</option>
                                    <option value="Delicious Food">Delicious Food</option>
                                    <option value="Friendly Staff">Friendly Staff</option>
                                    <option value="Great Amenities">Great Amenities</option>
                                    <option value="Slow Check-Out">Slow Check-Out</option>
                                    <option value="Noisy Environment">Noisy Environment</option>
                                </select>
                            </div>
                        </div>
                        <!-- Review Text -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Write Your Review <span class="text-danger">*</span></label>
                                <textarea name="review" class="form-control" id="review" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-md"><i class="isax isax-edit-2 me-1"></i> Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
    <!-- /Review Modal -->




<!-- Reply Review Modal -->
@if(auth()->user() && auth()->user()->id == 1)
<div class="modal fade" id="replyreview" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
<div class="modal-dialog modal-md modal-dialog-centered">
<div class="modal-content">
    <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Reply to Feedback</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="alert alert-warning">Please note that after leaving  your reply, you cannot make any changes or remove it.</div>
    


        <h5>Feedback</h5>
        <p id="feedback_p"></p>


        <hr>
        <form action="{{route('reviews.store_reply')}}" method="post">
            @csrf
            <input type="hidden" name="review_id" id="review_id">                    
            <div class="mb-3">
            <label class="fieldlabels">Reply Comment</label> 
            <textarea name="reply" id="" class="form-control" placeholder="Please share your feedback response comments here."></textarea>
            </div>

            <input type="submit" name="submit" class="btn btn-primary" value="Submit">


        </form>
    </div>
    
</div>
</div>
</div>
@endif












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
 <!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    
    $(document).ready(function() {
    const hotelId = {{$hotel->id}};  // Get the hotel ID from the hidden input field
    
    // Function to load reviews
    function loadReviews() {
        $.ajax({
            url: `{{url('/')}}/hotels/${hotelId}/reviews`,  // Adjust this URL based on your route
            type: 'GET',
            dataType: 'json',
            success: function(response) {
    if (response.status === 'success') {
        if (response.total_reviews > 0) {
            $('#reviews-list').html(response.review_html);
        } else {
            $('#reviews-list').html('<li class="text-center">No review posted yet.</li>');
        }
        $('.average-ratings').html(response.average_rating);
        $('.total-reviews').html(response.total_reviews);
    }
},
            error: function(xhr, status, error) {
                console.error('Error fetching reviews:', error);
            }
        });
    }

    // Initial load of reviews
    loadReviews();

    // Submit review form via AJAX
    $('#review-form').submit(function(e) {
        e.preventDefault();
        const rating = $('input[name="rating"]:checked').val();
        const reason = $('#reason').val();
        const reviewText = $('#review').val();
        //alert(reason);

        $.ajax({
            url: `{{url('/')}}/hotels/${hotelId}/review`,  // Adjust this URL based on your route
            type: 'POST',
            data: {
                rating: rating,
                review_text: reviewText,
                reason: reason,
                hotel_id: hotelId,
                _token: $('meta[name="csrf-token"]').attr('content')  // CSRF token for security
            },
            dataType: 'json',
            success: function(response) {
                    $('#add_review').modal('hide');
                    $('.write-review').hide();
                    loadReviews();  // Reload reviews after submission
                    Swal.fire({
                        icon: 'success',
                        title: 'Review Submitted!',
                        text: 'Thank you for your review.',
                        showConfirmButton: true,
                    });
                
            },
            error: function(xhr, status, error) {
                console.error('Error submitting review:', error);
                alert('Something went wrong!');
            }
        });
    });

    // Load more reviews (optional, implement if needed)
    $('#load_reviews').click(function() {
        loadReviews();
    });
});

    $(document).ready(function () {
        // Function to calculate the total amount
        function calculateTotalAmount() {
            var rooms = parseInt($('#roomsQuantity').val());

            // Get the check-in and check-out dates
            var checkInDate = new Date($('#check_in').val());
            var checkOutDate = new Date($('#check_out').val());

            // Check if either date is not selected
            if (isNaN(checkInDate) || isNaN(checkOutDate)) {
                // Set both dates to the current day
                checkInDate = new Date();
                checkOutDate = new Date();
                // Set checkOutDate to the next day
                checkOutDate.setDate(checkOutDate.getDate() + 1);
                // Update the input fields
                $('#check_in').val(formatDate(checkInDate));
                $('#check_out').val(formatDate(checkOutDate));
            }

            // Calculate the number of days
            var days = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));

            // Convert the room price to a JavaScript numeric value
            var roomPrice = parseFloat("{{ filter_var($hotel->extra_field_1, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) }}");

            // Check if roomPrice is a valid numeric value
            if (isNaN(roomPrice)) {
                console.error("Error: Room price is not a valid numeric value.");
                return;
            }

            // Calculate total amount
            var totalAmount = rooms * roomPrice * days;
            $('#totalAmount').text(totalAmount.toFixed(2)); // Display total amount with two decimal places
            $('#price').val(totalAmount.toFixed(2));
        }

        // Attach the calculateTotalAmount function to relevant form elements' change events
        $('#roomsQuantity, #check_in, #check_out').change(calculateTotalAmount);

        // Trigger the calculation on page load
        calculateTotalAmount();

        // Function to format a date as "YYYY-MM-DD"
        function formatDate(date) {
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var day = ('0' + date.getDate()).slice(-2);
            return year + '-' + month + '-' + day;
        }
    });

    $(document).on('click','.replybtn',function(){
        var review_id = $(this).data('review');
        var feedback = $(this).data('feedback');

        $('#feedback_p').html(feedback);
        $('#review_id').val(review_id);
    })
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".number .plus, .number .minus").forEach(button => {
            button.addEventListener("click", function () {
                let targetId = this.getAttribute("data-target");
                let input = document.getElementById(targetId);
                let value = parseInt(input.value);

                if (this.classList.contains("plus")) {
                    if (value < 10) input.value = value + 1; // Max 10 rooms
                } else if (this.classList.contains("minus")) {
                    if (value > 1) input.value = value - 1; // Min 1 room
                }

                var rooms = parseInt($('#roomsQuantity').val());

            // Get the check-in and check-out dates
            var checkInDate = new Date($('#check_in').val());
            var checkOutDate = new Date($('#check_out').val());

            // Check if either date is not selected
            if (isNaN(checkInDate) || isNaN(checkOutDate)) {
                // Set both dates to the current day
                checkInDate = new Date();
                checkOutDate = new Date();
                // Set checkOutDate to the next day
                checkOutDate.setDate(checkOutDate.getDate() + 1);
                // Update the input fields
                $('#check_in').val(formatDate(checkInDate));
                $('#check_out').val(formatDate(checkOutDate));
            }

            // Calculate the number of days
            var days = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));

            // Convert the room price to a JavaScript numeric value
            var roomPrice = parseFloat("{{ filter_var($hotel->extra_field_1, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) }}");

            // Check if roomPrice is a valid numeric value
            if (isNaN(roomPrice)) {
                console.error("Error: Room price is not a valid numeric value.");
                return;
            }

            // Calculate total amount
            var totalAmount = rooms * roomPrice * days;
            $('#totalAmount').text(totalAmount.toFixed(2)); // Display total amount with two decimal places
            $('#price').val(totalAmount.toFixed(2));
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let today = new Date().toISOString().split("T")[0];
        document.getElementById("check_in").setAttribute("min", today);
        document.getElementById("check_out").setAttribute("min", today);

        document.getElementById("check_in").addEventListener("change", function () {
            document.getElementById("check_out").setAttribute("min", this.value);
        });
    });
</script>

 @endpush
 
</x-app-layout>


