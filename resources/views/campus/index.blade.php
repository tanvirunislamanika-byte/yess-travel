<x-admin-layout>
    <div class="container-xl px-4">
        <div class="card mb-4">
            <livewire:admin.common.header
                :title="'Users List'"
                :content="'List of all Users are below'"
                :icon="'fa-school'"
                :term="'User'"
                :slug="url('/admin/users/create')"
                :button="__('Add New User')"
            />
            <div class="card-body">
                @if(session()->has('message.added'))
                    <div class="alert alert-{{ session('message.added') }} alert-dismissible fade show" role="alert">
                        <strong>{{__('Congratulations')}}!</strong> {!! session('message.content') !!}.
                    </div>
                @endif
                <input type="hidden" id="table-campuses-url" value="{!! Request::url() !!}">
                <input type="hidden" id="is_enable_campuses_action" value="yes">
                <table class="table table-bordered" id="table-campuses">
                    <thead>
                        
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr><tr role="row" class="filter"> 
                            <td>
                                <input type="text" class="form-control" name="name" id="name" autocomplete="off" placeholder="Search Name">
                            </td>
                            <td>
                                <input type="email" class="form-control" name="email" id="email" autocomplete="off" placeholder="Search Email">
                            </td>


                            <td>
                                <?php $roles = Spatie\Permission\Models\Role::pluck('name','id')->toArray(); ?>
                                {!! Form::select('role', [''=>'Select Role']+$roles, null, array('class'=>'form-control', 'id'=>'role', 'required'=>'required')) !!}
                            </td>
                                                  
                            <td>
                                <select name="status" id="status"  class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="blocked">Blocked</option>
                                </select>
                              </td>
                              <td></td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
    @push('js')
    <script src="{{asset('admin_assets/js/functions.js?v=1.1')}}"></script>
    @endpush
</x-app-layout>
