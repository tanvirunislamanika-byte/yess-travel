<x-admin-layout>   

    <div class="container-xl px-4">

        <div class="card mb-4">
            <livewire:admin.common.header :title="$module->name.' List'" :content="'List of all '.$module->name.' are below'" :icon="'fa-school'" :term="$module->term" :slug="url('/admin/'.$module->slug)" :button="__($module->term.' List')"/>
            <div class="card-body">
                
               <div class="tab-content" id="nav-tabContent">
                <ul class="row">
                    <li class="col-md-12">{!!$module_data->title!!}</li>
                <?php 
                for ($i = 1; $i <= 25; $i++){
                    $fieldName = "extra_field_type_$i";
                    $fieldType = $module->$fieldName;
                    $fieldTitle = "extra_field_$i";
                    if ($fieldType === 'file') {
                        
                        $fileFields[] = $fieldTitle;
                    }elseif ($fieldType === 'select') {
                        echo '<li class="col-md-6">'.title($module_data->$fieldTitle).'</li>';
                    }else{
                        echo '<li class="col-md-6">'.$module_data->$fieldTitle.'</li>';
                    }


                }
                ?>
                
                    <?php foreach ($fileFields as $key => $fileField) {

                    $filePath = asset("images/" . $module_data->$fileField);
                    //dd($filePath);
                        if (!empty($module_data->$fileField)) { ?>

                            <li class="col-md-6">{!!generateFileViews($filePath)!!}</li>

                       <?php }
                    }?>
                    <li class="col-md-12">{!!$module_data->description!!}</li>
                </ul>
                   
               </div>

            </div>

        </div>

        

    </div>


</x-admin-layout>