<x-admin-layout>
    <div class="container-xl px-4">
        <livewire:admin.common.header :title="'Edit Module'" :icon="'fa-school'" :term="'Module'" :slug="url('/admin/modules')" :button="__('Modules List')"/> 
        <div class="card mb-4">
            <div class="card-body">
               {!! Form::model($module, array('method' => 'post', 'route' => array('admin.modules.update'), 'class' => 'form', 'files'=>true)) !!}
               {!! Form::hidden('id', $module->id) !!}
               <div class="sbp-preview">
                  <div class="sbp-preview-content">
                     @include('admin.modules.inc.form')
                     <div class="col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">{{__('Update')}} &nbsp <i class="fa-solid fa-arrow-right"></i></button>
                     </div>
                  </div>
               </div>
               {!!Form::close()!!}
            </div>
        </div>
        
    </div>
</x-admin-layout>