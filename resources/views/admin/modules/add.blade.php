<x-admin-layout>   
    <div class="container-xl px-4">
         <livewire:admin.common.header :title="'Add New Module'" :content="'List of all modules are below'" :icon="'fa-school'" :term="'Module'" :slug="url('/admin/modules')" :button="__('Modules List')"/>
        <div class="card mb-4">
            <div class="card-body">
               {!! Form::open(array('method' => 'post', 'route' => array('admin.modules.store'), 'class' => 'form', 'files'=>true)) !!}
               <div class="sbp-preview">
                  <div class="sbp-preview-content">
                     @include('admin.modules.inc.form')
                     <div class="col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">{{__('Create')}} &nbsp <i class="fa-solid fa-arrow-right"></i></button>
                     </div>
                  </div>
               </div>
               {!!Form::close()!!}
            </div>
        </div>
        
    </div>
</x-admin-layout>
