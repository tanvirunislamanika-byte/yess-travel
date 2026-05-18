<x-admin-layout>   

    <div class="container-xl px-4">

        <div class="card mb-4">
             <livewire:admin.common.header
                :title="'Add Widget'"
                :content="'Add New Widget'"
                :icon="'fa-school'"
                :term="__('Widget')"
                :slug="route('admin.widgets')"
                :button="__('List Widgets')"
            />
            <div class="card-body">
                
               <div class="tab-content" id="nav-tabContent"> 
                <div class="tab-pane fade show active" id="nav-customer" role="tabpanel" aria-labelledby="nav-customer-tab">
                           {!! Form::open(array('method' => 'post', 'route' => 'admin.widgets.store', 'class' => 'form', 'files'=>true)) !!}
                           <div class="card-body">
                            <div class="border p-3">
                           @include('admin.widgets.inc.form')
                           <div class="row">
                              <div class="col-md-5"></div>
                              <div class="col-md-4"><button type="submit" class="btn btn-primary">{{__('Create')}}</button></div>
                           </div>
                           
                        </div>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            <!-- Page body end -->
         </div>
</x-admin-layout>
