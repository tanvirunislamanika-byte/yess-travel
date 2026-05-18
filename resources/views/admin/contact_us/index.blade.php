<x-admin-layout>
    <div class="container-xl px-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-10">{{__('Messages List')}}</div>
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

               <div class="row">
                  <div class="col-sm-12">
                     <!-- Basic Form Inputs card start -->
                     <div class="card">
                        
                        <div class="card-block">
                           <div class="dt-responsive table-responsive">
                              <table id="fix-header" class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Email')}}</th>
                                    <th>{{__('Dated')}}</th>
                                    <th>{{__('Action')}}</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if(null!==($messages))
                                 @foreach($messages as $data)
                                 <tr>
                                    <td>{{$data->first_name}} {{$data->last_name}}</td>

                                    <td>{{$data->email_address}}</td>
                                    
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)}}</td>
                                    <td>
                                      <a href="{{route('admin.contact-us-detail',[$data->id])}}" class="tabledit-edit-button btn btn-primary btn-sm waves-effect waves-light"><span class="icofont icofont-eye-alt"></span>&nbsp {{__('View Detail')}}</a>
                                      
                                      
                                    </td>
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
      </div>
</x-admin-layout>
