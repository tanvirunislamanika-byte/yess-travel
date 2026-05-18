<x-app-layout>
<!-- Page title start -->
<div class="pageheader">            
    <div class="container">
        <h1>Discover Your Next Adventure with Us</h1>
    </div>
</div>
<!-- Page title end -->

<!-- Top Hotels Section -->
<div class="innerpagewrap">
  <div class="container">

    <div class="row">
        <div class="col-lg-3">
            <div class="filtersidebar">                
                <form action="{{url('flights')}}">
                <!-- Locations -->
                <div class="filterbox">

                    <h5>Search Filter</h5>                    
                    <div class="mb-3">
                        <label for="">From</label>
                        <input type="text" class="form-control" name="from_location" placeholder="e.g. Alberta">
                    </div>
                    <div class="mb-3">
                        <label for="">To</label>
                        <input type="text" class="form-control" name="to_location" placeholder="e.g. New York">
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="triptype" id="onewayflight" checked="">
                          <label class="form-check-label" for="onewayflight">
                             One Way
                          </label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="triptype" id="twowayflight" checked="">
                          <label class="form-check-label" for="twowayflight">
                             Two Way
                          </label>
                        </div>
                      </div>


                      <div class="mb-3"><label for="">Travelling On</label><input type="date" name="travelling_date" class="form-control" placeholder="When"></div>
                      <div class="mb-3"><label for="">Return</label><input name="return_date" type="date" class="form-control" placeholder="When"></div>


                </div>

                <!-- Airline -->
                <div class="filterbox">
                    <h5>Airline</h5>
                    @if(null!==($airlines = module(4)))
                    @foreach($airlines as $airline)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{$airline->id}}" name="airline" id="airline{{$airline->id}}" <?php if(request()->airline==$airline->id){echo 'checked';} ?>>
                        <label class="form-check-label" for="airline{{$airline->id}}">
                          {{$airline->title}}
                        </label>
                    </div>
                    @endforeach
                    @endif

                </div>

                <!-- Price Range -->
                <div class="filterbox">
                    <h5>Price Range</h5>

                    <div class="price-input">
                        <div class="field">
                          <span>Min</span>
                          <input type="number" class="input-min" value="10">
                        </div>
                        <div class="separator">-</div>
                        <div class="field">
                          <span>Max</span>
                          <input type="number" class="input-max" value="500">
                        </div>
                      </div>

                      <div class="slider">
                        <div class="progress"></div>
                      </div>
                      <div class="range-input">
                        <input type="range" class="range-min" min="0" max="10000" name="min_price" value="10" step="100">
                        <input type="range" class="range-max" min="0" max="10000" name="max_price" value="500" step="100">
                      </div>  
                </div>


                <!-- Stops -->
                

              
                <button class="btn btn-primary w-100">Apply Filter</button>
                    

                </form>
            </div>
        </div>

        <div class="col-lg-9">

            @if(isset($error))
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @else
                <div class="search-results-header">
                    <strong>Showing {{ count($flights ?? []) }} Search Results</strong>
                </div>

                @if(count($flights ?? []) > 0)
                    <div class="flights-list">
                        @foreach($flights as $flight)
                            <div class="flight-card">
                                <div class="airline-info">
                                    <img src="{{ $flight['owner']['logo_symbol_url'] ?? '' }}" alt="{{ $flight['owner']['name'] ?? '' }}" class="airline-logo">
                                    <span class="airline-name">{{ $flight['owner']['name'] ?? '' }}</span>
                                </div>
                                
                                <div class="flight-details">
                                    <div class="route">
                                        <span class="origin">{{ $flight['slices'][0]['origin']['city_name'] ?? '' }}</span>
                                        <span class="arrow">→</span>
                                        <span class="destination">{{ $flight['slices'][0]['destination']['city_name'] ?? '' }}</span>
                                    </div>
                                    
                                    <div class="time">
                                        <span class="departure">{{ $flight['slices'][0]['segments'][0]['departing_at'] ?? '' }}</span>
                                        <span class="arrival">{{ $flight['slices'][0]['segments'][0]['arriving_at'] ?? '' }}</span>
                                    </div>
                                    
                                    <div class="cabin-class">
                                        {{ $flight['slices'][0]['segments'][0]['passengers'][0]['cabin_class_marketing_name'] ?? '' }}
                                    </div>
                                </div>
                                
                                <div class="price">
                                    <span class="amount">{{ $flight['total_amount'] ?? '' }} {{ $flight['total_currency'] ?? '' }}</span>
                                    <button class="btn btn-primary">Book Now</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        No flights found for your search criteria.
                    </div>
                @endif
            @endif

        </div>
    </div>
    
    

  </div>
</div>

<!-- Widgets -->
<div class="container pb-5">
  <div class="row">
    <div class="col-lg-6">
        <div class="hotelwidget">
            <h2>25% Off</h2>
            <h3>Explore the World, One Destination at a Time</h3>
            <a href="#" class="btn btn-sec">Book Now</a>
        </div>
    </div>
    <div class="col-lg-6">
      <div class="fligtwidget">
          <h2>25% Off</h2>
          <h3>Experience the World in Extraordinary Ways</h3>
          <a href="#" class="btn btn-sec">Book Now</a>
      </div>
  </div>
  </div>
</div>
</x-app-layout>