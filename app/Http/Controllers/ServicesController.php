<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModulesData;
use App\Models\Tags;

class ServicesController extends Controller
{
    public function index(Request $request){
        $data = ModulesData::where('module_id', 28)->where('status','active');

        if ($request->has('keyword')) {
            $data->where('title', 'like', '%'.$request->keyword.'%');
        }

        if ($request->has('category')) {
            $data->where('category', $request->category);
        }

        if ($request->has('archive')) {
            $data->whereMonth('created_at', date('m', strtotime($request->archive)))->whereYear('created_at', date('Y', strtotime($request->archive)));
        }

        if ($request->has('tag')) {
            $data->whereRaw("FIND_IN_SET($request->tag,tag_ids)");
        }

        if ($request->has('cate')) {
            $data->whereRaw("FIND_IN_SET($request->cate,category_ids)");
        }

        $arr['services'] = $data->paginate(10);

        $arr['recent_data'] = ModulesData::where('module_id', 28)->where('status','active')->orderBy('id','desc')->take(3)->get(); 
        $arr['archives'] = $this->lastThreeMonths();
        return view('services.index')->with($arr);
    }   

    public function services($slug='')
    {
        $service = ModulesData::where('slug',$slug)->first();
        $arr['services'] = ModulesData::where('module_id', 28)->where('status','active')->where('category',$service->id)->orderBy('id','desc')->get();
        return view('services.services')->with($arr);
    }

    public function detail($slug){
    	$data['service'] = ModulesData::where('slug',trim($slug))
    	 		->where('module_id', 28)
    	 		->where('status','active')
    	 		->first();

    	$data['recent_data'] = ModulesData::where('module_id', 28)->where('id','!=',$data['service']->id)->where('status','active')->orderBy('id','desc')->take(3)->get(); 
    	
		$data['archives'] = $this->lastThreeMonths();
    	 
    	return view('services.detail')->with($data); 		
    }

    function lastThreeMonths() {
	    return array(
	        date('F Y', time()),
	        date('F Y', strtotime('-1 month')),
	        date('F Y', strtotime('-2 month'))
	    );
	}
}
