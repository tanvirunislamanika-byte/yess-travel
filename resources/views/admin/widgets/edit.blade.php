<x-admin-layout>   



    <div class="container-xl px-4">



        <div class="card mb-4">

             <livewire:admin.common.header

                :title="'Edit Widget'"

                :content="'Edit Widget'"

                :icon="'fa-school'"

                :term="__('Widget')"

                :slug="route('admin.widgets')"

                :button="__('List Widgets')"

            />

            <div class="card-body">

                

               <div class="tab-content" id="nav-tabContent"> 

                <div class="tab-pane fade show active" id="nav-customer" role="tabpanel" aria-labelledby="nav-customer-tab">

                          {!! Form::model($widget, array('method' => 'post', 'route' => array('admin.widgets.update'), 'class' => 'form', 'files'=>true)) !!}

                          {!! Form::hidden('id', $widget->id) !!}

                           <div class="card-body">

                            <div class="border p-3">

                           @include('admin.widgets.inc.form')

                           <div class="row">

                              <div class="col-md-5"></div>

                              <div class="col-md-4"><button type="submit" class="btn btn-primary">{{__('Update')}}</button></div>

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

