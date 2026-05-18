<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modules;
use App\Models\FieldsShow;
use App\Helpers\LocationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data['modules'] = Modules::get();
        return view('admin.modules.index')->with($data);
    }

    public function add() {
        $data['categories'] = Modules::select('name', 'id')->where('status','active')->pluck('name', 'id')->toArray();
        
        // Add location data for extra field attributes
        $data['location_data'] = LocationHelper::getLocationDataForModule();
        
        return view('admin.modules.add')->with($data);
    }

    public function edit($id) {
        $data = array();
        $data['categories'] = Modules::select('name', 'id')->where('status','active')->pluck('name', 'id')->toArray();
        
        // Add location data for extra field attributes
        $data['location_data'] = LocationHelper::getLocationDataForModule();
        
        $data['module'] = Modules::findorFail($id);
        return view('admin.modules.edit')->with($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'term' => 'required',
        ], [
            'name.required' => 'Module Name is required.',
            'term.required' => 'Module Term is required.',
        ]);
        $slug = Str::slug($request->name, '-');
        $slugs = unique_slug($slug, 'modules', $field = 'slug', $key = NULL, $value = NULL);
        $module = new Modules();
        $module->name = $request->name;
        $module->term = $request->term;
        $module->is_slug = $request->is_slug;
        $module->is_menu = $request->is_menu;
        $module->is_description = $request->is_description;
        $module->is_highlights = $request->is_highlights;
        $module->is_seo = $request->is_seo;
        $module->category = $request->category;
        $module->multiple_category = $request->multiple_category;
        $module->sub_category = $request->sub_category;
        $module->parent_sub_id = $request->parent_sub_id;
        $module->tags = $request->tags;
        $module->is_image = $request->is_image;
        $module->multi_images = $request->multi_images;
        $module->parent_id = $request->parent_id;
        $module->thumbnail_height = $request->thumbnail_height;
        $module->thumbnail_width = $request->thumbnail_width;
        $module->extra_fields = $request->extra_fields;
        $module->slug = $slugs;
        $module->save();


        if($request->extra_fields && (int)$request->extra_fields>0){
               for ($i=1; $i <=$request->extra_fields ; $i++) {
                    $extra_field_title = 'extra_field_title_'.$i;
                    $extra_field_type = 'extra_field_type_'.$i;
                    $extra_field_attr = 'extra_field_attr_'.$i;
                    $extra_field_sort = 'extra_field_sort_'.$i;
                    $extra_field_col = 'extra_field_col_'.$i;
                    $extra_field_max_length = 'extra_field_max_length_'.$i;
                    $extra_field_required = 'extra_field_required_'.$i;
                    $extra_field_required_message = 'extra_field_required_message_'.$i;
                    $extra_field_show = 'extra_field_show_'.$i;
                    $extra_field_show_in_list = 'extra_field_show_in_list_'.$i;

                    $module->$extra_field_title = $request->$extra_field_title;
                    $module->$extra_field_type = $request->$extra_field_type;
                    $module->$extra_field_attr = $request->$extra_field_attr;
                    $module->$extra_field_sort = $request->$extra_field_sort;
                    $module->$extra_field_col = $request->$extra_field_col;
                    $module->$extra_field_max_length = $request->$extra_field_max_length;
                    $module->$extra_field_required = $request->$extra_field_required;
                    $module->$extra_field_required_message = $request->$extra_field_required_message;
                    $module->$extra_field_show = $request->$extra_field_show;

                    if($request->$extra_field_show_in_list){
                        $field_show = new FieldsShow();
                        $field_show->module_id = $request->id;
                        $field_show->field = 'extra_field_'.$i;
                        $field_show->field_title = $request->$extra_field_title;
                        $field_show->field_type = $request->$extra_field_type;
                        $field_show->field_attr = $request->$extra_field_attr;
                        $field_show->save();
                    }
            }
        }
        





        if ($module->save() == true) {
            $request->session()->flash('message.added', 'success');
            $request->session()->flash('message.content', 'A module has been successfully Created!');
        }
        return redirect(route('admin.modules'));
    }


    public function add_columns(Request $request)
    {
        Schema::table('modules', function (Blueprint $table) use($request) {

            for ($i=$request->start; $i <=$request->end ; $i++) { 

                $extra_field_title = 'extra_field_title_'.$i;
                $extra_field_type = 'extra_field_type_'.$i;
                $extra_field_attr = 'extra_field_attr_'.$i;
                $extra_field_sort = 'extra_field_sort_'.$i;
                $extra_field_col = 'extra_field_col_'.$i;
                $extra_field_max_length = 'extra_field_max_length_'.$i;
                $extra_field_required = 'extra_field_required_'.$i;
                $extra_field_required_message = 'extra_field_required_message_'.$i;
                $extra_field_show = 'extra_field_show_'.$i;

                $table->text($extra_field_title)->nullable();
                $table->text($extra_field_type)->nullable();
                $table->text($extra_field_attr)->nullable();
                $table->text($extra_field_sort)->nullable();
                $table->text($extra_field_col)->nullable();
                $table->text($extra_field_max_length)->nullable();
                $table->text($extra_field_required)->nullable();
                $table->text($extra_field_required_message)->nullable();
                $table->text($extra_field_show)->nullable();
                
            }
        });
    }


    public function add_module_data_columns(Request $request)
    {
        Schema::table('modules_data', function (Blueprint $table) use($request) {

            for ($i=$request->start; $i <=$request->end ; $i++) { 

                $extra_field = 'extra_field_'.$i;

                $table->string($extra_field)->nullable();
                
                
            }
        });
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'term' => 'required',
        ], [
            'name.required' => 'Module Name is required.',
            'term.required' => 'Module Term is required.',
        ]);
        
        $module = Modules::findorFail($request->id);
        if(trim($module->name) != trim($request->name)){
            $slug = Str::slug($request->name, '-');
            $slugs = unique_slug($slug, 'modules', $field = 'slug', $key = NULL, $value = NULL);
            $module->slug = $slugs;
        }
        $module->name = $request->name;
        $module->term = $request->term;
        $module->is_slug = $request->is_slug;
        $module->is_menu = $request->is_menu;
        $module->is_description = $request->is_description;
        $module->is_highlights = $request->is_highlights;
        $module->is_seo = $request->is_seo;
        $module->is_preview = $request->is_preview;
        $module->category = $request->category;
        $module->multiple_category = $request->multiple_category;
        $module->sub_category = $request->sub_category;
        $module->parent_sub_id = $request->parent_sub_id;
        $module->tags = $request->tags;
        $module->is_image = $request->is_image;
        $module->multi_images = $request->multi_images;
        $module->parent_id = $request->parent_id;
        $module->thumbnail_height = $request->thumbnail_height;
        $module->thumbnail_width = $request->thumbnail_width;
        $module->extra_fields = $request->extra_fields;
        if($request->extra_fields && (int)$request->extra_fields>0){
           for ($i=1; $i <=$request->extra_fields ; $i++) {
                $extra_field_title = 'extra_field_title_'.$i;
                $extra_field_type = 'extra_field_type_'.$i;
                $extra_field_attr = 'extra_field_attr_'.$i;
                $extra_field_sort = 'extra_field_sort_'.$i;
                $extra_field_col = 'extra_field_col_'.$i;
                $extra_field_max_length = 'extra_field_max_length_'.$i;
                $extra_field_required = 'extra_field_required_'.$i;
                $extra_field_required_message = 'extra_field_required_message_'.$i;
                $extra_field_show = 'extra_field_show_'.$i;
                $extra_field_show_in_list = 'extra_field_show_in_list_'.$i;

                $module->$extra_field_title = $request->$extra_field_title;
                $module->$extra_field_type = $request->$extra_field_type;
                $module->$extra_field_attr = $request->$extra_field_attr;
                $module->$extra_field_sort = $request->$extra_field_sort;
                $module->$extra_field_col = $request->$extra_field_col;
                $module->$extra_field_max_length = $request->$extra_field_max_length;
                $module->$extra_field_required = $request->$extra_field_required;
                $module->$extra_field_required_message = $request->$extra_field_required_message;
                $module->$extra_field_show = $request->$extra_field_show;

                if($request->$extra_field_show_in_list){
                    $field_show = FieldsShow::where('field','extra_field_'.$i)->where('module_id',$request->id)->first();
                    if($field_show){
                        $field_show->module_id = $request->id;
                        $field_show->field = 'extra_field_'.$i;
                        $field_show->field_title = $request->$extra_field_title;
                        $field_show->field_type = $request->$extra_field_type;
                        $field_show->field_attr = $request->$extra_field_attr;
                        $field_show->update();
                    }else{
                        $field_show = new FieldsShow();
                        $field_show->module_id = $request->id;
                        $field_show->field = 'extra_field_'.$i;
                        $field_show->field_title = $request->$extra_field_title;
                        $field_show->field_type = $request->$extra_field_type;
                        $field_show->field_attr = $request->$extra_field_attr;
                        $field_show->save();
                    }
                    
                    
                }else{
                    $field_show = FieldsShow::where('field','extra_field_'.$i)->where('module_id',$request->id)->first();
                    if($field_show){
                        $field_show->delete();
                    }
                }
           }
        }
        $module->update();
        if ($module->update() == true) {
            $request->session()->flash('message.added', 'success');
            $request->session()->flash('message.content', 'A module has been successfully Updated!');
        }
        return redirect(route('admin.modules'));
    }

    public function destroy(Request $request, $id)
    {
        $module = Modules::findOrFail($id);
        $module->delete();
        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', 'A module has been successfully Deleted!');
        return redirect(route('admin.modules'));
    }
}
