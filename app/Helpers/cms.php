<?php 


// Fallback menu translation function
if (!function_exists('translate_menu')) {
    function translate_menu($menu, $locale = null) {
        if (!$locale) {
            $locale = app()->getLocale();
        }
        
        if ($locale === 'en') {
            return $menu->title;
        }
        
        // Check stored menu translation
        $translation = \DB::table('menu_translations')
            ->where('menu_id', $menu->id)
            ->where('locale', $locale)
            ->value('title');
            
        return $translation ?: $menu->title;
    }
}

use Illuminate\Support\Facades\DB;

use App\Models\Menu;
use Illuminate\Support\Str;
use App\Models\Widgets;
use App\Models\WidgetsData;
use App\Models\ModulesData;
use App\Models\Contact_us;
use App\Models\Modules;
use App\Models\Appointments;
use Carbon\Carbon;

if (! function_exists('seo')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function seo($slug)
    {
        $array = '';
        if($slug){

          $cms = ModulesData::where('slug',$slug)->where('status','active')->first();
          if($cms){
            $data['title'] = $cms->getTranslatedMetaTitle() ?: $cms->getTranslatedTitle();
            $data['meta_description'] = $cms->getTranslatedMetaDescription() ?: $cms->getTranslatedTitle();
            $data['meta_keywords'] = $cms->getTranslatedMetaKeywords() ?: $cms->getTranslatedTitle();
          }else{
            $widget = Widgets::findorFail(1);
            $widgetData = $widget->widget_data;
            $data['title'] = $widgetData->extra_field_1;
            $data['meta_description'] = $widgetData->extra_field_2;
            $data['meta_keywords'] = $widgetData->extra_field_3;
          }
          
        }else{
          $widget = Widgets::findorFail(1);
          $widgetData = $widget->widget_data;
          $data['title'] = $widgetData->extra_field_1;
          $data['meta_description'] = $widgetData->extra_field_1;
          $data['meta_keywords'] = $widgetData->extra_field_1;
        }
        
        return $data;
    }
} 

if (! function_exists('adminMenus')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @return array
     */
    function adminMenus()
    {       
        $modules = App\Models\Modules::orderByRaw('sorting_order IS NULL, sorting_order ASC')->get();
        $array = array();
        if (null !== $modules) {
            foreach ($modules as $key => $val) {
                $array[$val->name] = array('icon' => 'grid', $val->name => url('admin/' . $val->slug), 'Create ' . $val->term => url('admin/' . $val->slug . '/add'));
            }
        }
        $menus = array(
            'Custom' => $array,);

        //dd($menus);
        return $menus;  
    }
}
if (! function_exists('countries')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function countries()
    {
        $array = ModulesData::select('title', 'id')->where('module_id',22)->pluck('title', 'id')->toArray();
        return $array;
    }
}
if (! function_exists('optionAttributes')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function optionAttributes($array,$attr)
    {
        $arr2 = array();
        if (null!==($array)) {
            foreach ($array as $key => $value) {
                $arr2[$key] = array(
                    $attr => $key
                );
            }
        }
        return $arr2;
    }
} 
if (! function_exists('frontMenus')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @return array
     */
    function frontMenus()
    {       
        $modules = App\Models\Modules::where('permissions','yes')->orderByRaw('sorting_order IS NULL, sorting_order ASC')->get();
        $array = array();
        if (null !== $modules) {
            foreach ($modules as $key => $val) {
                $array[$val->name] = array('icon' => 'grid', $val->name => url('/' . $val->slug), 'Create ' . $val->term => url('admin/' . $val->slug . '/add'));
            }
        }
        $menus = array(
            'Custom' => $array,);

        //dd($menus);
        return $menus;  
    }
}
if (! function_exists('findOrCreate')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function findOrCreate($id,$tit)
    {
        $array = ModulesData::where('title',$tit)->where('module_id',$id)->first();
        if($array){
          $title = $array->id;
        }else{
            if($tit){
                $ddd = new ModulesData();
                $ddd->title = $tit;
                $ddd->module_id = $id;
                $ddd->save();
                $title = $ddd->id;
            }else{
                $title = null;
            }
            
        }
        
        return $title;
        }
    }  
if (! function_exists('adminDashbaord')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @return array
     */
    function adminDashbaord()
    {       
        $modules = App\Models\Modules::where('sorting_order','!=','')->orderBy('sorting_order','ASC')->get();
        $array = array();
        if (null !== $modules) {
            foreach ($modules as $key => $val) {
                $array[$val->name] = array('icon' => 'grid', $val->name => url('admin/' . $val->slug), 'Create ' . $val->term => url('admin/' . $val->slug . '/add'));
            }
        }
        $menus = array(
            'Custom' => $array,);

        //dd($menus);
        return $menus;  
    }
}

