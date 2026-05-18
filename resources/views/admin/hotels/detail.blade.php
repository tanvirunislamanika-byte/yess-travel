<x-admin-layout>
    <div class="container-xl px-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-10">{{__('Booking Information for')}} <strong><a href="{{route('hotel.detail',$hotelM->slug)}}" target="_blank">{{$hotelM->title}} <i class="fas fa-external-link-alt"></i></a></strong> </div>
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
                        @if($statues)
                            <label>Booking Status</label>
                            <div class="input-group">
                                <select class="form-control" name="status" id="status">
                                    @foreach($statues as $status)
                                        <option @if($status->title == $hotel->booking_status) selected @endif value="{{$status->title}}">
                                            {{$status->title}}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" onclick="confirmAjaxRequest({{$hotel->id}}, '{{ $hotelM->title }}')">Update</button>
                                </div>
                            </div>
                        @endif

                </div>
            </div>
            <br>

                @if(session()->has('message.added'))
                <div class="alert alert-{!! session('message.added') !!} alert-dismissible fade show" role="alert">
                  <strong>{{__('Congratulations')}}!</strong> {!! session('message.content') !!}.
                </div>
                @endif
                <div class="card-body">
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
                                            <td><i class="icofont icofont-id-card"></i> {{__('Check In')}}:</td>
                                            <td class="text-right">{{date('d M, Y',strtotime($hotel->created_at))}}</td>
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
                                            <td><i class="icofont icofont-contrast"></i> {{__('Travelling From')}}:</td>
                                            <td class="text-right"><span class="f-right"><a> {{$hotel->travelling_from}}</a></span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="icofont icofont-id-card"></i> {{__('Rooms')}}:</td>
                                            <td class="text-right">{{$hotel->rooms}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="ti-email"></i> {{__('Booking Price')}}:</td>
                                            <td class="text-right">{{'USD '.$hotel->price}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="icofont icofont-id-card"></i> {{__('Payment Status')}}:</td>
                                            <td class="text-right"><strong class="status">{{$hotel->status}}</strong></td>
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


            </div>
            <!-- Page body end -->
         </div>
         @push('js')
         <script>
            function confirmAjaxRequest(hotelId, hotelTitle) {
    var statusTitle = document.getElementById('status').value; // Get selected status
    var confirmation = confirm("Are you sure you want to update the status for hotel " + hotelTitle + " to " + statusTitle + "?");

    if (confirmation) {
        $.ajax({
            url: "{{ url('admin/hotel-booking-change-status') }}/" + hotelId,
            method: 'GET',
            data: { hotel_id: hotelId, status_title: statusTitle },
            success: function(response) {
                alert("Status updated successfully!"); // Show success message
                // Optionally, update UI or refresh page
            },
            error: function(error) {
                alert("Error updating status!");
            }
        });
    }
}



            document.querySelectorAll('.status').forEach(status => {
    let text = status.textContent.trim().toLowerCase();
    
    if (text === "paid") {
        status.style.color = "green";
    } else if (text === "pending") {
        status.style.color = "orange";
    } else if (text === "cancelled") {
        status.style.color = "red";
    }
});

        </script>
         @endpush
</x-admin-layout>
