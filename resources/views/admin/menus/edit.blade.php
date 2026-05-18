<x-admin-layout>   

<div>   

<section class="content mt-2">

      <div class="container-fluid">

        <div class="row">

          <div class="col-12">

            <!-- /.card -->



            <div class="card card-primary">

              <div class="card-header">

                <div class="row">

                    <div class="col-md-9 "><h3 class="card-title m-1"><span class="align-middle">{{__('Edit Menu')}}</span></h3></div>

                    <div class="col-md-3 text-right"><a href="{{url('admin/menus')}}" class="btn btn-danger btn-sm"><i class="fas fa-list"></i> &nbsp {{__('List Menus')}}</a></div>

                </div>

                

              </div>

                          {!! Form::model($menu, array('method' => 'post', 'route' => array('admin.menus.update'), 'class' => 'form', 'files'=>true)) !!}

                          {!! Form::hidden('id', $menu->id) !!}

                           <div class="card-body">

                            <div class="border p-3">

                           @include('admin.menus.inc.form')

                           <div class="row">

                              <div class="col-md-5"></div>

                              <div class="col-md-4"><button type="submit" class="btn btn-primary">{{__('Update')}}</button></div>

                           </div>

                           

                        </div>

                     </div>

                  </div>

               </form>



                     </div>

                  </div>

               </div>

            </div>

            <!-- Page body end -->

         </div>

      </div>

   </div>

</div>

</section>
</div>
</x-admin-layout>

