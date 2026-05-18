<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modules;
use App\Models\Menu_types;
use App\Models\ModulesData;
use App\Models\Tags;
use App\Models\Menu;
use App\Models\History;
use App\Models\Assigned_contacts;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use DataTables;
use PDF;

class ModulesDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($slug = '')
    {
        $data = [
            'module' => Modules::where('slug', $slug)->firstOrFail(),
        ];

        if ($data['module']->parent_id) {
            $data['parent'] = Modules::findOrFail($data['module']->parent_id);
        }

        return view('modules_data.index')->with($data);
    }

    public function add($slug)
    {
        $data = [
            'module' => Modules::where('slug', $slug)->firstOrFail(),
            'menu_types' => Menu_types::where('status', 'active')->pluck('title', 'id')->toArray(),
        ];

        if ($data['module']->parent_id) {
            $data['categories'] = ModulesData::where('module_id', $data['module']->parent_id)
                ->where('status', 'active')->pluck('title', 'id')->toArray();
        }

        $data['tags'] = dropdown(3);

        return view('admin.modules_data.add')->with($data);
    }

    public function edit($slug, $id)
    {
        $data = [
            'module' => Modules::where('slug', $slug)->firstOrFail(),
            'menu_types' => Menu_types::where('status', 'active')->pluck('title', 'id')->toArray(),
            'module_data' => ModulesData::findOrFail($id),
        ];

        if ($data['module']->parent_id) {
            $data['categories'] = ModulesData::where('module_id', $data['module']->parent_id)
                ->where('status', 'active')->pluck('title', 'id')->toArray();
        }

        $data['tags'] = dropdown(3);

        return view('modules_data.edit')->with($data);
    }

    public function filterParties($id)
    {
        $module_data = ModulesData::findOrFail($id);
        echo $module_data->extra_field_4;
    }

    public function preview($slug, $id)
    {
        $data = [
            'module' => Modules::where('slug', $slug)->firstOrFail(),
            'menu_types' => Menu_types::where('status', 'active')->pluck('title', 'id')->toArray(),
            'module_data' => ModulesData::findOrFail($id),
        ];

        return view('modules_data.preview')->with($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ], [
            'title.required' => 'Title is required.',
        ]);

        $slug = $request->slug;
        $slugs = unique_slug($slug, 'modules_data', 'slug');
        $dynamic_form = $request->input('dynamic_form');

        $data = new ModulesData($request->only([
            'title', 'description', 'category', 'sub_category', 'module_id', 'meta_title', 'meta_keywords', 'meta_description', 'image', 'images'
        ]));

        $data->slug = $slugs;
        $data->category_ids = $request->category_ids ? implode(",", $request->category_ids) : null;

        $this->setExtraFields($data, $request);

        $data->tag_ids = $request->tag_ids ? implode(",", $request->tag_ids) : null;

        $this->saveDynamicForm($data, $dynamic_form);
        $data->user_id = auth()->user()->id;

        $data->save();
        $module = Modules::findOrFail($data->module_id);
        if($module->is_preview){
            $check = checkCompleteIncomplete($data->id);
            $statusType = $check ? 'active' : 'blocked';
            $data->status = $statusType;
            $data->update();
        }

        $history = new History();
        $history->data_id = $data->id;
        $history->message = auth()->user()->name.' has create this record at '.date('Y-m-d H:i:s');
        $history->save();

        $this->saveMenuTypes($request, $data);

        if ($request->ajax()) {
            return response()->json(['id' => $data->id, 'title' => $data->title]);
        }

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', $request->module_term . ' has been successfully Created!');

        

        return redirect(route('admin.modules.data', $request->module_slug));
    }

    public function update(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'title' => 'required',
        ], [
            'title.required' => 'Title is required.',
        ]);

        $slug = $request->slug;
        $data = ModulesData::findOrFail($request->id);

        $data->fill($request->only([
            'title', 'description', 'category', 'sub_category', 'module_id', 'meta_title', 'meta_keywords', 'meta_description', 'image', 'images',
        ]));

        $data->slug = $slug;
        $data->category_ids = $request->category_ids ? implode(",", $request->category_ids) : null;

        $this->setExtraFields($data, $request);

        $data->tag_ids = $request->tag_ids ? implode(",", $request->tag_ids) : null;
        $data->final_submit = $request->finalSubmit=='yes' ? 'yes' : 'no';


        $this->saveDynamicForm($data, $request->dynamic_form);

        $data->update();

        $module = Modules::findOrFail($data->module_id);

        if($module->is_preview){
            $check = checkCompleteIncomplete($data->id);
            $statusType = $check ? 'active' : 'blocked';
            $data->status = $statusType;
            $data->update();
        }

        $history = new History();
        $history->data_id = $data->id;
        $history->message = auth()->user()->name.' has update this record at '.date('Y-m-d H:i:s');
        $history->save();

        $this->saveMenuTypes($request, $data);

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', $request->module_term . ' has been successfully Updated!');

        return redirect(route('modules.data', $request->module_slug));
    }

    private function setExtraFields($data, $request)
    {
        for ($i = 1; $i <= 25; $i++) {
            $extra_field = 'extra_field_' . $i;
            

            // Check if the field is a file and has been uploaded
            if ($request->hasFile($extra_field) && $request->file($extra_field)->isValid()) {
                $file = $request->file($extra_field);
                $data->$extra_field = $file->getClientOriginalName();
                $file->move(public_path('images'), $file->getClientOriginalName());
            }else{
                if(!empty($request->$extra_field)){
                    $data->$extra_field = $request->$extra_field;
                }
                
            }
        }
    }


    public function assignContacts(Request $request)
    {
        
        $users = explode(',', $request->selected_users);
        $selectcontactsstr = $request->selectcontacts;
        $selectcontacts = explode(',', $selectcontactsstr);
        //dd($selectcontacts);
        if(null!==($users)){
            foreach ($users as $key => $user) {

                if(null!==($selectcontacts)){
                    foreach ($selectcontacts as $key => $contact) {
                        if(!$contact){
                            continue;
                        }
                        
                        $contac = ModulesData::findOrFail($contact);
                        $contac->assign_to = $user;
                        $contac->start_date = $request->start_date;
                        $contac->end_date = $request->end_date;
                        $contac->task = $request->task;
                        $contac->update();
                       // dd($contact);
                        $ass = Assigned_contacts::where('user_id',$user)->where('contact_id',$contact)->first();
                        if(!$ass){
                            $ass = new Assigned_contacts();
                        }
                        
                        $ass->user_id = $user;
                        $ass->contact_id = $contact?$contact:'';
                        $ass->start_date = $request->start_date;
                        $ass->end_date = $request->end_date;
                        $ass->task = $request->task;
                        //dd($ass);
                        $ass->save();
                        
                    }
                }
                
            }
        }

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', $request->module_term . ' has been successfully Assigned!');

        

        return redirect()->back();
    }

    public function deleteContacts(Request $request,$id,$user)
    {
        
        Assigned_contacts::where('user_id',$user)->where('contact_id',$id)->delete();
        $contact = ModulesData::findOrFail($id);
        $contact->assign_to = null;
        $contac->start_date = null;
        $contac->end_date = null;
        $contac->task = null;
        $contact->update();

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', $request->module_term . ' has been successfully Unassigned!');

        

        return redirect()->back();
    }

    private function saveDynamicForm($data, $dynamic_form)
    {
        if (isset($dynamic_form['dynamic_form']) && $dynamic_form['dynamic_form'] !== null) {
            $data->highlights = json_encode($dynamic_form['dynamic_form']);
        }
    }

    private function saveMenuTypes(Request $request, $data)
    {
        $menu_types = Menu_types::where('status', 'active')->pluck('title', 'id')->toArray();

        if ($menu_types) {
            foreach ($menu_types as $key => $menu_type) {
                $field = 'menu_' . $key;

                if ($request->$field) {
                    $menu = new Menu([
                        'title' => $data->title,
                        'slug' => $data->slug,
                        'menu_type_id' => $key,
                        'post_id' => $data->id,
                        'parent_id' => 0,
                        'order' => Menu::max('order') + 1,
                        'menu_is' => 'internal',
                    ]);

                    $menu->save();
                }
            }
        }
    }

   public function fetchModulesData(Request $request)
    {
        $module = Modules::findOrFail($request->id);
        if (auth()->user()->hasRole('admin')) {
            $modulesDataQuery = ModulesData::where('module_id', $module->id)->orderBy('id','DESC');
        }else{
            $modulesDataQuery = ModulesData::where('module_id', $module->id)->where('assign_to',auth()->user()->id)->orderBy('id','DESC');
        }
        $selectcontacts = array();
        if($request->selectcontacts){
            $selectcontacts = explode(',', $request->selectcontacts);
        }
        
        

        $datacolumns = DataTables::of($modulesDataQuery)
            ->filter(function ($query) use ($request, $module) {
                $this->applyFilters($query, $request, $module);
            })
            ->addColumn('image', function ($modulesData) {
                return '<div class="image-container"><img src="' . asset('/images/thumb/' . $modulesData->image) . '" alt=""></div>';
            }) 
           ->addColumn('checkedvals', function ($modulesData) use ($selectcontacts) {
                return '<span><input type="checkbox" name="checkedvals[]" ' . (in_array($modulesData->id, $selectcontacts) ? 'checked' : '') . ' value="' . $modulesData->id . '" placeholder="" class=""></span>';
            })
            ->addColumn('title', function ($modulesData) use ($module){
                  return '<span>' . Str::limit(strip_tags($modulesData->title), 40, '...') . '</span>';  
            })
            ->addColumn('created_date', function ($modulesData) {
                return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $modulesData->created_at);
            })
            ->addColumn('category', function ($modulesData) use ($module) {
                return $this->getCategoryLinks($modulesData, $module);
            })
            ->addColumn('status', function ($modulesData) use ($module) {
                return $this->getStatusBadge($modulesData,$module);
            })
            ->addColumn('assigns', function ($modulesData) use ($module) {
                return $this->getAssigns($modulesData,$module);
            })
            ->addColumn('action', function ($modulesData) use ($module) {
                return $this->getActionButtons($module, $modulesData);
            })
            ->rawColumns(['title', 'status', 'action', 'image', 'category','checkedvals','assigns'])
            ->setRowId(function ($modulesData) {
                return 'countryDtRow' . $modulesData->id;
            });

        $this->addColumnFields($datacolumns, $module);

        return $datacolumns->make(true);
    }

    private function applyFilters($query, $request, $module)
    {
        if ($request->filled('title')) {
            $query->where('title', 'like', "%{$request->get('title') }%");
        }

        if ($request->filled('category')) {
            $query->where(function ($query) use ($request) {
                $query->where('category', '=', $request->get('category'))
                    ->orWhere('category_ids', 'like', "%{$request->get('category') }%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $fields = $module->fields()->get();

        foreach ($fields as $val) {
            $field = $val->field;

            if ($request->filled($field)) {
                $query->where($field , $request->$field);
            }
        }
    }

    private function getCategoryLinks($modulesData, $module)
    {
        if ($module->multiple_category) {
            $cateIds = explode(",", $modulesData->category_ids);
            $categories = ModulesData::whereIn('id', $cateIds)->get();
            $cateLinks = $categories->map(function ($cat) {
                return $cat->title;
            })->implode(' | ');

            return $cateLinks;
        } else {
            return title($modulesData->category);
        }
    }

    private function getStatusBadge($modulesData,$module)
    {
        $statusType = ($modulesData->status == 'active') ? 'success' : 'danger';
        $statusIcon = ($modulesData->status == 'active') ? 'check-circle' : 'times-circle';
        $statusText = ucfirst($modulesData->status);
      
        $status = '<span class="' . $statusType . '"><i class="fas fa-' . $statusIcon . '"></i>&nbsp;<span style="font-size: 12px;" class="status-text">' . $statusText . '</span></span>';

        return '<a class="waves-effect status waves-light" onclick="update_status(' . $modulesData->id . ');" href="javascript:void(0);" id="sts_' . $modulesData->id . '"> ' . $status . '</a>';
    }

    private function getAssigns($modulesData,$module)
    {
        $assigns = Assigned_contacts::where('contact_id',$modulesData->id)->pluck('user_id')->toArray();
        $users = User::whereIn('id',$assigns)->get();
        $str = '';
        if(null!==($users)){
            foreach ($users as $key => $user) {
                $del = route('admin.delete-contact',[$modulesData->id,$user->id]);
                $str .= '<span class="badge badge-primary">'.$user->name.'&nbsp &nbsp<a href="'.$del.'" class="">x</a></span>&nbsp&nbsp <br><b>'.(($modulesData->start_date)?date('m/d/Y',strtotime($modulesData->start_date)).' - '.date('m/d/Y',strtotime($modulesData->end_date)):'').'</b>';
            }
        }
        return $str;
    }

    private function getActionButtons($module, $modulesData)
    {
        $edit = '';
        $delete = '';
        $preview = ($module->is_preview)
            ? '<a target="_blank" href="' . route('modules.data.preview', [$module->slug, $modulesData->id]) . '"><i class="icofont icofont-eye-alt"></i>&nbsp;<i class="fa-solid fa-eye"></i></a>&nbsp&nbsp&nbsp'
            : '';
        if (auth()->user()->hasRole('admin')) {
            $edit = '<a class="" href="' . route('admin.modules.data.edit', [$module->slug, $modulesData->id]) . '"><i class="fa-solid fa-pen-to-square"></i></a>&nbsp&nbsp&nbsp';

            $delete = '<a class="" id="delete" href="' . route('admin.modules.data.delete', [$module->slug, $modulesData->id]) . '"><i class="fa-solid fa-trash"></i></a>';
        }else{
            
                $edit = '<a class="" href="' . route('modules.data.edit', [$module->slug, $modulesData->id]) . '"><i class="fa-solid fa-pen-to-square"></i></a>&nbsp&nbsp&nbsp';
            
        }    
        

        return $preview . $edit . $delete;
    }

    private function addColumnFields($datacolumns, $module)
    {
        $fields = $module->fields()->get();

        foreach ($fields as $val) {
            $field = $val->field;

            $datacolumns->addColumn($field, function ($modulesData) use ($field,$val) {
                $titleField = optional($modulesData)->$field;
                
                if ($titleField !== null) {
                    return ($val->field_type == 'select') ? title($titleField) : $titleField;
                }

                return '';
            });
        }
    }



    public function destroy(Request $request, $slug, $id)
    {
        $data = ModulesData::findOrFail($id);
        $slug = $data->slug;
        $data->delete();

        Menu::where('slug', $slug)->delete();

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', 'Successfully Deleted!');

        return redirect()->back();
    }

    public function destroyFile(Request $request, $id, $field)
    {
        $data = ModulesData::findOrFail($id);
        $data->$field = null;
        $data->update();

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', 'Successfully Deleted!');

        return redirect()->back();
    }

    public function update_status($id, $current_status)
    {
        if (empty($id) || empty($current_status)) {
            return response()->json(['error' => 'Invalid data provided.']);
        }

        $new_status = (strtolower($current_status) == 'active') ? 'blocked' : 'active';

        $module = ModulesData::findOrFail($id);
        $module->status = $new_status;
        $module->update();

        echo $new_status;
    }

    public function downloadFiles($id, $moduleId)
    {

        $data = [
            'module' =>  $module = Modules::findOrFail($moduleId),
            'menu_types' => Menu_types::where('status', 'active')->pluck('title', 'id')->toArray(),
            'module_data' => ModulesData::findOrFail($id),
        ];

        //return view('admin.modules_data.preview')->with($data);


        $pdf = PDF::loadView('admin.modules_data.download', $data);
        return $pdf->download($data['module_data']->title.'.pdf');

        // Create a unique zip file name
        $zipFileName = 'files_' . time() . '.zip';

        // Use a temporary directory to create the ZIP file
        $tempZipFilePath = storage_path("temp/" . $zipFileName);

        // Create a new ZipArchive instance
        $zip = new ZipArchive;

        $filesPath = array();

        // Open the zip file for creating
        if ($zip->open($tempZipFilePath, ZipArchive::CREATE) === TRUE) {
            foreach ($fileFields as $key => $fileField) {

                $filePath = public_path("images/" . $module_data->$fileField);
                //dd($filePath);
                if (file_exists($filePath) && !empty($module_data->$fileField)) {
                    $zip->addFile($filePath, basename($module_data->$fileField));
                    $filesPath[] = $filePath;

                }
            }
           // dd($zip);
            // Close the zip file
            sleep(1);
            
            $zip->close();
        }

        // Move the ZIP file to the intended location
        $finalZipFilePath = public_path("images/" . $zipFileName);
        rename($tempZipFilePath, $finalZipFilePath);

        // Set the appropriate content type
        $headers = [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $zipFileName . '"',
        ];

        // Return the response with the zip file content
        return response()->download($finalZipFilePath, null, $headers);
    }


    public function shareFiles($id, $moduleId)
    {
        $module = Modules::findOrFail($moduleId);
        $module_data = ModulesData::findOrFail($id);

        $filePaths = [];

        for ($i = 1; $i <= 25; $i++) {
            $fieldName = "extra_field_type_$i";
            $fieldType = $module->$fieldName;
            $fieldTitle = "extra_field_$i";

            if ($fieldType === 'file') {
                $filePaths[] = asset("images/" . $module_data->$fieldTitle);
            }
        }

        $phoneNumber = '03436193567'; // Get the phone number from the request

        if (!$phoneNumber) {
            return response()->json(['status' => 'error', 'message' => 'Phone number is required'], 400);
        }

        return view('admin.modules_data.share', compact('filePaths', 'phoneNumber'));
    }




}