if (! function_exists('checkCityAndGr')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function checkCityAndGr($moduleId)
    {
        $module = Modules::findOrFail($moduleId);
        $fileFields = [];
        $aaaa = [];
        for ($i = 1; $i <= 25; $i++) {
            $fieldName = "extra_field_title_$i";
            if($module->$fieldName == 'City'){
                $fileFields[0] = "extra_field_$i";
            }
            if(trim($module->$fieldName) == 'G.R **'){

                $fileFields[1] = "extra_field_$i";
            }

            if($module->$fieldName == 'Party Name'){
                $fileFields[2] = "extra_field_$i";
            }

            if($module->$fieldName == 'Invoice amount'){
                $fileFields[3] = "extra_field_$i";
            }

            if($module->$fieldName == 'Eway Bill'){
                $fileFields[4] = "extra_field_$i";
            }

            $aaaa[] = $module->$fieldName;
            
        }

        return $fileFields;
    }
}  

if (! function_exists('checkCompleteIncomplete')) {
    /**
     * Check if any file fields in ModulesData object are empty.
     *
     * @param  int  $id
     * @return bool
     */
    function checkCompleteIncomplete($id)
    {
        $modulesData = ModulesData::findOrFail($id);
        $module = Modules::findOrFail($modulesData->module_id);

        for ($i = 1; $i <= 25; $i++) {
            $fieldName = "extra_field_type_$i";
            $fieldType = $module->$fieldName;

            if ($fieldType === 'file') {
                $fieldTitle = "extra_field_$i";
                $fieldValue = $modulesData->$fieldTitle;

                if (empty($fieldValue)) {
                    // If any file field is empty, return false
                    return false;
                }
            }
        }

        // If all file fields are non-empty, return true
        return true;
    }
}

function unique_auto($string, $table, $field = 'slug', $module)
{
   //$field = 'extra_field_4';
    $slug = url_title($string);
    $slug = strtolower($slug);
    $i = 1; // Start the counter at 1 instead of 0
    $params = [$field => $slug, 'module_id' => $module];
    $maxValue = DB::table($table)
    ->where('module_id', $module)
    ->max(DB::raw('CAST(' . $field . ' AS SIGNED)'));
        
   //dd($maxValue);
        
    $atuo = $maxValue + 1;
        

    $params[$field] = $atuo;
    return $params[$field];
}


if (! function_exists('permissions')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function permissions()
    {       
            $permissions = array();
            if(Auth::user()->roles() && isset(Auth::user()->roles()->get()[0])){
                $permissions = Auth::user()->roles()->get()[0]->permissions()->pluck('name')->toArray();
            }
            
            //dd($permissions);
            return $permissions;  
    }
}

if (! function_exists('role')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function role($id)
    {       
            $role = Spatie\Permission\Models\Role::where('id',$id)->first();
            
            return $role?$role->name:null;  
    }
}

if (! function_exists('parent')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function parent($id)
    {       
            $role = App\Models\User::where('id',$id)->first();
            
            return $role?'('.$role->name.')':null;  
    }
}


if (! function_exists('unique_slug')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function unique_slug($string,$table,$field='slug',$key=NULL,$value=NULL)
    {
            $slug = url_title($string);
            $slug = strtolower($slug);
            $i = 0;
            $params = array ();
            $params[$field] = $slug;

            if($key)$params["$key !="] = $value;

            while (DB::table($table)->where('slug',$params)->first())
            {  
                if (!preg_match ('/-{1}[0-9]+$/', $slug ))
                    $slug .= '-' . ++$i;
                else
                    $slug = preg_replace ('/[0-9]+$/', ++$i, $slug );

                $params[$field] = $slug;
            }  
            return $slug;  
    }
}


function getFileExtension($fileName) {
    // Get the last part of the file name after the last dot
    $parts = explode(".", $fileName);
    $extension = end($parts);

    // Return the extension in lowercase
    return strtolower($extension);
}

