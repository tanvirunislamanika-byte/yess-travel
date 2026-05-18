<div class="row">
    <div class="col-sm-12 col-md-12">
       <div class="mb-3">
        {!! Form::label('name', 'Permission Name', []) !!} 
        {!! Form::text('name', null, array('class'=>'form-control', 'id'=>'name', 'placeholder'=>'Permission Name')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'name') !!}</div>
    </div>
</div>