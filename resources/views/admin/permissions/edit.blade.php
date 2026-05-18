<x-app-layout>   
    <livewire:components.header :title="'Update Permission'" :content="'Please fill all the fields to update Permission'" :icon="'fa-school'"/>
    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-body">
               {!! Form::model($permission, array('method' => 'put', 'route' => array('permissions.update',[$permission->id]), 'class' => 'form', 'files'=>true)) !!}
               {!! Form::hidden('id', $permission->id) !!}
               <div class="sbp-preview">
                  <div class="sbp-preview-content">
                     @include('permissions.inc.form')
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