<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contacts;
use App\Models\ModulesData;
use Str;
use Auth;

class ImportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function csvToArray($filename = '', $delimiter = ',')
{
    if (!file_exists($filename) || !is_readable($filename)) {
        return false;
    }

    $header = null;
    $data = array();

    if (($handle = fopen($filename, 'r')) !== false) {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            if (!$header) {
                $header = $row;
            } else {
                // Pad the row with empty values if it is shorter than the header
                $row = array_pad($row, count($header), null);
                $data[] = array_combine($header, $row);
            }
        }
        fclose($handle);
    }

    return $data;
}



    public function store(Request $request)
    {
        $this->validate($request, [
            'csv_file' => 'required',
        ]);

        $file = $request->csv_file;

        $customerArr = $this->csvToArray($file);

        $matchingRecords = [];
        $newRecords = [];

        //dd($customerArr);
        $data = array();    
        if(null!==($customerArr)){

            foreach ($customerArr as $key => $contactData) {
                    $mappedData = [
                        'module_id' => 2,
                        'title' => $this->remove_utf8_bom_head(@$value['First Name']),
                        'extra_field_1' => $this->remove_utf8_bom_head(@$value['Last Name']),
                        'extra_field_2' => $this->remove_utf8_bom_head(@$value['Company']),
                        'extra_field_3' => $this->remove_utf8_bom_head(@$value['Title']),
                        'extra_field_4' => $this->remove_utf8_bom_head(@$value['Phone1']),
                        'extra_field_5' => $this->remove_utf8_bom_head(@$value['Phone2']),
                        'extra_field_6' => $this->remove_utf8_bom_head(@$value['Other Phones']),
                        'extra_field_7' => $this->remove_utf8_bom_head(@$value['Email1']),
                        'extra_field_8' => $this->remove_utf8_bom_head(@$value['Email1']),
                        'extra_field_9' => $this->remove_utf8_bom_head(@$value['Other Emails']),
                        'extra_field_10' => $this->remove_utf8_bom_head(@$value['Address1']),
                        'extra_field_11' => $this->remove_utf8_bom_head(@$value['City']),
                        'extra_field_12' => $this->remove_utf8_bom_head(@$value['State']),
                        'extra_field_13' => $this->remove_utf8_bom_head(@$value['Country']),
                        'extra_field_14' => $this->remove_utf8_bom_head(@$value['Pincode']),
                        'extra_field_15' => $this->remove_utf8_bom_head(@$value['Address2']),
                        'category' => findOrCreate(2, $this->remove_utf8_bom_head(@$value['Contact Type'])),
                        'extra_field_16' => $this->remove_utf8_bom_head(@$value['Budget ']),
                        'extra_field_17' => $this->remove_utf8_bom_head(@$value['Location ']),
                        'extra_field_18' => findOrCreate(5, $this->remove_utf8_bom_head(@$value['Property Status'])),
                        'extra_field_19' => findOrCreate(4, $this->remove_utf8_bom_head(@$value['No. of Beds '])),
                        'extra_field_20' => $this->remove_utf8_bom_head(@$value['Notes ']),
                    ];

                    
                    $existingRecord = ModulesData::where('title', $contactData['First Name'])->where('extra_field_1', $contactData['Last Name'])->where('extra_field_4', $contactData['Phone1'])->first();
                    if($request->recordAction == 'override'){
                        //$existingRecord->delete();
                        $existingRecord->update($mappedData);
                    }elseif($request->recordAction == 'ignore'){
                        if($existingRecord){
                            continue;
                        }
                        ModulesData::create($mappedData);
                    }elseif ($existingRecord) {
                        // If matching record found, add to matchingRecords array
                        $matchingRecords[] = $contactData;
                    } else {
                        // If no match, add to newRecords array
                        
                        ModulesData::create($mappedData);
                    }
                    

                    //dd($mappedData);

                // Insert data into the contacts table
                //Contacts::create($mappedData);
            }

        }

        if (!empty($matchingRecords)) {
            return response()->json(['matchingRecords' => $matchingRecords]);
        }

        return true;

        

        /*if(null!==($data)){
            //dd($data);
            ModulesData::insert($data);
        }*/
        //dd($data);
        //flash('Jobs has been imported!')->success();
        //return redirect()->back();
        
        
    }

    public function remove_utf8_bom_head($text) {
        if(substr(bin2hex($text), 0, 6) === 'efbbbf') {
            $text = substr($text, 3);
        }
        return $text;
    }


}
