<x-admin-layout>   
<link href="{{asset('css/preview.css?v=2')}}" rel="stylesheet" />
    <div class="container-xl px-4">

        <div class="card mb-4">
            <livewire:admin.common.header :title="$module->name.' List'" :content="'List of all '.$module->name.' are below'" :icon="'fa-school'" :term="$module->term" :slug="url('/'.$module->slug)" :button="__($module->term.' List')"/>
            <div class="card-body">
                
               <div class="tab-content" id="nav-tabContent">
                <ul class="row">
                <?php 
                 echo '<li class="col-md-6"><div class="mb-3">'.Form::label('title', 'First Name', ['class' => '']).'<div class="form-control">'.$module_data->title.'</div></div></li>';
                for ($i = 1; $i <= 25; $i++){
                    
                        $field = "extra_field_title_$i";
                        $fieldName = "extra_field_type_$i";
                        $fieldType = $module->$fieldName;
                        $fieldTitle = "extra_field_$i";
                        if($module_data->$fieldTitle){
                        if ($fieldType === 'file') {
                            
                            $fileFields[] = $fieldTitle;
                        }elseif ($fieldType === 'select') {
                            echo '<li class="col-md-6"><div class="mb-3">'.Form::label('title', $module->$field, ['class' => '']).'<div class="form-control">'.title($module_data->$fieldTitle).'</div></div></li>';
                        }else{
                            echo '<li class="col-md-6"><div class="mb-3">'.Form::label('title', $module->$field, ['class' => '']).'<div class="form-control">'.$module_data->$fieldTitle.'</div></div></li>';
                        }
                    }
                    


                }
                ?>
                
                    <li class="col-md-12">{!!$module_data->description!!}</li>
                </ul>
                   
               </div>

            </div>

        </div>

        

    </div>


</x-admin-layout>