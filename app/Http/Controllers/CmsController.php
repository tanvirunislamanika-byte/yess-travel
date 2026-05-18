<?php



namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\ModulesData;

use App\Models\Country;

use App\Models\State;

use App\Models\City;

use Str;

use Form;



class CmsController extends Controller

{

    public function index($slug){

        $cms = ModulesData::where('slug',$slug)->where('status','active')->first();
        if (null === $cms) {
           return abort(404);
        }else{
            return view('pages.cms')->with('cms',$cms);
        }
        



    }



    public function import(Request $request){

        $id = $request->id;

        $ids = explode('-', $id);

        $arr = array();

        for ($i=$ids[0]; $i <= $ids[1]; $i++) { 

        	$arr[] = $i;

        }

        

    	$countries = Country::where('lang','en')->whereIn('id',$arr)->get();

    	//dd($countries);

    	foreach ($countries as $key => $value) {

    		$data = new ModulesData();

        	$data->module_id = 11;

        	$data->title = $value->country;

        	$data->extra_field_1 = $value->nationality;

        	$data->extra_field_2 = $value->flag;

        	$data->extra_field_3 = $value->flag2;

        	$data->status = 'active';

        	$data->save();



        	$states = State::where('country_id',$value->country_id)->get();

        	if(null!==($states)){

        		foreach ($states as $key => $val) {

	        		$state = new ModulesData();

		        	$state->module_id = 12;

		        	$state->title = $val->state;

		        	$state->category = $data->id;

		        	$state->status = 'active';

		        	$state->save();



		        	$cities = City::where('state_id',$val->state_id)->get();

		        	if(null!==($cities)){

		        		foreach ($cities as $key => $va) {

			        		$city = new ModulesData();

			        		$city->module_id = 13;

			        		$city->title = $va->city;

			        		$city->category = $state->id;

			        		$city->status = 'active';

			        		$city->save();

			        	}

		        	}

		        	

	        	}

        	}

        	

    	}

    	

    }



    public function filterStates(Request $request)

    {

        $country_id = $request->input('country_id');

        $state = $request->input('state');

        $states = ModulesData::select('title', 'id')->where('category',$country_id)->where('status','active')->pluck('title', 'id')->toArray();

        $dd = Form::select('state', ['' => __('Select State')] + $states, $state, array('class' => 'form-control', 'id' => 'state'));

        echo $dd;

    }



    public function filterCities(Request $request)

    {

        $state_id = $request->input('state_id');

        $city = $request->input('city');

        $cities = ModulesData::select('title', 'id')->where('category',$state_id)->where('status','active')->pluck('title', 'id')->toArray();



        $dd = Form::select('city', ['' => 'Select City'] + $cities, $city, array('id' => 'city', 'class' => 'form-control'));

        echo $dd;

    } 



    public function features(){

        $data['cms'] = ModulesData::where('slug','faqs')->where('status','active')->first();
        $data['features'] = ModulesData::where('module_id',15)->where('status','active')->get();
        return view('pages.features')->with($data);



    }

}

