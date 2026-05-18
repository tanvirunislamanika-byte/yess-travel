@push('js')
<style type="text/css">
    .error{color: red}
    .form-control-file{border: 1px solid;}
</style>

{{-- Include Location Fields Component (only if not already included) --}}
@if(!isset($locationFieldsIncluded))
    @php $locationFieldsIncluded = true; @endphp
    @include('admin.modules.inc.location_fields')
@endif

<script type="text/javascript">
    var thumbnail_height = "{{$module->thumbnail_height}}";
    var thumbnail_width = "{{$module->thumbnail_width}}";
</script>
@endpush

<input type="hidden" name="module_id" value="{{$module->id}}">
<input type="hidden" name="module_term" value="{{$module->term}}">
<input type="hidden" name="module_slug" value="{{$module->slug}}">
<input type="hidden" id="attached_file" <?php if(isset($module_data)){echo 'value="'.$module_data->image.'"';} ?> name="image">
<input type="hidden" id="attached_files" <?php if(isset($module_data)){echo 'value="'.$module_data->images.'"';} ?> name="images">
<?php $style = ''; ?>
<?php if(in_array($module->id, array(29,31,33,35))){
    $style = 'display:none';
    $titleVal = $module->term;
} ?>

<div class="row">
    <div class="col-md-12">
        <div class="mb-3">
            {!! Form::label('title', $module->term, ['class' => '']) !!}
            {!! Form::text('title', null, array('class'=>'form-control', 'id'=>'title', 'placeholder'=>$module->term, 'required'=>'required')) !!}
        </div>
    </div>
    
    <div class="col-md-12" style="display: none;">
        <div class="mb-3">
            {!! Form::label('term', $module->term.' Slug', ['class' => '']) !!}
            {!! Form::text('slug', null, array('class'=>'form-control', 'id'=>'slug', 'placeholder'=>$module->term.' Slug', 'required'=>'required')) !!}
        </div>
    </div>

    @if($module->is_menu)
    @if(null!==($menu_types))
    <div class="col-md-12">
        {!! Form::label('term', 'Select Menus', ['class' => '']) !!}
        <div class="card mb-4">
            <div class="card-body">  
                <div class="row">
                    @foreach($menu_types as $key => $menu_type)
                    @php
                    if(isset($module_data)){
                        $menu = App\Models\Menu::where('post_id',$module_data->id)->where('menu_type_id',$key)->first();
                    }
                    @endphp
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" <?php if(isset($menu) && $menu->id!=''){echo 'checked';} ?> name="menu_{{$key}}" id="checkbox{{$key}}" type="checkbox" value="">
                            <label class="form-check-label" for="checkbox{{$key}}">{{$menu_type}}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif

    @if($module->category && $module->multiple_category)
    <div class="col-md-12">
        <div class="mb-3">
            @php
            if(isset($module_data)){
                $category_ids = explode(',',$module_data->category_ids);
            }
            @endphp
            {!! Form::label('category_ids', $module->term.' Categories', ['class' => '']) !!}
            {!! Form::select('category_ids[]', $categories, isset($category_ids)?$category_ids:null, array('class'=>'js-example-basic-multiple col-sm-12 select2-hidden-accessible', 'id'=>'category_ids', 'multiple'=>'multiple')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'category_ids') !!}
        </div>
    </div>
    @else
    @if($module->category)
    <div class="col-md-12">
        <div class="mb-3">
            <?php 
            $parent = App\Models\Modules::findOrFail( $module->parent_id);
            ?>
            {!! Form::label('category', 'Select '.$parent->term, ['class' => '']) !!}
            {!! Form::select('category', [''=>'Select '.$parent->term]+$categories, null, array('class'=>'form-control', 'id'=>'category', 'required'=>'required')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'category') !!}
        </div>
    </div>
    @endif
    @endif

    @if($module->sub_category)
    <div class="col-md-12">
        <div class="mb-3">
            {!! Form::label('sub_category', $module->term.' Sub Category', ['class' => '']) !!}
            <span id="dd-subcategories">
                {!! Form::select('sub_category', [''=>'Select Sub Category'], null, array('class'=>'form-control', 'id'=>'sub_category', 'required'=>'required')) !!}
            </span>
            {!! APFrmErrHelp::showErrors($errors, 'sub_category') !!}
        </div>
    </div>
    @endif

    

    @php
    $totalFields = $module->extra_fields;
    $arrSort = [];

    if ($totalFields > 0) {
        for ($i = 1; $i <= $totalFields; $i++) {
            $arrSort += getFieldAttributes($module, $i);
        }
    }

    uksort($arrSort, function ($a, $b) {
        return $a - $b;
    });

    $requiredArr = [];

    if (!empty($arrSort)) {
        foreach ($arrSort as $key => $sort) {
            list($fieldTitle, $fieldName, $fieldType, $fieldMaxLength, $fieldRequired, $fieldRequiredMessage,
                $fieldShow, $fieldAttr, $isRequired, $fieldCol) = $sort;

            $requiredArr[] = [
                'field' => $fieldName,
                'required' => $isRequired == 'required' ? 'yes' : 'no',
                'message' => $fieldRequiredMessage,
            ];

            @endphp
            @if ($fieldShow)
                <div class="col-lg-{{ $module->$fieldCol }}" id="{{$fieldName}}_div">
                    <div class="mb-3">
                        <label>{{ $fieldTitle }} @if($isRequired) <span id="{{$fieldName.'_span'}}">*</span> @endif @if($fieldTitle=='Party Name') <a href="javascript:;" onclick="javascript:$('#myModal').modal('show')" style="font-size: 12px;color: red;">Add Party</a>@endif</label>

                        @if ($fieldType == 'select')
                            <?php 
                            // Check if this is a location field
                            $isLocationField = false;
                            $locationType = '';
                            $parentField = '';
                            
                            if (trim($fieldAttr) == 'countries') {
                                $isLocationField = true;
                                $locationType = 'countries';
                            } elseif (trim($fieldAttr) == 'states') {
                                $isLocationField = true;
                                $locationType = 'states';
                                // Find the country field to set as parent
                                for ($j = 1; $j <= $totalFields; $j++) {
                                    $countryFieldAttr = 'extra_field_attr_' . $j;
                                    $attrValue = $module->$countryFieldAttr;
                                    if (trim($attrValue) == 'countries') {
                                        $countryFieldName = 'extra_field_' . $j;
                                        $parentField = $countryFieldName;
                                        break;
                                    }
                                }
                            } elseif (trim($fieldAttr) == 'cities') {
                                $isLocationField = true;
                                $locationType = 'cities';
                                // Find the state field to set as parent
                                for ($j = 1; $j <= $totalFields; $j++) {
                                    $stateFieldAttr = 'extra_field_attr_' . $j;
                                    $attrValue = $module->$stateFieldAttr;
                                    if (trim($attrValue) == 'states') {
                                        $stateFieldName = 'extra_field_' . $j;
                                        $parentField = $stateFieldName;
                                        break;
                                    }
                                }
                            }
                            
                            if ($isLocationField) {
                                // For location fields, we'll populate via JavaScript
                                $dropdown = ['' => 'Select ' . $fieldTitle];
                            } else {
                                // For regular fields, use the existing dropdown logic
                                if(trim($fieldTitle) == 'Invoice Status'){
                                    $dropdown = dropdown($fieldAttr);
                                } else {
                                    $dropdown = ['' => 'Select ' . $fieldTitle] + dropdown($fieldAttr);
                                }
                            }
                            ?>
                            
                            @if ($isLocationField)
                                {{ Form::select($fieldName, $dropdown, null, [
                                    'class' => 'form-control', 
                                    'id' => $fieldName, 
                                    $isRequired, 
                                    'data-location-type' => $locationType,
                                    'data-parent-field' => $parentField,
                                    'oninvalid' => "this.setCustomValidity('" . $fieldRequiredMessage . "')", 
                                    'oninput' => "this.setCustomValidity('')"
                                ]) }}
                            @else
                            {{ Form::select($fieldName, $dropdown, null, ['class' => 'form-control', 'id' => $fieldName, $isRequired, 'oninvalid' => "this.setCustomValidity('" . $fieldRequiredMessage . "')", 'oninput' => "this.setCustomValidity('')"]) }}
                            @endif
                        @elseif ($fieldType == 'auto')
                            <?php $digits = $module->$fieldMaxLength; $auto =  unique_auto($digits,'modules_data',$fieldName,$module->id); ?>

                            <?php 
                            if(request()->regenerate==1){
                              $aabb = $auto;
                            }else{
                              $aabb = isset($module_data)?null:$auto;
                            }
                            ?>
                            {{ Form::number($fieldName, $aabb, ['class' => 'form-control', 'id' => $fieldName, 'placeholder' => 'Enter ' . $fieldTitle, $isRequired, 'maxlength' => $fieldMaxLength, 'oninvalid' => "this.setCustomValidity('" . $fieldRequiredMessage . "')", 'oninput' => "this.setCustomValidity('')"]) }}
                        @elseif ($fieldType == 'checkbox')
                            {{ Form::checkbox($fieldName, null, null, ['id' => $fieldName]) }}    
                        @elseif ($fieldType == 'file')
                                @if(isset($module_data) && $module_data->$fieldName)
                                {{ Form::file($fieldName, ['class' => 'form-control form-control-file', 'id' => $fieldName,'oninvalid' => "this.setCustomValidity('" . $fieldRequiredMessage . "')", 'oninput' => "this.setCustomValidity('')"]) }}
                                @else
                                {{ Form::file($fieldName, ['class' => 'form-control form-control-file', 'id' => $fieldName, $isRequired, 'oninvalid' => "this.setCustomValidity('" . $fieldRequiredMessage . "')", 'oninput' => "this.setCustomValidity('')"]) }}
                                @endif
                                


                            @if(isset($module_data) && $module_data->$fieldName)
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ asset('images/' . $module_data->$fieldName) }}" download>{{ $module_data->$fieldName }}</a> &nbsp <a style="color: red" href="{{route('admin.modules.data.delete.file',[$module_data->id,$fieldName])}}"  onclick="return confirm('Are you sure you want to delete this file?')"><i class="fa-regular fa-circle-xmark"></i></a>
                                    </div>
                                </div>
                                    
                            @endif
                            @elseif($fieldType == 'datetime-local')
                        {{ Form::input('datetime-local', $fieldName, null, ['class' => 'form-control','id' => $fieldName,'placeholder' => 'Enter ' . $fieldTitle,$isRequired,'maxlength' => $fieldMaxLength,'oninvalid' => "this.setCustomValidity('" . $fieldRequiredMessage . "')",'oninput' => "this.setCustomValidity('')"]) }}
                        @else
                            {{ Form::$fieldType($fieldName, null, ['class' => 'form-control', 'id' => $fieldName, 'placeholder' => 'Enter ' . $fieldTitle, $isRequired, 'maxlength' => $fieldMaxLength, 'oninvalid' => "this.setCustomValidity('" . $fieldRequiredMessage . "')", 'oninput' => "this.setCustomValidity('')"]) }}
             @endif

                    </div>
                </div>
            @endif
            @php
            }
        }
    @endphp

    <input type="hidden" name="required_json_arr" value='{!!json_encode($requiredArr)!!}' id="required_json_arr">

    @if($module->id == 1)
    
   <?php 
    $services = App\Models\ModulesData::where('module_id', 28)->get();
    $cusines = App\Models\ModulesData::where('module_id', 29)->get();

    $service_ids = isset($module_data) ? json_decode($module_data->services, true) ?? [] : [];
    $cusines_ids = isset($module_data) ? json_decode($module_data->cusines, true) ?? [] : [];
    ?>

    <div class="col-md-12">
        {!! Form::label('term', 'Select Service', ['class' => '']) !!}
        <div class="card mb-4">
            <div class="card-body">  
                <div class="row">
                    @foreach($services as $key => $service)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" <?php if(in_array($service->id,$service_ids)){echo 'checked';} ?> type="checkbox" name="services[]" value="{{$service->id}}">
                            <label class="form-check-label" for="checkbox{{$service->id}}">{{$service->title}}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        {!! Form::label('term', 'Select Cusines', ['class' => '']) !!}
        <div class="card mb-4">
            <div class="card-body">  
                <div class="row">
                    @foreach($cusines as $key => $cusine)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" <?php if(in_array($cusine->id,$cusines_ids)){echo 'checked';} ?> type="checkbox" name="cusines[]" value="{{$cusine->id}}">
                            <label class="form-check-label" for="checkbox{{$cusine->id}}">{{$cusine->title}}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif   


    @if($module->is_description)
    <?php 
        $html = null;
        if($module->id==12 && !isset($module_data)){
            $html = widget(2)->description;
        }
    ?>
    <div class="col-md-12">
        <div class="mb-3">
            {!! Form::label('description', $module->term.' Description', ['class' => '']) !!}
            {!! Form::textarea('description', $html, array('class'=>'form-control editor1', 'id'=>'editor1', 'placeholder'=>$module->term.' Description', 'required'=>'required')) !!}
        </div>
    </div>
    @endif

    @if($module->is_highlights)
    <div class="col-md-12">
        <div id="dynamic_form">
            <div class="mb-3">
                {!! Form::label('is_highlights', $module->term.' Highlights', ['class' => '']) !!}
                <div class="row">
                    <div class="col-md-10">
                        <input type="text" name="highlights" id="highlights" class="form-control" placeholder="{{$module->term}} Highlights">
                    </div>
                    <div class="col-md-2">
                        <div class="button-group">
                            <a style="font-size: 32px; height: 40px; width: 40px; border-radius: 50%;" href="javascript:void(0)" class="btn btn-primary btn-sm" id="plus"><i class="fas fa-circle-plus"></i></a>
                            <a style="font-size: 32px; height: 40px; width: 40px; border-radius: 50%;" href="javascript:void(0)" class="btn btn-danger btn-sm" id="minus"><i class="fas fa-circle-minus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($module->is_image)
    <div class="col-md-12">
        <div class="mb-3">
            <input type="file" name="image" id="filer_input1">
        </div>
    </div>
    @endif

    @if($module->tags)
    <div class="col-md-12">
        <div class="mb-3">
            @php
            if(isset($module_data)){
                $tag_ids = explode(',',$module_data->tag_ids);
            }
            @endphp
            {!! Form::label('tag_ids', $module->term.' Tags', ['class' => '']) !!}
            {!! Form::select('tag_ids[]', $tags, isset($tag_ids)?$tag_ids:null, array('class'=>'js-example-basic-multiple col-sm-12 select2-hidden-accessible', 'id'=>'tag_ids', 'multiple'=>'multiple')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'tag_ids') !!}
        </div>
    </div>
    @endif

    @if($module->is_seo)
    <div class="col-md-12">
        <div class="mb-3">
            {!! Form::label('meta_title', $module->term.' Meta Title', ['class' => '']) !!}
            {!! Form::text('meta_title', null, array('class'=>'form-control', 'id'=>'meta_title', 'placeholder'=>$module->term.' Meta Title')) !!}
        </div>
    </div>
    <div class="col-md-12">
        <div class="mb-3">
            {!! Form::label('meta_keywords', $module->term.' Meta Keywords', ['class' => '']) !!}
            {!! Form::text('meta_keywords', null, array('class'=>'form-control', 'id'=>'meta_keywords', 'placeholder'=>$module->term.' Meta Keywords')) !!}
        </div>
    </div>
    <div class="col-md-12">
        <div class="mb-3">
            {!! Form::label('meta_description', $module->term.' Meta Description', ['class' => '']) !!}
            {!! Form::textarea('meta_description', null, array('class'=>'form-control', 'id'=>'meta_description', 'placeholder'=>$module->term.' Meta Description')) !!}
        </div>
    </div>
    @endif