if (! function_exists('url_title')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function url_title($str, $separator = '-', $lowercase = FALSE)
    {
            if ($separator === 'dash')
        {
            $separator = '-';
        }
        elseif ($separator === 'underscore')
        {
            $separator = '_';
        }

        $q_separator = preg_quote($separator, '#');

        $trans = array(
            '&.+?;'         => '',
            '[^a-z0-9 _-]'      => '',
            '\s+'           => $separator,
            '('.$q_separator.')+'   => $separator
        );

        $str = strip_tags($str);
        foreach ($trans as $key => $val)
        {
            $str = preg_replace('#'.$key.'#i', $val, $str);
        }

        if ($lowercase === TRUE)
        {
            $str = strtolower($str);
        }

        return trim(trim($str, $separator));
    }
} 

function generateFileViews($filePath)
{
    $html = '';
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        $fileName = basename($filePath);

        switch ($fileExtension) {
            case 'pdf':
                // Display PDF in an iframe
                $html .= '<iframe src="' . $filePath . '" width="100%" height="600px"></iframe>';
                break;

            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                // Display image using img tag
                $html .= '<img src="' . $filePath . '" alt="' . $fileName . '" style="max-width: 100%;">';
                break;

            case 'doc':
            case 'docx':
                // Display file name and provide download button
                $html .= '<p>' . $fileName . '</p>';
                $html .= '<a href="' . $filePath . '" download>Download</a>';
                break;

            // Add more cases for other file types as needed

            default:
                // For unsupported types, display a simple link for download
                $html .= '<p>' . $fileName . '</p>';
                $html .= '<a href="' . $filePath . '" download>Download</a>';
                break;
        }

        $html .= '<hr>'; // Add a horizontal line between files for clarity

    return $html;
}


if (! function_exists('title')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function title($id)
    {
        $array = ModulesData::select('title')->where('id',$id)->where('status','active')->first();
        //dd($array);
        $title = null;
        if(null!==($array)){
          $title = $array->title;
        }
        
        return $title;
        }
    }  

    if (! function_exists('uploadedImage')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function uploadedImage($id)
    {
        $array = ModulesData::select('image')->where('id',$id)->where('status','active')->first();
        //dd($array);
        $title = null;
        if(null!==($array)){
          $title = asset('images/'.$array->image);
        }
        
        return $title;
        }
    }  


if (! function_exists('pendingAppointments')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function pendingAppointments()
    {
        $array = Appointments::whereDate('date','>=', Carbon::today())->where('status','pending')->count();
        
        return $array;
    }
}  

