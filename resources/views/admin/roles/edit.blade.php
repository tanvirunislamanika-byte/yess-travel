<x-app-layout>   
    <livewire:components.header :title="'Update Role'" :content="'Please fill all the fields to update Role'" :icon="'fa-school'"/>
    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-body">
               {!! Form::model($role, array('method' => 'put', 'route' => array('roles.update',[$role->id]), 'class' => 'form', 'files'=>true)) !!}
               {!! Form::hidden('id', $role->id) !!}
               <div class="sbp-preview">
                  <div class="sbp-preview-content">
                     @include('roles.inc.form')
                     <div class="col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">{{__('Update')}} &nbsp <i class="fa-solid fa-arrow-right"></i></button>
                     </div>
                  </div>
               </div>
               {!!Form::close()!!}
            </div>
        </div>
        
    </div>
</x-app-layout>