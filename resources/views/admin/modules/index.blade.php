<x-admin-layout>   
    <div class="container-xl px-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-10">{{__('Modules List')}}</div>
                    <div class="col-md-2">
                        <div class="input-group input-group-joined border-0 add-button">
                            <a class="btn btn-danger btn-sm" href="{{url('admin/add-module')}}">{{__('Add New Module')}}</a>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="card-body">
                @if(session()->has('message.added'))
                <div class="alert alert-{!! session('message.added') !!} alert-dismissible fade show" role="alert">
                  <strong>{{__('Congratulations')}}!</strong> {!! session('message.content') !!}.
                </div>
                @endif
                <input type="hidden" id="table-modules-url" value="{!! Request::url() !!}">
                <input type="hidden" id="is_enable_modules_action" value="{!! (in_array('modules.edit',permissions()) || in_array('modules.destroy',permissions()))?'yes':'no' !!}">
                <table class="table table-bordered" id="table-modules">
                  <thead>
                     <tr>
                        <th>Module Name</th>
                        <th>Module Term</th>
                        <th>Link</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     @if($modules)
                     @foreach($modules as $module)
                     <tr>
                        <td>{{$module->name}}</td>
                        <td>{{$module->term}}</td>
                        <td><a href="{{ route('admin.modules.data',$module->slug)}}">Link</a></td>
                        <td>
                           <a class="btn btn-success btn-sm" href="{{route('admin.modules.edit',$module->id)}}" role="button">Edit</a>
                           <a class="btn btn-danger delete btn-sm" href="{{route('admin.modules.delete',$module->id)}}" role="button">Delete</a>
                        </td>
                     </tr>
                     @endforeach
                     @endif
                  </tbody>
                </table>
            </div>
        </div>
        
    </div>
</x-admin-layout>
