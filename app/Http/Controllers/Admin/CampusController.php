<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\CampusFormRequest;
use Illuminate\Support\Facades\Hash;
use Laravel\Jetstream\HasProfilePhoto;
use DataTables;
use Str;

class CampusController extends Controller
{
    use HasProfilePhoto;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('*');
            return Datatables::of($data)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('name') && !empty($request->name)) {
                                $query->where('name', 'like', "%{$request->get('name')}%");
                            }
                            if ($request->has('email') && !empty($request->email)) {
                                $query->where('email',$request->email);
                            }
                            if ($request->has('role') && !empty($request->role)) {
                                $query->where('role',$request->role);
                            }
                            if ($request->has('status') && !empty($request->status)) {
                                $query->where('status',$request->status);
                            }
                        })->addColumn('name', function ($data) {
                            return $data->name;
                        })->addColumn('email', function ($data) {
                               return $data->email; 
                        })->addColumn('role', function ($data) {
                                return role($data->role).' '.parent($data->parent_id);
                        })->addColumn('status', function ($data) {
                                $class = $data->status=='active'?'bg-success':'bg-warning';
                                return '<div class="badge '.$class.' text-white rounded-pill">'.ucfirst($data->status).'</div>';        
                        })->addColumn('action', function ($data) {
                           $edit = '';
                            $delete = '';                                $edit = '<a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('admin.users.edit',[$data->id]).'"><i class="fa-solid fa-pen-to-square"></i></a>';

                                $delete = '<a class="btn btn-datatable btn-icon btn-transparent-dark" href="'.route('admin.users.destroy',[$data->id]).'"><i class="fa-solid fa-trash"></i></a>';
                            return $edit.$delete;
                        })->rawColumns(['name', 'status', 'action', 'email'])

                        ->setRowId(function($data) {
                            return 'countryDtRow' . $data->id;
                        })->make(true);
        }
          
        return view('campus.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('campus.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->parent_id = $request->parent_id;
        $user->password = Hash::make($request->password);
        $user->save();

        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', 'You have successfully created new user');
        return redirect(route('admin.users.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findorFail($id);
        return view('campus.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findorFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->parent_id = $request->parent_id;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->update();


        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', 'You have successfully updated user informations');
        return redirect(route('admin.users.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
       User::where('id', $id)->delete();
       $request->session()->flash('message.added', 'danger');
       $request->session()->flash('message.content', 'You have successfully deleted a user');
       return redirect(route('admin.users.index'));

    }
}