</div>

@push('js')


@include('admin.ckeditor.index')

<script type="text/javascript">
    <?php $fieldsss = checkCityAndGr($module->id); ?>

    @if(isset($fieldsss[0]) && isset($fieldsss[1]) && isset($fieldsss[2]))

     $(document).ready(function () {
            $('#{{$fieldsss[0]}}').on('change', function () {
                var val = $(this).val().toLowerCase(); // Convert to lowercase for case-insensitive comparison
                if (val === 'delhi') {
                    $('#{{$fieldsss[1]}}').removeAttr('required');
                    $('#{{$fieldsss[1]}}_span').html('');
                }
            });
        });
        function checcityandgr(val){
            if (val.toLowerCase() === 'delhi') {
                    $('#{{$fieldsss[1]}}').removeAttr('required');
                    $('#{{$fieldsss[1]}}_span').html('');
                }
        }

        

        @if(isset($fieldsss[3]) && isset($fieldsss[4]))

            $('#{{$fieldsss[4]}}_div').hide();

            @if(isset($module_data))
                <?php $fieldddd = $fieldsss[3]; ?>
                invoicevalue(parseInt('{{$module_data->$fieldddd}}'));
            @endif

            $('#{{$fieldsss[3]}}').on('keyup', function () {
                invoicevalue($(this).val());
            });


            function invoicevalue(val){
                var city  = $('#{{$fieldsss[0]}}').val().toLowerCase();
                if (city === 'delhi') {
                    if( val >=100000){
                        $('#{{$fieldsss[4]}}_div').show();
                    }else{
                        $('#{{$fieldsss[4]}}_div').hide();
                    }
                }else{
                    if( val >=50000){
                        $('#{{$fieldsss[4]}}_div').show();
                    }else{
                        $('#{{$fieldsss[4]}}_div').hide();
                    }
                }
            }

        @endif

        $('#{{$fieldsss[2]}}').change(function() {
          // Get the selected value
          var selectedValue = $(this).val();

          // Make an AJAX request when the dropdown changes
          $.ajax({
            url: "{{url('/admin/filter-parties')}}/"+selectedValue, // Replace with your actual URL
            type: 'GET', // Adjust the HTTP method as needed
            success: function(response) {
              // Handle the success response for the extra_field_2 dropdown change
              $('#{{$fieldsss[0]}}').val(response);
              checcityandgr(response);
            },
            error: function(error) {
              // Handle the error response for the extra_field_2 dropdown change
              $('#{{$fieldsss[0]}}').val('');
            }
          });
        });
    @endif

    @if($module->id == 29)
    $('#extra_field_10').on('change',function(){
        for (var i = 0; i < 25; i++) {
            if(i!=1 && $(this).val() == '126' && i!=10){
                $('#extra_field_'+i).removeAttr('required');
                $('#extra_field_'+i+'_span').html('');
                $('#extra_field_'+i+'_div').hide();
            }else{
                $('#extra_field_'+i).attr('required');
                $('#extra_field_'+i+'_span').html('*');
                $('#extra_field_'+i+'_div').show();
            }
            
        }
    })
    @endif

    
    
    @if($module->sub_category)
    sub_categories(<?php echo old('sub_category', (isset($module_data)) ? $module_data->sub_category : 0); ?>);

    $(document).on('change','#category',function(){
        sub_categories(0);
    })

    function sub_categories(id){
        var category = $('#category').val();
        $.ajax({
            url: "{{route('admin.filter-sub-categories')}}?category="+category+"&sub_category="+id,
            method: "GET",
        }).done(function(data) {
            $('#dd-subcategories').html(data);
            //$('#sub_category').select2(); 
        });
    }
    @endif

    @if($module->multi_images)
    images_limit = 10;
    @endif

    $(document).ready(function() {
        if ($(".form").length > 0) {
            $(".form").validate({
                validateHiddenInputs: true,
                ignore: "",
                rules: getConditionalValidations(),
                messages: getConditionalValidationMessages(),
            })
        }
    })
    
</script>
@endpush
