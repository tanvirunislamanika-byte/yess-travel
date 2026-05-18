<x-admin-layout>
    <div class="container-xl px-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-10">Flight booking for 
                        @if($hotelM)
                            <strong><a href="{{route('flights.detail',$hotelM->slug)}}" target="_blank">{{$hotelM->title}} <i class="fas fa-external-link-alt"></i></a></strong>
                        @else
                            <strong>{{$hotel->flight_from}} to {{$hotel->flight_to}}</strong>
                        @endif
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-joined border-0 add-button">
                            
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="card-header border-bottom" style="background-color: #e7eaf0;">
                <!-- Wizard navigation-->
                <div class="nav nav-pills nav-justified flex-column flex-xl-row nav-wizard" id="cardTab" role="tablist">
                    <!-- Wizard navigation item 1-->
                    <?php $statues = App\Models\ModulesData::where('module_id',26)->get(); ?>
                    @if(null!==($statues))
                        <label>Booking Status</label>
                        <select class="form-control" name="status" id="status" onchange="confirmAjaxRequest({{$hotel->id}}, this.value, '{{$hotelM ? $hotelM->title : $hotel->flight_from . " to " . $hotel->flight_to}}')">
                            @foreach($statues as $status)
                                <option @if($status->title == $hotel->booking_status) selected @endif value="{{$status->title}}">{{$status->title}}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
            <br>

                @if(session()->has('message.added'))
                <div class="alert alert-{!! session('message.added') !!} alert-dismissible fade show" role="alert">
                  <strong>{{__('Congratulations')}}!</strong> {!! session('message.content') !!}.
                </div>
                @endif
                <div class="row">
                    <!-- Task-detail-right start -->
                    <div class="col-xl-6 col-lg-12 push-xl-8">
                    <h3 class="ml-4 ps-2 font-bold">Booking details</h3>
                        <div class="card m-4">

                            <div class="card-block task-details">
                                <table class="table table-border">
                                    <tbody>
                                        <tr>
                                            <td><i class="icofont icofont-contrast"></i> {{__('Name')}}:</td>
                                            <td class="text-right"><span class="f-right"><a> {{$user->name}}</a></span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="ti-email"></i> {{__('Email Address')}}:</td>
                                            <td class="text-right">{{$user->email}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="ti-email"></i> {{__('Phone')}}:</td>
                                            <td class="text-right">{{$user->phone}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="ti-email"></i> {{__('User Location')}}:</td>
                                            <td class="text-right">{{title($user->country)}}, {{$user->state}}, {{$user->city}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="icofont icofont-id-card"></i> {{__('Travelling on')}}:</td>
                                            <td class="text-right">{{date('d M, Y',strtotime($hotel->travelling_on))}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="icofont icofont-id-card"></i> {{__('Return')}}:</td>
                                            <td class="text-right">{{date('d M, Y',strtotime($hotel->return))}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="icofont icofont-id-card"></i> {{__('Flight Type')}}:</td>
                                            <td class="text-right">{{$hotel->one_way_or_two_way}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="icofont icofont-id-card"></i> {{__('Flight From')}}:</td>
                                            <td class="text-right">{{$hotel->flight_from}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="icofont icofont-id-card"></i> {{__('Flight To')}}:</td>
                                            <td class="text-right">{{$hotel->flight_to}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="icofont icofont-id-card"></i> {{__('Adult (18+ years)')}}:</td>
                                            <td class="text-right">{{$hotel->adults}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="icofont icofont-id-card"></i> {{__('Children (0-12 years)')}}:</td>
                                            <td class="text-right">{{$hotel->childrens}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="icofont icofont-contrast"></i> {{__('Adult (18+ years)')}}:</td>
                                            <td class="text-right"><span class="f-right"><a> {{$hotel->adults}}</a></span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="icofont icofont-id-card"></i> {{__('Children (0-12 years)')}}:</td>
                                            <td class="text-right">{{$hotel->childrens}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="ti-email"></i> {{__('Booking Price')}}:</td>
                                            <td class="text-right">{{'USD '.$hotel->price}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="icofont icofont-id-card"></i> {{__('Payment Status')}}:</td>
                                            <td class="text-right">{{$hotel->status}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="icofont icofont-id-card"></i> {{__('Payment Via')}}:</td>
                                            <td class="text-right">{{$hotel->payment_via}}</td>
                                        </tr>
                                        
                                        
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        
                        
                        
                        
                    </div>
                    <!-- Task-detail-right start -->
                   
               
                </div>
            </div>
            <!-- Page body end -->
         </div>
         @push('js')
         <script>
            function confirmAjaxRequest(hotelId, statusTitle, hotel) {
                var confirmation = confirm("Are you sure you want to send a request for hotel " + hotel + " with status " + statusTitle + "?");
        
                if (confirmation) {
                    // Proceed with the AJAX request here
                    // You can use XMLHttpRequest or a library like jQuery for the AJAX call
                    // Example using jQuery:
                    $.ajax({
                        url: "{{url('admin/hotel-booking-change-status')}}/"+hotelId,
                        method: 'GET',
                        data: { hotel_id: hotelId, status_title: statusTitle },
                        success: function(response) {
                            // Handle success
                        },
                        error: function(error) {
                            // Handle error
                        }
                    });
                } else {
                    // User canceled the request
                }
            }
        </script>
         @endpush
</x-admin-layout>
