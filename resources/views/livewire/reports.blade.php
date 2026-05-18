<div class="container-xl px-4">

        <div class="card mb-4">
           <div  class="card-header">
                <div class="row">
                    <div class="col-md-10">Reports</div>
                    <div class="col-md-2">
                        <div class="input-group input-group-joined border-0 add-button">
                            <a class="btn btn-danger btn-sm" href="javascript:;" wire:click="insert()">Add Report</a>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="card-body">
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
               
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{__('Sr.')}}</th>
                            <th>{{__('Name')}}</th>
                            @if(\Laravel\Jetstream\Jetstream::findRole(auth()->user()->membership->role)->name == 'Administrator')
                            <th>{{__('Member') }}</th>
                            @endif
                            <th>{{__('Created Date') }}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                        <tr role="row" class="filter">
                            <td></td>
                            <td><input type="text" wire:model.live="name" class="form-control" name="title" id="title" autocomplete="off" placeholder="Search Name"></td>
                            @if(\Laravel\Jetstream\Jetstream::findRole(auth()->user()->membership->role)->name == 'Administrator')
                            <td> <?php 
                            $team = App\Models\Team_user::where('user_id',auth()->user()->id)->first();
                            $userids = App\Models\Team_user::where('team_id',$team->team_id)->pluck('user_id')->toArray();
                            $users = App\Models\User::whereIn('id',$userids)->get(); ?>
                                <select name="status" wire:model.live="member" id="status" class="form-control status">
                                    <option value="">Select Member</option>
                                    @if(null!==($users))
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                    @endif
                                </select></td>
                            @endif    
                            <td>
                            <td><select name="status" wire:model.live="status" id="status" class="form-control status">
                                    <option value="">Select Status</option>
                                    <option value="completed">Completed</option>
                                    <option value="draft">Draft</option>
                                </select></td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @if(null!==($reports))
                        <?php $count = 1; ?>
                        @foreach($reports as $report)
                        <tr>
                            <td>{{$count}}</td>
                            <td>{{$report->first_name.' '.$report->last_name}}</td>
                            @if(\Laravel\Jetstream\Jetstream::findRole(auth()->user()->membership->role)->name == 'Administrator')
                            <td>{{$report->member->name}}</td>
                            @endif
                            <td>{{date('M d, Y',strtotime($report->created_at))}}</td>
                            <td>{{ucfirst($report->status)}}</td>
                            <td><a href="{{route('print-report',$report->id)}}" class="btn btn-warning btn-sm">Download</a> &nbsp<a href="javascript:;" class="btn btn-primary btn-sm" wire:click="view({{$report->id}})">View</a> &nbsp 
                                <?php
                                $timestamp = $report->created_at;

                                    // Convert the timestamp to a Carbon instance
                                    $timestamp = Carbon\Carbon::parse($timestamp);

                                    // Get the current time
                                    $currentTime = Carbon\Carbon::now();

                                    // Check if the timestamp is within the last 24 hours
                                    if ($timestamp->greaterThanOrEqualTo($currentTime->subDay()) || $report->status == 'draft') {
                                       
                                 ?>
                                <a href="javascript:;" class="btn btn-success btn-sm" wire:click="edit({{$report->id}})">Edit</a> 
                            <?php } ?>


                            </td>
                        </tr>
                        <?php $count++; ?>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

        

    </div>
</div>