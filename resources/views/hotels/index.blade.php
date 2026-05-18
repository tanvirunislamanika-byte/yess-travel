<x-app-layout>
    <!-- Page title start -->
<div class="pageheader">            
    <div class="container">
        <h1>{{ __('frontend.discover_your_next_adventure') }}</h1>
    </div>
</div>
<!-- Page title end -->

<!-- Top Hotels Section -->
<div class="innerpagewrap">
  <div class="container">

    <div class="row">
        <div class="col-lg-3">
            
            <div class="filtersidebar">
                <!-- Search -->
            <form action="{{url('/hotels')}}">
                <div class="filterbox">
                  <div class="mb-3"><label for="">{{ __('frontend.destination') }}</label><input type="text" class="form-control" placeholder="{{ __('frontend.destination_placeholder') }}" name="keyword"></div>

                 <label for="">{{ __('frontend.when') }}</label><input type="date" class="form-control" placeholder="{{ __('frontend.when') }}">
                </div>

                <!-- Hotels -->
                <div class="filterbox">
                    <h5>{{ __('frontend.types_of_hotels') }}</h5>
                    @if(null!==($types = module(2)))
                    @foreach($types as $type)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" value="{{$type->id}}" <?php if(request()->type==$type->id){echo 'checked';} ?>>
                        <label class="form-check-label" for="thotels">
                           {{$type->title}}
                        </label>
                    </div>
                    @endforeach
                    @endif
                    

                </div>

                <!-- Price Range -->
                <div class="filterbox">
    <h5>{{ __('frontend.price_range') }}</h5>

    <div class="price-input">
        <div class="field">
            <span>{{ __('frontend.min') }}</span>
            <input type="number" class="input-min" name="min_price" value="{{ request('min_price', 10) }}">
        </div>
        <div class="separator">-</div>
        <div class="field">
            <span>{{ __('frontend.max') }}</span>
            <input type="number" class="input-max" name="max_price" value="{{ request('max_price', 500) }}">
        </div>
    </div>

    <div class="slider">
        <div class="progress"></div>
    </div>

    <div class="range-input">
        <input type="range" class="range-min" min="10" max="10000" name="min_price" value="{{ request('min_price', 10) }}" step="100">
        <input type="range" class="range-max" min="10" max="10000" name="max_price" value="{{ request('max_price', 500) }}" step="100">
    </div>  
