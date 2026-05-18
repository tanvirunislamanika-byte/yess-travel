<x-admin-layout>
    <div class="container-xl px-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-10">{{__('Roles List')}}</div>
                    <div class="col-md-2">
                        <div class="input-group input-group-joined border-0 add-button">
                            <a class="btn btn-danger btn-sm" href="{{url('admin/roles/create')}}">{{__('Add New Role')}}</a>
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
                <table class="table table-bordered" id="table-Roles">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $key => $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="{{route('admin.roles.edit',[$role->id])}}"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark" href="{{route('admin.roles.destroy',[$role->id])}}"><i class="fa-solid fa-trash"></i></a>
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex">
                    {!! $roles->links() !!}
                </div>
            </div>
        </div>
        
    </div>
</x-admin-layout>
