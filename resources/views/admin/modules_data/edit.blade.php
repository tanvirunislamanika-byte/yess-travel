<x-admin-layout>   
    <div class="container-xl px-4">

        <div class="card mb-4">
            <livewire:admin.common.header :title="'Update '.$module->term" :content="'Please fill all the fields to update '.$module->term" :icon="'fa-school'" :term="$module->term" :slug="url('/admin/'.$module->slug)" :button="__($module->name.' List')"/>
            <div class="card-body">
                 @if(session()->has('message.added'))
                    <div class="alert alert-{{ session('message.added') }} alert-dismissible fade show" role="alert">
                        <strong>{{__('Congratulations')}}!</strong> {!! session('message.content') !!}.
                    </div>
                @endif
               <div class="tab-content" id="nav-tabContent">
               <div class="tab-pane fade show active" id="nav-customer" role="tabpanel" aria-labelledby="nav-customer-tab"> 
               {!! Form::model($module_data, array('method' => 'post', 'route' => array('admin.modules.data.update',$module->slug), 'class' => 'form', 'files'=>true,'novalidate'=>'novalidate')) !!}

               {!! Form::hidden('id', $module_data->id) !!}

               <div class="sbp-preview">

                  <div class="sbp-preview-content">

                     @include('admin.modules_data.inc.form')

                     <div class="col-sm-12 col-md-12 text-center">

                        <button type="submit" class="btn btn-primary">{{__('Update')}} &nbsp <i class="fa-solid fa-arrow-right"></i></button>

                        @if(checkCompleteIncomplete($module_data->id) && $module->is_preview && $module_data->final_submit=='no')
                        <button style="color:red" type="submit" name="finalSubmit" value="yes" class="btn btn-danger">{{__('Final Submit')}} &nbsp <i class="fa-solid fa-arrow-right"></i></button>
                        @endif

                     </div>

                  </div>

               </div>

               {!!Form::close()!!}
               </div>
                

               </div>
            </div>

        </div>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Add Party</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Your form goes here -->
                        <form method="POST" action="https://pacificdocsmanager.in/admin/party/store" accept-charset="UTF-8" class="form" id="party-form" enctype="multipart/form-data">
                            @csrf

                            <div class="sbp-preview">
                                <div class="sbp-preview-content">
                                    <input type="hidden" name="module_id" value="28" />
                                    <input type="hidden" name="module_term" value="Party" />
                                    <input type="hidden" name="module_slug" value="party" />

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="title" class="">Party</label>
                                                <input class="form-control" id="title" placeholder="Party" required="required" name="title" type="text" />
                                            </div>
                                        </div>

                                        <div class="col-lg-6" id="extra_field_1_div">
                                            <div class="mb-3">
                                                <label>Enter Nick Name </label>

                                                <input
                                                    class="form-control"
                                                    id="extra_field_1"
                                                    placeholder="Enter Nick Name"
                                                    maxlength="30"
                                                    oninvalid="this.setCustomValidity('')"
                                                    oninput="this.setCustomValidity('')"
                                                    name="extra_field_1"
                                                    type="text"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-lg-6" id="extra_field_2_div">
                                            <div class="mb-3">
                                                <label>Enter Email </label>

                                                <input class="form-control" id="extra_field_2" placeholder="Enter Email" maxlength="30" oninvalid="this.setCustomValidity('')" oninput="this.setCustomValidity('')" name="extra_field_2" type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6" id="extra_field_3_div">
                                            <div class="mb-3">
                                                <label>Enter Phone <span id="extra_field_3_span">*</span> </label>

                                                <input
                                                    class="form-control"
                                                    id="extra_field_3"
                                                    placeholder="Enter Phone"
                                                    required=""
                                                    maxlength="30"
                                                    oninvalid="this.setCustomValidity('')"
                                                    oninput="this.setCustomValidity('')"
                                                    name="extra_field_3"
                                                    type="number"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-lg-6" id="extra_field_4_div">
                                            <div class="mb-3">
                                                <label>Enter City </label>

                                                <input class="form-control" id="extra_field_4" placeholder="Enter City" maxlength="40" oninvalid="this.setCustomValidity('')" oninput="this.setCustomValidity('')" name="extra_field_4" type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6" id="extra_field_5_div">
                                            <div class="mb-3">
                                                <label>Enter State <span id="extra_field_5_span">*</span> </label>
                                                 {{ Form::select('extra_field_5', ['' => 'Select State'] + dropdown(27), null, ['class' => 'form-control', 'id' => 'extra_field_5', 'required'=>'required', 'oninvalid' => "this.setCustomValidity('')", 'oninput' => "this.setCustomValidity('')"]) }}    
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <div class="jFiler jFiler-theme-dragdropbox">
                                                    <input type="file" name="image" id="filer_input1" class="form-control" />
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary w-100">
                                            Create &nbsp;
                                            <svg
                                                class="svg-inline--fa fa-arrow-right"
                                                aria-hidden="true"
                                                focusable="false"
                                                data-prefix="fas"
                                                data-icon="arrow-right"
                                                role="img"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 448 512"
                                                data-fa-i2svg=""
                                            >
                                                <path
                                                    fill="currentColor"
                                                    d="M438.6 278.6l-160 160C272.4 444.9 264.2 448 256 448s-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L338.8 288H32C14.33 288 .0016 273.7 .0016 256S14.33 224 32 224h306.8l-105.4-105.4c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160C451.1 245.9 451.1 266.1 438.6 278.6z"
                                                ></path>
                                            </svg>
                                            <!-- <i class="fa-solid fa-arrow-right"></i> Font Awesome fontawesome.com -->
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>

        

    </div>

    @push('js')

