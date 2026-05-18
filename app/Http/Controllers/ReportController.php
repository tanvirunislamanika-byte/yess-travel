<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModulesData;
use App\Models\AccidentReport;
use PDF;

class ReportController extends Controller
{

    public function print($id){
    	$data = array();

        $data['report'] = AccidentReport::where('id',$id)->first();
        

        $pdf = PDF::loadView('worksheet', $data);
        return $pdf->download($data['report']->first_name.time().'.pdf'); 		
    }
}
