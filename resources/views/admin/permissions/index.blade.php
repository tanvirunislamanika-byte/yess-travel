<x-admin-layout>
    <div class="container-xl px-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-10">{{__('Permissions List')}}</div>
                    <div class="col-md-2">
                        <div class="input-group input-group-joined border-0 add-button">
                            <a class="btn btn-danger btn-sm" href="{{url('admin/permissions/create')}}">{{__('Add New Permission')}}</a>
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
                <table class="table table-bordered" id="table-permissions">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Guard</th>
                            <th>Actions</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                        <tr>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->guard_name }}</td>
                            <td>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="{{route('permissions.edit',[$permission->id])}}"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark" href="{{route('permissions.destroy',[$permission->id])}}"><i class="fa-solid fa-trash"></i></a>
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</x-admin-layout>