</div>



                <!-- Locations -->
                <div class="filterbox">
                    <h5>{{ __('frontend.locations') }}</h5>
                    <?php $locations = App\Models\ModulesData::select('extra_field_18')->where('module_id',1)->pluck('extra_field_18')->toArray(); ?>
                    @if(null!==($locations))
                    @foreach($locations as $location)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{$location}}" name="searchlocation" <?php if(request()->searchlocation==$location){echo 'checked';} ?> id="">
                        <label class="form-check-label" for="alberta">
                          {{$location}}
                        </label>
                    </div>
                    @endforeach
                    @endif
                    

                </div>
                <div class="filterbox">
                    <h5>{{ __('frontend.services') }}</h5>
                    <?php $services = App\Models\ModulesData::where('module_id', 28)->get(); ?>
                    @if(null !== $services)
                        @foreach($services as $service)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                      value="{{$service->id}}" 
                                      name="services[]" 
                                      {{ is_array(request()->services) && in_array($service->id, request()->services) ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    {{$service->title}}
                                </label>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="filterbox">
                    <h5>{{ __('frontend.cuisine') }}</h5>
                    <?php $cusines = App\Models\ModulesData::where('module_id', 29)->get(); ?>
                    @if(null !== $cusines)
                        @foreach($cusines as $cusine)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                      value="{{$cusine->id}}" 
                                      name="cusines[]" 
                                      {{ is_array(request()->cusines) && in_array($cusine->id, request()->cusines) ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    {{$cusine->title}}
                                </label>
                            </div>
                        @endforeach
                    @endif
                </div>


              
                <button type="submit" class="btn btn-primary w-100">{{ __('frontend.apply_filter') }}</button>
                    

</form>
            </div>
            
    </div>
        

        <div class="col-lg-9">
        @if(null!==($hotels))
    <div class="topsort">
        <strong>{{ __('frontend.showing_search_results', ['count' => count($hotels)]) }}</strong>
    </div>

    <div class="destinationList">
        <ul class="row">
        @foreach($hotels as $hotel)
        <li class="col-lg-4">
            <div class="hotel-card">
                <div class="hotel-card-image">
                    <a href="{{ route('hotel.detail', $hotel->slug) }}" class="hotel-image-link">
                        <img src="{{ asset('images/' . $hotel->image) }}" alt="{{ $hotel->getTranslatedTitle() }}" class="hotel-img">
                    </a>
                    <div class="hotel-badges">
                        <div class="hotel-badge hotel-type-badge">
                            <i class="fa fa-hotel"></i>
                            {{ title($hotel->getTranslatedExtraField(2)) }}
                        </div>
                        <div class="hotel-badge hotel-star-badge">
                            <i class="fa fa-star"></i>
                            {{ title($hotel->getTranslatedExtraField(23)) }}
                        </div>
                    </div>
                </div>
                <div class="hotel-card-content">
                    <div class="htl-card-header">
                        <h3 class="hotel-card-title">
                            <a href="{{ route('hotel.detail', $hotel->slug) }}">{{ $hotel->getTranslatedTitle() }}</a>
                        </h3>
                        <div class="hotel-rating">
                            <?php 
                            $averageRating = $hotel->reviews()->avg('rating');
                            $averageRating = number_format($averageRating, 1);
                            ?>
                            <div class="rating-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $averageRating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="rating-text">{{ $averageRating }}/5</span>
                            </div>
                        </div>
                    </div>
                    <div class="hotel-card-meta">
                        <div class="hotel-meta-item">
                            <i class="fa fa-map-marker"></i>
                            <span>{{ $hotel->getTranslatedExtraField(18) }}</span>
                        </div>
                        <div class="hotel-meta-item">
                            <i class="fa fa-users"></i>
                            <span>{{ $hotel->getTranslatedExtraField(11) }} {{ __('frontend.people_per_room') }}</span>
                        </div>
                    </div>
                    <div class="hotel-card-footer">
                        <div class="hotel-price-section">
                            <div class="hotel-price">
                                <span class="price-amount">{{ \App\Helpers\CurrencyHelper::formatPrice($hotel->getTranslatedExtraField(1)) }}</span>
                                <span class="price-unit">{{ __('frontend.per_night') }}</span>
                            </div>
                        </div>
                        <div class="hotel-actions">
                            <a href="{{ route('hotel.detail', $hotel->slug) }}" class="hotel-details-btn">{{ __('frontend.view_details') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        @endforeach
        </ul>
    </div>

    <!-- Pagination -->
    <ul class="pagination justify-content-center">
        <li class="page-item {{ $hotels->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $hotels->previousPageUrl() }}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">{{ __('frontend.previous') }}</span>
            </a>
        </li>

        @php
            $totalPages = $hotels->lastPage();
            $currentPage = $hotels->currentPage();
            $numPagesToShow = 5; // Number of pages to show before and after the current page
            $startPage = max($currentPage - $numPagesToShow, 1);
            $endPage = min($currentPage + $numPagesToShow, $totalPages);
        @endphp

        @for ($i = $startPage; $i <= $endPage; $i++)
            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                <a class="page-link" href="{{ $hotels->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        <li class="page-item {{ $hotels->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link" href="{{ $hotels->nextPageUrl() }}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">{{ __('frontend.next') }}</span>
            </a>
        </li>
    </ul>

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
            <h2>{{ __('frontend.discount_25_off') }}</h2>
            <h3>{{ __('frontend.explore_world_destinations') }}</h3>
            <a href="#" class="btn btn-sec">{{ __('frontend.book_now') }}</a>
        </div>
    </div>
    <div class="col-lg-6">
      <div class="fligtwidget">
          <h2>{{ __('frontend.discount_25_off') }}</h2>
          <h3>{{ __('frontend.experience_world_extraordinary') }}</h3>
          <a href="#" class="btn btn-sec">{{ __('frontend.book_now') }}</a>
      </div>
  </div>
  </div>
</div>
</x-app-layout>