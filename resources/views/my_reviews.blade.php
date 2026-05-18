<x-app-layout>
   <!-- Page title start -->

<section class="bookings-hero">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">{{ __('frontend.my_reviews') }}</h1>
            </div>
        </div>
    </section>
<!-- Page title end -->


<!-- Page content start -->
<div class="innerpagewrap">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
              @include('components.user-sidebar')
            </div>
            <div class="col-lg-9">
              <?php $hotel_bookings = App\Models\Hotels::where('user_id', auth()->user()->id)->get();  ?>
             

              


              <div class="dashtask">
              <div class="sortby d-flex justify-content-between mb-3 align-items-center">
                  <h3>My Reviews</h3>                  
              </div>
              <div class="table-responsive">
              <table class="table table-hover table-striped table-bordered myjobtable">
                  <thead>
                      <tr>
                          <th>Hotel</th>
                          <th>Review</th>                            
                          <th>Rating</th>
                          <th>Dated</th>
                      </tr>
                  </thead>
                  <tbody>
                      @if(null!==($reviews) && count($reviews))
                      @foreach($reviews as $review)
                      <tr>
                        <?php $hotel = App\Models\ModulesData::where('id',$review->hotel_id)->first(); ?>
                          <td><a href="{{ route('hotel.detail', $hotel->slug) }}">{{$hotel->title}}</a></td>
                          <td>{{$review->review}}</td>  
                          <td>{{$review->rating}} Star</td>  
                          <td>{{ date('d M Y, h:i A', strtotime($review->created_at)) }}</td>
   
                          
                      </tr>
                      @endforeach
                      @else
                      <tr><td colspan="8">No Reviews available</td></tr>
                      @endif
                                                            
                  </tbody>
              </table>
              </div>
              </div>



            



            </div>
          </div>
    </div>
    </div>
<!--  end -->


</x-app-layout>
