<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modules;
use App\Models\Menu_types;
use App\Models\ModulesData;
use App\Models\Tags;
use App\Models\Menu;
use Illuminate\Support\Str;
use DataTables;

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

        return view('admin.modules_data.index')->with($data);
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

        return view('admin.modules_data.edit')->with($data);
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
            'title', 'description', 'category', 'sub_category', 'module_id', 'meta_title', 'meta_keywords', 'meta_description', 'image', 'images',
        ]));

        $data->slug = $slugs;
        $data->category_ids = $request->category_ids ? implode(",", $request->category_ids) : null;

        $this->setExtraFields($data, $request);

        $data->tag_ids = $request->tag_ids ? implode(",", $request->tag_ids) : null;

        $this->saveDynamicForm($data, $dynamic_form);

        $data->save();

        $this->saveMenuTypes($request, $data);

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', $request->module_term . ' has been successfully Created!');

        return redirect(route('admin.modules.data', $request->module_slug));
    }

    public function update(Request $request)
    {
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

        $this->saveDynamicForm($data, $request->dynamic_form);

        $data->update();

        $this->saveMenuTypes($request, $data);

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', $request->module_term . ' has been successfully Updated!');

        return redirect(route('admin.modules.data', $request->module_slug));
    }

    private function setExtraFields($data, $request)
    {
        for ($i = 1; $i <= 25; $i++) {
            $extra_field = 'extra_field_' . $i;
            $data->$extra_field = $request->$extra_field;
        }
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
        $modulesDataQuery = ModulesData::where('module_id', $module->id);

        $datacolumns = DataTables::of($modulesDataQuery)
            ->filter(function ($query) use ($request, $module) {
                $this->applyFilters($query, $request, $module);
            })
            ->addColumn('image', function ($modulesData) {
                return '<div class="image-container"><img src="' . asset('/images/thumb/' . $modulesData->image) . '" alt=""></div>';
            })
            ->addColumn('title', function ($modulesData) {
                return '<span>' . Str::limit(strip_tags($modulesData->title), 40, '...') . '</span>';
            })
            ->addColumn('created_date', function ($modulesData) {
                return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $modulesData->created_at);
            })
            ->addColumn('category', function ($modulesData) use ($module) {
                return $this->getCategoryLinks($modulesData, $module);
            })
            ->addColumn('status', function ($modulesData) {
                return $this->getStatusBadge($modulesData);
            })
            ->addColumn('action', function ($modulesData) use ($module) {
                return $this->getActionButtons($module, $modulesData);
            })
            ->rawColumns(['title', 'status', 'action', 'image', 'category'])
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

    private function getStatusBadge($modulesData)
    {
        $statusType = ($modulesData->status == 'active') ? 'success' : 'danger';
        $statusIcon = ($modulesData->status == 'active') ? 'check-circle' : 'times-circle';
        $statusText = ucfirst($modulesData->status);

        $status = '<span class="' . $statusType . '"><i class="fas fa-' . $statusIcon . '"></i>&nbsp;<span style="font-size: 12px;" class="status-text">' . $statusText . '</span></span>';

        return '<a class="waves-effect status waves-light" onclick="update_status(' . $modulesData->id . ');" href="javascript:void(0);" id="sts_' . $modulesData->id . '"> ' . $status . '</a>';
    }

    private function getActionButtons($module, $modulesData)
    {
        $preview = ($module->is_preview)
            ? '<a target="_blank" href="' . url('/admin/' . strtolower($module->term) . '/' . $modulesData->slug) . '" class="btn btn-success"><i class="icofont icofont-eye-alt"></i>&nbsp;Preview</a>'
            : '';

        $edit = '<a class="" href="' . route('admin.modules.data.edit', [$module->slug, $modulesData->id]) . '"><i class="fa-solid fa-pen-to-square"></i></a>&nbsp&nbsp&nbsp';

        $delete = '<a class="" id="delete" href="' . route('admin.modules.data.delete', [$module->slug, $modulesData->id]) . '"><i class="fa-solid fa-trash"></i></a>';

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


}