if (! function_exists('appointments')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function appointments()
    {
        $array = Appointments::count();
        
        return $array;
    }
}  

    if (! function_exists('get_menus')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function get_menus($id)
    {
        $manues = '';
        $classli='';
        $spancart='';
        $nav_link = '';
        if($id==1){
          $nav_link = 'nav-link';
        }
        $parent_menus = Menu::where('menu_type_id',$id)->orderBy('order', 'ASC')->get();
        foreach($parent_menus as $menu){
             if (is_child($menu->id) == TRUE) {
                $classli='class="dropdown dropnav"';
                $spancart='<i class="fas fa-caret-down"></i>';
            }
            else{
                $classli='class="nav-item ps-2"';
                $spancart='';
            }
            if($menu->menu_is=='internal'){
                $menu_url=url('/').'/'.$menu->slug;
            }else{
                $menu_url=url('/').'/'.$menu->slug;
            }
          $translatedMenuTitle = translate_menu($menu);
          $manues.='<li '.$classli.'>';
          $manues.='<a href="'.$menu_url.'" class="'.$nav_link.'">'.$translatedMenuTitle.''.$spancart.'</a>';
          if (is_child($menu->id) == TRUE) {
              $manues.='<ul class="dropdown-menu">';
              if(null!==($menu->submenus)){
                foreach($menu->submenus as $submenu){

                  if($submenu->menu_is=='internal'){
                        $submenu_url=url('/').'/'.$submenu->slug;
                    }else{
                        $submenu_url=$submenu->slug;
                    }
                   // Check if translate_menu function exists, otherwise use original title
                   if (function_exists('translate_menu')) {
                       $translatedSubmenuTitle = translate_menu($submenu);
                   } else {
                       $translatedSubmenuTitle = $submenu->title;
                   }
                   $manues.='<li>';
                   $manues.='<a href="'.$submenu_url.'" class="'.$nav_link.'">'.$translatedSubmenuTitle.'</a>';
                   $manues.='</li>';
                }
              }
              
              $manues.='</ul>'; 
          }
             $manues.='</li>';
            }         

            return $manues;
        }
}

        if (! function_exists('is_child')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function is_child($id)
    {
        $child_menus = Menu::where('parent_id',$id)->first();
        if ($child_menus) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}


    if (! function_exists('messages_count')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function messages_count()
    {
        return Contact_us::where('status','unseen')->count();
    }
}

if (! function_exists('widget')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function widget($id)
    {
        $widget = Widgets::findorFail($id);
        $array = $widget->widget_data;
        return $array;
    }
} 

if (! function_exists('dataArray')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function dataArray($id)
    {
        $array = ModulesData::select('title', 'id')->where('module_id',$id)->where('status','active')->pluck('title', 'id')->toArray();
        return $array;
    }
} 

if (! function_exists('widgetPage')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function widgetPage($id)
    {
        $widget = Widgets::findorFail($id);
        $array = $widget->page;
        return $array;
    }
}  

if (! function_exists('asset_image_versioned')) {
    function asset_image_versioned($filename)
    {
        $path = public_path('images/' . $filename);
        if ($filename && file_exists($path)) {
            return asset('images/' . $filename) . '?v=' . filemtime($path);
        }
        return asset('images/' . $filename);
    }
}

if (! function_exists('dropdown')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function dropdown($id)
    {
        $array = ModulesData::select('title', 'id')->where('module_id',$id)->where('status','active')->pluck('title', 'id')->toArray();
        return $array;
    }
} 
if (! function_exists('module')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function module($id,$paginate='')
    {
      if($paginate !== ''){
        $array = ModulesData::where('module_id',$id)->where('status','active')->paginate($paginate);
      }else{
        $array = ModulesData::where('module_id',$id)->where('status','active')->get();
      }
        
        return $array;
    }
}

if (! function_exists('moduleF')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function moduleF($id,$paginate='')
    {
      if($paginate !== ''){
        $array = ModulesData::where('module_id',$id)->where('status','active')->where('extra_field_2','406')->paginate($paginate);
      }else{
        $array = ModulesData::where('module_id',$id)->where('status','active')->where('extra_field_2','406')->get();
      }
        
        return $array;
    }
} 


if (! function_exists('toCities')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function toCities()
    {
        $distinctFields = ModulesData::select('extra_field_6')->where('module_id',3)
        ->distinct()
        ->pluck('extra_field_6');
        
        return $distinctFields;
    }
} 

if (! function_exists('toCitiesH')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function toCitiesH()
    {
        $distinctFields = ModulesData::select('extra_field_18')->where('module_id',1)
        ->distinct()
        ->pluck('extra_field_18');
        
        return $distinctFields;
    }
} 

if (! function_exists('toCountries')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function toCountries()
    {
        $distinctFields = ModulesData::select('extra_field_7')->where('module_id',3)
        ->distinct()
        ->pluck('extra_field_7');

        $array = ModulesData::whereIn('id',$distinctFields)
        ->distinct()
        ->pluck('title','id');
        
        return $array;
    }
} 

if (! function_exists('toCountriesH')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function toCountriesH()
    {
        $distinctFields = ModulesData::select('extra_field_21')->where('module_id',1)
        ->distinct()
        ->pluck('extra_field_21');

        $array = ModulesData::whereIn('id',$distinctFields)
        ->distinct()
        ->pluck('title','id');
        
        return $array;
    }
} 

if (! function_exists('moduleSlug')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function moduleSlug($id)
    {
        $array = Modules::findorFail($id);
        
        return $array;
    }
}  


if (! function_exists('messages')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function messages()
    {
        return Contact_us::where('status','unseen')->orderBy('id','asc')->take(5)->get();
    }
}

if (! function_exists('getFieldAttributes')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  array  $array
     * @return array
     */
    function getFieldAttributes($module, $i)
    {
        $orderSort = 'extra_field_sort_' . $i;
        $fieldCol = 'extra_field_col_' . $i;
        $fieldTitle = 'extra_field_title_'.$i;
        $fieldName = 'extra_field_'.$i;
        $fieldType = 'extra_field_type_'.$i;
        $fieldMaxLength = 'extra_field_max_length_'.$i;
        $fieldRequired = 'extra_field_required_'.$i;
        $fieldRequiredMessage = 'extra_field_required_message_'.$i;
        $fieldShow = 'extra_field_show_'.$i;
        $fieldAttr = 'extra_field_attr_'.$i;

        $isRequired = $module->$fieldRequired == 1 ? 'required' : '';

        return [
            $module->$orderSort => [
                $module->$fieldTitle, $fieldName, $module->$fieldType,
                $module->$fieldMaxLength, $module->$fieldRequired, $module->$fieldRequiredMessage,
                $module->$fieldShow, $module->$fieldAttr, $isRequired, $fieldCol
            ]
        ];
    }
}








 ?>