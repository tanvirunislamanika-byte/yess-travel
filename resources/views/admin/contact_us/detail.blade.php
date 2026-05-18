<x-admin-layout>
    <div class="container-xl px-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-10">{{__('Messages Detail')}}</div>
                    <div class="col-md-2">
                        <div class="input-group input-group-joined border-0 add-button">
                            
                        </div>
                    </div>
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
                    <div class="col-xl-4 col-lg-12 push-xl-8 task-detail-right">
                        <div class="card">
                            <div class="card-block task-details">
                                <table class="table table-border table-xs">
                                    <tbody>
                                        <tr>
                                            <td><i class="icofont icofont-contrast"></i> {{__('Name')}}:</td>
                                            <td class="text-right"><span class="f-right"><a> {{$message->first_name}}</a></span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="ti-email"></i> {{__('Email Address')}}:</td>
                                            <td class="text-right">{{$message->email_address}}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="icofont icofont-id-card"></i> {{__('Created')}}:</td>
                                            <td class="text-right">{{date('d M, Y',strtotime($message->created_at))}}</td>
                                        </tr>
                                        
                                        
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        
                        
                        
                        
                    </div>
                    <!-- Task-detail-right start -->
                    <!-- Task-detail-left start -->
                    <div class="col-xl-8 col-lg-12 pull-xl-4">
                        <div class="card">
                            <div class="card-block">
                                <div class="">
                                    <div class="m-b-20" style="padding: 10px;">
                                        <p>
                                            {!!$message->message!!}
                                        </p>
                                    </div>
                                    
                                    
                                    
                                    
                                    
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
                    <!-- Task-detail-left end -->
                </div>
            </div>
            <!-- Page body end -->
         </div>
</x-admin-layout>
