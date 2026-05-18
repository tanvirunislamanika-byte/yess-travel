<div class="row">
    <div class="col-sm-12 col-md-12">
       <div class="mb-3">
        {!! Form::label('name', 'Role Name', []) !!} 
        {!! Form::text('name', null, array('class'=>'form-control', 'id'=>'name', 'placeholder'=>'Role Name')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'name') !!}</div>
    </div>
    <div class="col-sm-12 col-md-12">
       <div class="mb-3">
        {!! Form::label('name', 'Assign Permissions', []) !!} 
    </div>
    <div class="col-sm-12 col-md-12">
       <div class="mb-3">
         <table class="table table-striped table-bordered">
           <thead>
               <th scope="col" width="1%"><input type="checkbox" name="all_permission"></th>
               <th scope="col" width="20%">Name</th>
               <th scope="col" width="1%">Guard</th> 
           </thead>
           @foreach($permissions as $permission)
               <tr>
                   <td>
                       <input type="checkbox" 
                       name="permission[{{ $permission->name }}]"
                       value="{{ $permission->name }}"
                       class='permission' {{(isset($role) && in_array($permission->name,$role->permissions()->pluck('name')->toArray()))?'checked':''}}>
                   </td>
                   <td>{{ $permission->name }}</td>
                   <td>{{ $permission->guard_name }}</td>
               </tr>
           @endforeach
         </table>
      </div>

</div>