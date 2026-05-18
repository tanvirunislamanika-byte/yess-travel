<x-admin-layout>
    <div class="container-xl px-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-10">{{__('Flight Bookings')}}</div>
                    <div class="col-md-2">
                        <div class="input-group input-group-joined border-0 add-button">
                            
                        </div>
                    </div>
                </div>
                
            </div>
                @if(session()->has('message.added'))
                <div class="alert alert-{!! session('message.added') !!} alert-dismissible fade show" role="alert">
                  <strong>{{__('Congratulations')}}!</strong> {!! session('message.content') !!}.
                </div>
                @endif
                <?php $flight_bookings = App\Models\Flights::orderBy('id','DESC')->get();  ?>
               <div class="card-body">
                 
                     <!-- Basic Form Inputs card start -->
                     <div class="card">
                        
                        <div class="card-block">
                           <div class="dt-responsive table-responsive">
                              <table class="table table-bordered dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th>Flight</th>
                                            <th>Airline</th>
                                            <th>Class</th>                            
                                            <th>Booked On</th>
                                            <th>Price</th>
                                            <th>Adult</th>
                                            <th>Status</th>
                                            <th>Payment Method</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @if(null!==($flight_bookings))
                                       @foreach($flight_bookings as $booking)
                                        <?php $flight = App\Models\ModulesData::where('id',$booking->flight_id)->first(); ?>
                                        <tr>
                                            <td>{{$flight->extra_field_3}} <i class="fas fa-arrows-alt-h"></i> {{$flight->extra_field_6}}</td>
                                            <td>{{title($flight->extra_field_1)}}</td>
                                            <td>{{title($flight->extra_field_11)}}</td>                             
                                            <td>{{date('d M Y l',strtotime($booking->created_at))}}</td>                            
                                            <td>USD <strong>{{$booking->price}}</strong></td>                          
                                            <td>{{$booking->adults}}</td>
                                            <td class="status">{{$booking->status}}</td>
                                            <td>{{$booking->payment_via}}</td>
                                            <td><a href="{{route('admin.flight-booking',$booking->id)}}" class="tabledit-edit-button btn btn-primary btn-sm waves-effect waves-light">View Detail</a></td>
                                        </tr>
                                        @endforeach
                                        @endif
                                                                        
                                    </tbody>
                                </table>
                           </div>
                        </div>
                     </div>
                 
               </div>
            </div>
      </div>



      @push('js')
         <script>
            

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
