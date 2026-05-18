<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
                            if ($request->has('status') && !empty($request->status)) {
                                $query->where('status',$request->status);
                            }
                        })->addColumn('name', function ($data) {
                            return $data->name;
                        })->addColumn('email', function ($data) {
                               return $data->email; 
                        })->addColumn('status', function ($data) {
                                $class = $data->status=='active'?'bg-success':'bg-warning';
                                return '<div class="badge '.$class.' text-white rounded-pill">'.ucfirst($data->status).'</div>';
                        })->addColumn('action', function ($data) {
                           $edit = '';
                            $delete = '';                                $edit = '<a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('campuses.edit',[$data->id]).'"><i class="fa-solid fa-pen-to-square"></i></a>';

                                $delete = '<a class="btn btn-datatable btn-icon btn-transparent-dark" href="'.route('campuses.destroy',[$data->id]).'"><i class="fa-solid fa-trash"></i></a>';
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
        $programs = Program::where('status','active')->pluck('name','id')->toArray();
        $courses = Course::where('status','active')->pluck('name','id')->toArray();
        return view('campus.create',compact('programs','courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CampusFormRequest $request)
    {

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = 2;
        $user->password = Hash::make($request->password);
        if (isset($request->image)) {
            $user->updateProfilePhoto($request->image);
        }
        $user->save();

        $slug = Str::slug($request->name,'-');
        $slugs = unique_slug($slug, 'campuses', $field = 'slug', $key = NULL, $value = NULL);

        $campus = new Campus();
        $campus->user_id = $user->id;
        $campus->name = $request->name;
        $campus->slug = $slugs;
        $campus->email = $request->email;
        $campus->mobile = $request->mobile;
        $campus->location = $request->location;
        $campus->city = $request->city;
        $campus->status = $request->status;
        $campus->save();

        if(null!==($request->programs)){
            foreach ($request->programs as $key => $value) {
                    $prog = new CampusProgramIds();
                    $prog->campus_id = $campus->id;
                    $prog->program_id = $value;
                    $prog->save();
            }
            
        }


        if(null!==($request->courses)){
            foreach ($request->courses as $key => $value) {
                    $cour = new CampusCourseIds();
                    $cour->campus_id = $campus->id;
                    $cour->course_id = $value;
                    $cour->save();
            } 
        }



        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', 'You have successfully created new campus');
        return redirect(route('campuses.index'));
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
    public function update(CampusFormRequest $request, $id)
    {
        $user = User::findorFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = 2;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        if (isset($request->image)) {
            $user->updateProfilePhoto($request->image);
        }
        $user->update();

        $slug = Str::slug($request->name,'-');
        $slugs = unique_slug($slug, 'campuses', $field = 'slug', $key = NULL, $value = NULL);

        $campus = Campus::where('user_id',$id)->first();
        $campus->user_id = $user->id;
        $campus->name = $request->name;
        $campus->slug = $slugs;
        $campus->email = $request->email;
        $campus->mobile = $request->mobile;
        $campus->location = $request->location;
        $campus->city = $request->city;
        $campus->status = $request->status;
        $campus->update();


        CampusProgramIds::where('campus_id',$campus->id)->delete();
        if(null!==($programs = $request->programs)){
            foreach ($programs as $key => $program) {
                $prog = new CampusProgramIds();
                $prog->campus_id = $campus->id;
                $prog->program_id = $program;
                $prog->save();
            }
        }

        CampusCourseIds::where('campus_id',$campus->id)->delete();
        if(null!==($courses = $request->courses)){
            foreach ($courses as $key => $course) {
                $cour = new CampusCourseIds();
                $cour->campus_id = $campus->id;
                $cour->course_id = $course;
                $cour->save();
            }
        }


        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', 'You have successfully updated campus informations');
        return redirect(route('campuses.index'));
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
       Campus::where('user_id',$id)->delete();
       $request->session()->flash('message.added', 'danger');
       $request->session()->flash('message.content', 'You have successfully deleted a campus');
       return redirect(route('campuses.index'));

    }
}
