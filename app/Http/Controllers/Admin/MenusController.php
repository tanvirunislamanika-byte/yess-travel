<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Menu_types;
use App\Models\MenuTranslation;
use App\Models\Language;
use Str;

class MenusController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('admin.auth:admin');
    }

    /**
     * Show the Admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.menus.index');
    }

    public function add() {
        $data = array();
        $data['menu_types'] = Menu_types::select('title', 'id')->where('status','active')->pluck('title', 'id')->toArray();
        return view('admin.menus.add')->with($data);
    }

    public function edit($id) {
        $data = array();

        $data['menu_types'] = Menu_types::select('title', 'id')->where('status','active')->pluck('title', 'id')->toArray();

        $data['menu'] = Menu::findorFail($id);
        return view('admin.menus.edit')->with($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'slug' => 'required',
            'menu_type_id' => 'required',
        ], [
            'title.required' => 'Menu Title is required.',
            'slug.required' => 'Menu Link is required.',
            'menu_type_id.required' => 'Please Select Menu Type.',
        ]);
        $menu = new Menu();
        $menu->title = $request->title;
        $menu->slug = $request->slug;
        $menu->menu_type_id = $request->menu_type_id;
        $menu->menu_is = 'external';
        $menu->save();
        if ($menu->save() == true) {
            $request->session()->flash('message.added', 'success');
            $request->session()->flash('message.content', 'Menu has been successfully Created!');
        }
        return redirect(route('admin.menus'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'slug' => 'required',
            'menu_type_id' => 'required',
        ], [
            'title.required' => 'Menu Title is required.',
            'slug.required' => 'Menu Link is required.',
            'menu_type_id.required' => 'Please Select Menu Type.',
        ]);
        
        $menu = Menu::findorFail($request->id);
        $menu->title = $request->title;
        $menu->slug = $request->slug;
        $menu->menu_type_id = $request->menu_type_id;
        $menu->update();
        if ($menu->update() == true) {
            $request->session()->flash('message.added', 'success');
            $request->session()->flash('message.content', 'A Menu has been successfully Updated!');
        }
        return redirect(route('admin.menus'));
    }
    public function post_index(Request $request)
    {         
        $source = $request->source;
        
            if($request->destination!=''){
              $destination  =$request->destination;  
            }else{
                $destination=0;
            }
        $item             = Menu::find($source);
        $item->parent_id  = $destination;  
        $item->save();

        $ordering       = json_decode($request->order);
        $rootOrdering   = json_decode($request->rootOrder );
        
        if($ordering){
          foreach($ordering as $order=>$item_id){

            if($itemToOrder = Menu::find($item_id)){
                $itemToOrder->order = $order;
                $itemToOrder->save();
            }
          }
        } else {
          foreach($rootOrdering as $order=>$item_id){
            if($itemToOrder = Menu::find($item_id)){
                $itemToOrder->order = $order;
                $itemToOrder->save();
            }
          }
        }

        return 'ok ';
    }
    public function destroy(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', 'A Menu has been successfully Deleted!');
        return redirect(route('admin.menus'));
    }

    /**
     * Show menu translations management page
     */
    public function translations()
    {
        $menus = Menu::with('translations')->get();
        $languages = Language::where('is_active', 1)->get();
        
        return view('admin.menus.translations', compact('menus', 'languages'));
    }

    /**
     * Store menu translation
     */
    public function storeTranslation(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'locale' => 'required|string|max:5',
            'title' => 'required|string|max:255'
        ]);

        MenuTranslation::updateOrInsert(
            ['menu_id' => $request->menu_id, 'locale' => $request->locale],
            ['title' => $request->title, 'updated_at' => now()]
        );

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', 'Menu translation has been saved successfully!');
        
        return redirect()->back();
    }

    /**
     * Update menu translation
     */
    public function updateTranslation(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:menu_translations,id',
            'title' => 'required|string|max:255'
        ]);

        $translation = MenuTranslation::findOrFail($request->id);
        $translation->update(['title' => $request->title]);

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', 'Menu translation has been updated successfully!');
        
        return redirect()->back();
    }

    /**
     * Delete menu translation
     */
    public function deleteTranslation(Request $request, $id)
    {
        $translation = MenuTranslation::findOrFail($id);
        $translation->delete();

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', 'Menu translation has been deleted successfully!');
        
        return redirect()->back();
    }
}