<script src="{{asset('admin_assets/assets/pages/filer/jquery.fileuploadsedit.init.js')}}" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    <?php $fieldsss = checkCityAndGr($module->id); ?>

    @if(isset($fieldsss[0]) && isset($fieldsss[1]) && isset($fieldsss[2]))
         $(document).ready(function() {
    // Intercept the form submission
    $('#party-form').submit(function(e) {
      e.preventDefault(); // Prevent the default form submission
      
      // Serialize the form data
      var formData = new FormData(this);

      // Make an AJAX request
      $.ajax({
        url: $(this).attr('action'), // URL to send the request
        type: $(this).attr('method'), // HTTP method (POST in this case)
        data: formData, // Form data
        processData: false, // Don't process the data (important when sending files)
        contentType: false, // Don't set the content type (important when sending files)
        success: function(response) {
          // Handle the success response here
          console.log(response);

          // Assuming response contains id and title
          var id = response.id;
          var title = response.title;

          // Append a new option to the dropdown
          $('#{{$fieldsss[2]}}').append('<option value="' + id + '">' + title + '</option>');
          $('#myModal').modal('hide');
          // Show SweetAlert success message
          Swal.fire({
            title: 'Success!',
            text: 'Party has been added successfully!',
            icon: 'success',
            confirmButtonText: 'OK'
          });
        },
        error: function(error) {
          // Handle the error response here
          console.error(error);

          // Show SweetAlert error message
          Swal.fire({
            title: 'Error!',
            text: 'Something went wrong. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      });
    });
  });
    @endif

 
</script>  
<script type="text/javascript">

@if(session()->has('message.added'))

            

    

    $(document).ready(function() {

   

    /*--------------------------------------

         Notifications & Dialogs

     ---------------------------------------*/

    /*

     * Notifications

     */

    function notify(from, align, icon, type, animIn, animOut){

        $.growl({

            icon: icon,

            title: ' <strong>Created Successfully!</strong> ',

            message: "{!! session('message.content') !!}",

            url: ''

        },{

            element: 'body',

            type: type,

            allow_dismiss: true,

            placement: {

                from: from,

                align: align

            },

            offset: {

                x: 30,

                y: 30

            },

            spacing: 10,

            z_index: 999999,

            delay: 2500,

            timer: 1000,

            url_target: '_blank',

            mouse_over: false,

            animate: {

                enter: animIn,

                exit: animOut

            },

            icon_type: 'class',

            template: '<div data-growl="container" class="alert" role="alert">' +

            '<button type="button" class="close" data-growl="dismiss">' +

            '<span aria-hidden="true">&times;</span>' +

            '<span class="sr-only">Close</span>' +

            '</button>' +

            '<span data-growl="icon"></span>' +

            '<span data-growl="title"></span>' +

            '<span data-growl="message"></span>' +

            '<a href="#" data-growl="url"></a>' +

            '</div>'

        });

    };



    



        var nFrom = 'top';

        var nAlign = 'right';

        var nIcons = $(this).attr('data-icon');

        var nType = 'success';

        var nAnimIn = 'animated flipInY';

        var nAnimOut = 'animated flipOutY';



        notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut);



});

@endif



$("#title").keyup(function(){

        var Text = $(this).val();

        Text = Text.toLowerCase();

        Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');

        $("#slug").val(Text);        

});



   @if($module->is_highlights)



   $(document).ready(function() {

    var dynamic_form = $("#dynamic_form").dynamicForm("#dynamic_form", "#plus", "#minus", {

        limit: 10,

        formPrefix: "dynamic_form",

        normalizeFullForm: false,

        // JSON data which will prefill the form

        //data: [{reference_links:'ttttttt'}]



    });



    @if(isset($module_data))

    dynamic_form.inject({!!$module_data->highlights!!});

    @else

    dynamic_form.inject();

    @endif

    

    

    //dynamic_form.inject([{p_name: 'Hemant',quantity: '123',remarks: 'testing remark'},{p_name: 'Harshal',quantity: '123',remarks: 'testing remark'}]);



    $("#dynamic_form #minus").on('click', function() {

        var initDynamicId = $(this).closest('#dynamic_form').parent().find(

                "[id^='dynamic_form']")

            .length;

        if (initDynamicId === 2) {

            $(this).closest('#dynamic_form').next().find('#minus').hide();

        }

        $(this).closest('#dynamic_form').remove();

    });



    $('form').on('submit', function(event) {

        var values = {};

        $.each($('form').serializeArray(), function(i, field) {

            values[field.name] = field.value;

        });

        //console.log(values)

        //event.preventDefault();

    })

});

   @endif

</script>

@endpush

</x-admin-layout>