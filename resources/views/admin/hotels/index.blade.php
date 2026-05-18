<x-admin-layout>
    <div class="container-xl px-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-10">{{__('Hotels Bookings')}}</div>
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

               <div class="card-body">
                 
                     <!-- Basic Form Inputs card start -->
                     
                        <?php $hotel_bookings = App\Models\Hotels::orderBy('id','DESC')->get();  ?>
                        <div class="card-block">
                           <div class="dt-responsive table-responsive">
                              <table id="fix-header" class="table table-bordered dataTable no-footer">
                              <thead>
                                  <tr>
                                      <th>Name</th>
                                      <th>Hotel</th>
                                      <th>Location</th>                            
                                      <th>Booked On</th>
                                      <th>Price</th>
                                      <th>Status</th>
                                      <th>Payment Method</th>
                                      <th>Action</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @if(null!==($hotel_bookings))
                                  @foreach($hotel_bookings as $booking)
                                  <tr>
                                    <?php $user = App\Models\User::where('id',$booking->user_id)->first(); ?>
                                    <?php $hotel = App\Models\ModulesData::where('id',$booking->hotel_id)->first(); ?>
                                      <td>{{$user->name}}</td>
                                      <td>{{$hotel->title}}</td>
                                      <td>{{$booking->travelling_from}}</td>  
                                      <td>{{date('d M Y l',strtotime($booking->check_in))}}</td>                          
                                      <td>USD <strong>{{$booking->price}}</strong></td>
                                      <td><span class="status">{{$booking->status}}</span></td>
                                      <td>{{$booking->payment_via}}</td>
                                      <td><a href="{{route('admin.hotel-booking',$booking->id)}}" class="tabledit-edit-button btn btn-primary btn-sm waves-effect waves-light">View Detail</a></td>
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
