
  <div class="row">
    <div class="col-sm-12 col-md-12">
       <div class="mb-3">
        {!! Form::label('name', 'Full Name', []) !!} 
        {!! Form::text('name', null, array('class'=>'form-control', 'id'=>'name', 'placeholder'=>'Full Name')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'name') !!}</div>
    </div>
    <div class="col-sm-6 col-md-6">
       <div class="mb-3">
        {!! Form::label('email', 'Email address', []) !!} 
        {!! Form::email('email', null, array('class'=>'form-control', 'id'=>'email', 'placeholder'=>'Email address' )) !!}
        {!! APFrmErrHelp::showErrors($errors, 'email') !!}</div>
    </div>
    <div class="col-sm-6 col-md-6">
       <div class="mb-3">
        {!! Form::label('phone', 'Phone Number', []) !!} 
        {!! Form::text('mobile', null, array('class'=>'form-control', 'id'=>'mobile', 'placeholder'=>'Phone Number' )) !!}
        {!! APFrmErrHelp::showErrors($errors, 'mobile') !!}</div>
    </div>
     
    <div class="col-sm-6 col-md-6">
       <?php $roles = Spatie\Permission\Models\Role::pluck('name','id')->toArray(); ?>
       <div class="mb-3">
        {!! Form::label('role', "Role", []) !!} 
        {!! Form::select('role', [''=>'Select Role']+$roles, null, array('class'=>'form-control', 'id'=>'role', 'required'=>'required')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'role') !!}</div>
    </div>
   
    <div class="col-sm-6 col-md-6">
       <div class="mb-3">
        {!! Form::label('status', 'User Status', []) !!} 
        {!! Form::select('status', ['active'=>'Active','blocked'=>'Blocked'], null, array('class'=>'form-control select2', 'id'=>'class_id', 'required'=>'required')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'mobile') !!}</div>
    </div>

  

    <div class="col-sm-12 col-md-12">
       <div class="mb-3">
        {!! Form::label('password', 'Password', []) !!} 
        {!! Form::password('password', array('class'=>'form-control', 'id'=>'password', 'placeholder'=>'*******', 'autocomplete'=>'new-password' )) !!}
        {!! APFrmErrHelp::showErrors($errors, 'password') !!}</div>
    </div> 

   
    

    @push('js')
    <script type="text/javascript">
       @if(isset($user))
         showhide({{$user->role}})
       @endif
       $('#role').on('change',function(){
         showhide($(this).val());
       })
       function showhide(val) {
         if(val==5){
            $('#company').show()
            $('#prefix_div').hide()
            $('#suffix_div').hide()
         }else{
            $('#company').hide()
            $('#prefix_div').show()
            $('#suffix_div').show()
         }
       }
    </script>
    @endpush

 </div>