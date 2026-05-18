<x-admin-layout>   

@push('css')

<link rel="stylesheet" href="{{ asset('/admin_assets/toastr/toastr.min.css') }}">

<link rel="stylesheet" href="{{ asset('/admin_assets/menu/css/nestable.css') }}">



<link rel="stylesheet" href="{{ asset('/admin_assets/menu/css/menu.css') }}">

@endpush

<div>   

<section class="content mt-2">

      <div class="container-fluid">

        <div class="row">

          <div class="col-12">

            <!-- /.card -->

            <?php 

            $menu_types = App\Models\Menu_types::where('status','active')->get();



              $menu= new App\Models\Menu;

               ?> 

            @if(null!==($menu_types))

            @foreach($menu_types as $type)

            <div class="card card-primary">

              <div class="card-header">

                <div class="row">

                    <div class="col-md-9 "><h3 class="card-title m-1"><span class="align-middle">{{$type->title}} {{__('List')}}</span></h3></div>

                    <div class="col-md-3 text-right"><a href="{{url('admin/add-menu')}}" class="btn btn-danger btn-sm"><i class="fas fa-list"></i> &nbsp {{__('Add new Menu')}}</a></div>

                </div>

                

              </div>

                        <div class="card-block table-border-style">

                           

                           <div class="dt-responsive table-responsive">

                              <div class="page-body">

               <div class="row">

                  <div class="col-sm-12">

                     <!-- Basic Form Inputs card start -->

                     <div class="card">

                        

                        <div class="card-block p-10">

                          <div class="dd" id="nestable">

                           {!!$menu->getHTML(App\Models\Menu::where('menu_type_id',$type->id)->orderBy('order')->get())!!}

                         </div>

                        </div>

                     </div>

                  </div>

               </div>

            </div>

                           </div>

                        </div>

                     </div>



                     @endforeach



                     @endif



                  </div>

               </div>

            </div>

         </section>

      </div>



 







@push('js')

<script type="text/javascript" src="{{ asset('/admin_assets/toastr/toastr.min.js') }}"></script>

 <script src="{{ asset('/admin_assets/menu/js/jquery.nestable.js') }}"></script>

 <script src="{{ asset('/admin_assets/menu/js/menu.js') }}"></script>

@endpush

</x-admin-layout>