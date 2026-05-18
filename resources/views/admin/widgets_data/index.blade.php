<x-admin-layout>   

    <div class="container-xl px-4">
            
           
                
               <div class="tab-content" id="nav-tabContent"> 
                <div class="tab-pane fade show active" id="nav-customer" role="tabpanel" aria-labelledby="nav-customer-tab">
            <div class="row">
            
            @if(null!==($widgets))
            @foreach($widgets as $wid)
            <?php $widget_data = null; ?> 
            <div class="col-12 widgets mb-3">
            <!-- /.card -->

            <div class="card card-primary">
              <div class="card-header">
                <div class="row">
                    <div class="col-md-10">
                        <h3 class="card-title m-1"><span class="align-middle" style="color: #000;">{{$wid->title}}</span></h3>
                    </div>
                    <div class="col-md-2">
                        <div class="text-right mb-1">
                            <a class="btn btn-warning btn-widget btn-sm" href="javascript:;" data-widget="{{$wid->id}}">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    
                </div>
              </div>
            
            <div id="widget_{{$wid->id}}" class="show_hide">
               <div class="row">
                  <div class="col-sm-12">
                     <!-- Basic Form Inputs card start -->
                     <div class="">
                      
                        <div class="card-block">
                        <?php 

                            $widget_data = App\Models\WidgetsData::where('widget_id',$wid->id)->first()
                        ?>
                          @if(null!==($widget_data))
                          {!! Form::model($widget_data, array('method' => 'post', 'route' => array('admin.widget_data.store',$wid->id), 'class' => 'form', 'files'=>true)) !!}
                           
                          @else
                          {!! Form::open(array('method' => 'post', 'route' => array('admin.widget_data.store',$wid->id), 'class' => 'form', 'files'=>true)) !!}
                           
                          @endif
                           {!! Form::hidden('id', $wid->id) !!}
                           
                            <div class="card-body">
                                <div class="border p-3">
                                    <div>
                                        @include('admin.widgets_data.inc.form')
                                        <div class="row">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
                                            </div>
                                        </div>
                                    </div>
                               </div>
                           </div>

                           {!! Form::close() !!}
                           
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            </div>
            </div>
            @endforeach
            @endif
            <!-- Page body end -->
         </div>
      </div>
   </div>
</div>
@push('js')

@include('admin.widgets_data.widgetfiler')
@include('admin.ckeditor.index')

@endpush
</x-admin-layout>
