<x-admin-layout>   



    <div class="container-xl px-4">



        <div class="card mb-4">
            <livewire:admin.common.header

                :title="'Edit Widget Page'"

                :content="'Edit Widget Page'"

                :icon="'fa-school'"

                :term="__('Widget')"

                :slug="route('admin.widget_pages')"

                :button="__('List Widgets Pages')"

            />
            <div class="card-body">

                

               <div class="tab-content" id="nav-tabContent"> 

                <div class="tab-pane fade show active" id="nav-customer" role="tabpanel" aria-labelledby="nav-customer-tab">

                          {!! Form::model($widget_page, array('method' => 'post', 'route' => array('admin.widget_pages.update'), 'class' => 'form', 'files'=>true)) !!}

                          {!! Form::hidden('id', $widget_page->id) !!}

                           <div class="card-body">

                            <div class="border p-3">

                           @include('admin.widget_pages.inc.form')

                           <br>

                           <div class="row">

                              <div class="col-md-5"></div>

                              <div class="col-md-4"><button type="submit" class="btn btn-primary">{{__('Update')}}</button></div>

                           </div>

                           

                        </div>



                     </div>

                  </div>

               </div>

            </div>

            <!-- Page body end -->

         </div>

      </div>

    </x-admin-layout>