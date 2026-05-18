<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AccidentReport;
use App\Models\Team_user;
class Report extends Component
{
    public $state = [
        'date' => '',
        'time' => '',
        'injury_location' => '',
        'trail' => '',
        'experience_on_trail' => '',
        'ski_lift' => '',
        'experience_on_lift' => '',
        'tubing' => '',
        'equipment' => '',
        'removed_by_patrol' => '',
        'rental_skis_snowboard' => '',
        'experience_on_skis_snowboard' => '',
        'wearing_helmet' => '',
        'removed_at_accident' => '',
        'first_name' => '',
        'last_name' => '',
        'street_address' => '',
        'city' => '',
        'state' => '',
        'dob' => '',
        'sex' => '',
        'weight' => '',
        'height' => '',
        'email_address' => '',
        'phone_number' => '',
        'work_lessons_at_bousquet' => '',
        'contacts_eyeglasses' => '',
        'witness_first_name' => '',
        'witness_last_name' => '',
        'witness_street_address' => '',
        'witness_city' => '',
        'witness_state' => '',
        'witness_phone_number' => '',
        'involved_with_accident' => '',
        'transport_in_method' => '',
        'transport_in_method_patrollers' => '',
        'highest_care_on_hill' => '',
        'care_rendered' => '',
        'highest_care_in_aid_room' => '',
        'highest_care_in_aid_room_care_rendered' => '',
        'highest_care_in_aid_room_patrollers' => '',
        'transport_out_method' => '',
        'plan_to_ski_again_today' => '',
    ];

    public $mode = 'listing';
    public $id = NULL;
    public $name = null;
    public $status = null;
    public $member = null;

    protected $rules = [
        'state.date' => 'required|date',
        'state.time' => 'required|date_format:H:i',
        'state.injury_location' => 'required',
        'state.trail' => 'required',
        'state.experience_on_trail' => 'required',
        'state.ski_lift' => 'required',
        'state.experience_on_lift' => 'required',
        'state.tubing' => 'required',
        'state.equipment' => 'required_if:state.tubing,21',
        'state.removed_by_patrol' => 'required_if:state.tubing,21',
        'state.rental_skis_snowboard' => 'required',
        'state.experience_on_skis_snowboard' => 'required',
        'state.wearing_helmet' => 'required',
        'state.removed_at_accident' => 'required_if:state.wearing_helmet,38',
        'state.first_name' => 'required',
        'state.last_name' => 'required',
        'state.street_address' => 'required',
        'state.city' => 'required',
        'state.state' => 'required',
        'state.dob' => 'required|date',
        'state.sex' => 'required',
        'state.weight' => 'required|numeric',
        'state.height' => 'required|numeric',
        'state.email_address' => 'required|email',
        'state.phone_number' => 'required|numeric',
        'state.work_lessons_at_bousquet' => 'required',
        'state.contacts_eyeglasses' => 'required',
        'state.witness_first_name' => 'required_if:state.contacts_eyeglasses,96',
        'state.witness_last_name' => 'required_if:state.contacts_eyeglasses,96',
        'state.witness_street_address' => 'required_if:state.contacts_eyeglasses,96',
        'state.witness_city' => 'required_if:state.contacts_eyeglasses,96',
        'state.witness_state' => 'required_if:state.contacts_eyeglasses,96',
        'state.witness_phone_number' => 'required_if:state.contacts_eyeglasses,96',
        'state.involved_with_accident' => 'required_if:state.contacts_eyeglasses,96',
        'state.transport_in_method' => 'required',
        'state.transport_in_method_patrollers' => 'required_if:state.transport_in_method,102',
        'state.highest_care_on_hill' => 'required',
        'state.care_rendered' => 'required_if:state.highest_care_on_hill,106',
        'state.highest_care_in_aid_room' => 'required',
        'state.highest_care_in_aid_room_care_rendered' => 'required_if:state.highest_care_in_aid_room,112',
        'state.highest_care_in_aid_room_patrollers' => 'required',
        'state.transport_out_method' => 'required',
        'state.plan_to_ski_again_today' => 'required',
    ];

    public function insert()
    {
        $this->id = NULL;
        $this->state = [
        'date' => '',
        'time' => '',
        'injury_location' => '',
        'trail' => '',
        'experience_on_trail' => '',
        'ski_lift' => '',
        'experience_on_lift' => '',
        'tubing' => '',
        'equipment' => '',
        'removed_by_patrol' => '',
        'rental_skis_snowboard' => '',
        'experience_on_skis_snowboard' => '',
        'wearing_helmet' => '',
        'removed_at_accident' => '',
        'first_name' => '',
        'last_name' => '',
        'street_address' => '',
        'city' => '',
        'state' => '',
        'dob' => '',
        'sex' => '',
        'weight' => '',
        'height' => '',
        'email_address' => '',
        'phone_number' => '',
        'work_lessons_at_bousquet' => '',
        'contacts_eyeglasses' => '',
        'witness_first_name' => '',
        'witness_last_name' => '',
        'witness_street_address' => '',
        'witness_city' => '',
        'witness_state' => '',
        'witness_phone_number' => '',
        'involved_with_accident' => '',
        'transport_in_method' => '',
        'transport_in_method_patrollers' => '',
        'highest_care_on_hill' => '',
        'care_rendered' => '',
        'highest_care_in_aid_room' => '',
        'highest_care_in_aid_room_care_rendered' => '',
        'highest_care_in_aid_room_patrollers' => '',
        'transport_out_method' => '',
        'plan_to_ski_again_today' => '',
    ];
        $this->mode = 'insert';
    }
    public function reports()
    {
        $this->mode = 'listing';
    }

    public function edit($id)
    {
        $this->id = $id;
        $report = AccidentReport::where('id',$this->id)->first();
        $this->state = $report->toArray();
        $this->mode = 'update';
    }

    public function view($id)
    {
        $this->id = $id;
        $this->mode = 'view';
    }
    public function submitForm()
    {
        //dd($this->state);
        //$this->validate();
        $userId = auth()->user()->id;

        

        // Check if a record already exists for the current user
        $existingReport = AccidentReport::where('id', $this->id)->first();

        // If a record already exists, update it
        if ($existingReport) {
            $existingReport->update($this->state);
            session()->flash('success', 'Accident report updated successfully.');
        } else {
            // If no record exists, create a new one
            $existingReport = AccidentReport::create(array_merge(['user_id' => $userId], $this->state));
            session()->flash('success', 'Accident report submitted successfully.');
        }

        $existingReport = AccidentReport::where('id', $existingReport->id)->first();
        $existingReport->status = 'completed';
        $existingReport->update();

        $this->mode = 'listing';

        //$this->reset('state');

        // You can add a success message or redirect as needed
        //session()->flash('message', 'Form submitted successfully!');
    }
    public function autosave()
    {
        //dd($this->state);
        //$this->validate();
        $userId = auth()->user()->id;

        

        // Check if a record already exists for the current user
        $existingReport = AccidentReport::where('id', $this->id)->first();

        // If a record already exists, update it
        if ($existingReport) {
            $existingReport->update($this->state);
            session()->flash('success', 'Accident report updated successfully.');
        } else {
            // If no record exists, create a new one
            $existingReport = AccidentReport::create(array_merge(['user_id' => $userId], $this->state));
            session()->flash('success', 'Accident report submitted successfully.');
        }

        $this->id = $existingReport->id;
        $report = AccidentReport::where('id',$this->id)->first();
        $this->state = $report->toArray();
        $this->mode = 'update';

        //$this->reset('state');

        // You can add a success message or redirect as needed
        //session()->flash('message', 'Form submitted successfully!');
    }

    public function updated($field)
    {
        if (in_array($field, ['name', 'member', 'status'])) {}else{
            $userId = auth()->user()->id;

        

        // Check if a record already exists for the current user
        $existingReport = AccidentReport::where('id', $this->id)->first();

        // If a record already exists, update it
        if ($existingReport) {
                $existingReport->user_id = $userId;
                $existingReport->date = $this->state['date']?$this->state['date']:null;
                $existingReport->time = $this->state['time']?$this->state['time']:null;
                $existingReport->injury_location = $this->state['injury_location']?$this->state['injury_location']:null;
                $existingReport->trail = $this->state['trail']?$this->state['trail']:null;
                $existingReport->experience_on_trail = $this->state['experience_on_trail']?$this->state['experience_on_trail']:null;
                $existingReport->ski_lift = $this->state['ski_lift']?$this->state['ski_lift']:null;
                $existingReport->experience_on_lift = $this->state['experience_on_lift']?$this->state['experience_on_lift']:null;
                $existingReport->tubing = $this->state['tubing']?$this->state['tubing']:null;
                $existingReport->equipment = $this->state['equipment']?$this->state['equipment']:null;
                $existingReport->rental_skis_snowboard = $this->state['rental_skis_snowboard']?$this->state['rental_skis_snowboard']:null;
                $existingReport->removed_by_patrol = $this->state['removed_by_patrol']?$this->state['removed_by_patrol']:null;
                $existingReport->experience_on_skis_snowboard = $this->state['experience_on_skis_snowboard']?$this->state['experience_on_skis_snowboard']:null;
                $existingReport->wearing_helmet = $this->state['wearing_helmet']?$this->state['wearing_helmet']:null;
                $existingReport->removed_at_accident = $this->state['removed_at_accident']?$this->state['removed_at_accident']:null;
                $existingReport->first_name = $this->state['first_name']?$this->state['first_name']:null;
                $existingReport->last_name = $this->state['last_name']?$this->state['last_name']:null;
                $existingReport->street_address = $this->state['street_address']?$this->state['street_address']:null;
                $existingReport->city = $this->state['city']?$this->state['city']:null;
                $existingReport->state = $this->state['state']?$this->state['state']:null;
                $existingReport->dob = $this->state['dob']?$this->state['dob']:null;
                $existingReport->sex = $this->state['sex']?$this->state['sex']:null;
                $existingReport->weight = $this->state['weight']?$this->state['weight']:null;
                $existingReport->height = $this->state['height']?$this->state['height']:null;
                $existingReport->email_address = $this->state['email_address']?$this->state['email_address']:null;
                $existingReport->phone_number = $this->state['phone_number']?$this->state['phone_number']:null;
                $existingReport->work_lessons_at_bousquet = $this->state['work_lessons_at_bousquet']?$this->state['work_lessons_at_bousquet']:null;
                $existingReport->contacts_eyeglasses = $this->state['contacts_eyeglasses']?$this->state['contacts_eyeglasses']:null;
                $existingReport->witness_first_name = $this->state['witness_first_name']?$this->state['witness_first_name']:null;
                $existingReport->witness_last_name = $this->state['witness_last_name']?$this->state['witness_last_name']:null;
                $existingReport->witness_street_address = $this->state['witness_street_address']?$this->state['witness_street_address']:null;
                $existingReport->witness_city = $this->state['witness_city']?$this->state['witness_city']:null;
                $existingReport->witness_state = $this->state['witness_state']?$this->state['witness_state']:null;
                $existingReport->witness_phone_number = $this->state['witness_phone_number']?$this->state['witness_phone_number']:null;
                $existingReport->involved_with_accident = $this->state['involved_with_accident']?$this->state['involved_with_accident']:null;
                $existingReport->transport_in_method = $this->state['transport_in_method']?$this->state['transport_in_method']:null;
                $existingReport->transport_in_method_patrollers = $this->state['transport_in_method_patrollers']?$this->state['transport_in_method_patrollers']:null;
                $existingReport->highest_care_on_hill = $this->state['highest_care_on_hill']?$this->state['highest_care_on_hill']:null;
                $existingReport->care_rendered = $this->state['care_rendered']?$this->state['care_rendered']:null;
                $existingReport->highest_care_in_aid_room = $this->state['highest_care_in_aid_room']?$this->state['highest_care_in_aid_room']:null;
                $existingReport->highest_care_in_aid_room_care_rendered = $this->state['highest_care_in_aid_room_care_rendered']?$this->state['highest_care_in_aid_room_care_rendered']:null;
                $existingReport->highest_care_in_aid_room_patrollers = $this->state['highest_care_in_aid_room_patrollers']?$this->state['highest_care_in_aid_room_patrollers']:null;
                $existingReport->transport_out_method = $this->state['transport_out_method']?$this->state['transport_out_method']:null;
                $existingReport->plan_to_ski_again_today = $this->state['plan_to_ski_again_today']?$this->state['plan_to_ski_again_today']:null;
                $existingReport->status = 'draft';
                $existingReport->update();
            $this->id = $existingReport->id;
            $this->state = $existingReport->toArray();
            $this->mode = 'update';
        } else {
            // If no record exists, create a new one

            //dd($this->state);
            
                $existingReport = new AccidentReport();
                $existingReport->user_id = $userId;
                $existingReport->date = $this->state['date']?$this->state['date']:null;
                $existingReport->time = $this->state['time']?$this->state['time']:null;
                $existingReport->injury_location = $this->state['injury_location']?$this->state['injury_location']:null;
                $existingReport->trail = $this->state['trail']?$this->state['trail']:null;
                $existingReport->experience_on_trail = $this->state['experience_on_trail']?$this->state['experience_on_trail']:null;
                $existingReport->ski_lift = $this->state['ski_lift']?$this->state['ski_lift']:null;
                $existingReport->experience_on_lift = $this->state['experience_on_lift']?$this->state['experience_on_lift']:null;
                $existingReport->tubing = $this->state['tubing']?$this->state['tubing']:null;
                $existingReport->equipment = $this->state['equipment']?$this->state['equipment']:null;
                $existingReport->rental_skis_snowboard = $this->state['rental_skis_snowboard']?$this->state['rental_skis_snowboard']:null;
                $existingReport->removed_by_patrol = $this->state['removed_by_patrol']?$this->state['removed_by_patrol']:null;
                $existingReport->experience_on_skis_snowboard = $this->state['experience_on_skis_snowboard']?$this->state['experience_on_skis_snowboard']:null;
                $existingReport->wearing_helmet = $this->state['wearing_helmet']?$this->state['wearing_helmet']:null;
                $existingReport->removed_at_accident = $this->state['removed_at_accident']?$this->state['removed_at_accident']:null;
                $existingReport->first_name = $this->state['first_name']?$this->state['first_name']:null;
                $existingReport->last_name = $this->state['last_name']?$this->state['last_name']:null;
                $existingReport->street_address = $this->state['street_address']?$this->state['street_address']:null;
                $existingReport->city = $this->state['city']?$this->state['city']:null;
                $existingReport->state = $this->state['state']?$this->state['state']:null;
                $existingReport->dob = $this->state['dob']?$this->state['dob']:null;
                $existingReport->sex = $this->state['sex']?$this->state['sex']:null;
                $existingReport->weight = $this->state['weight']?$this->state['weight']:null;
                $existingReport->height = $this->state['height']?$this->state['height']:null;
                $existingReport->email_address = $this->state['email_address']?$this->state['email_address']:null;
                $existingReport->phone_number = $this->state['phone_number']?$this->state['phone_number']:null;
                $existingReport->work_lessons_at_bousquet = $this->state['work_lessons_at_bousquet']?$this->state['work_lessons_at_bousquet']:null;
                $existingReport->contacts_eyeglasses = $this->state['contacts_eyeglasses']?$this->state['contacts_eyeglasses']:null;
                $existingReport->witness_first_name = $this->state['witness_first_name']?$this->state['witness_first_name']:null;
                $existingReport->witness_last_name = $this->state['witness_last_name']?$this->state['witness_last_name']:null;
                $existingReport->witness_street_address = $this->state['witness_street_address']?$this->state['witness_street_address']:null;
                $existingReport->witness_city = $this->state['witness_city']?$this->state['witness_city']:null;
                $existingReport->witness_state = $this->state['witness_state']?$this->state['witness_state']:null;
                $existingReport->witness_phone_number = $this->state['witness_phone_number']?$this->state['witness_phone_number']:null;
                $existingReport->involved_with_accident = $this->state['involved_with_accident']?$this->state['involved_with_accident']:null;
                $existingReport->transport_in_method = $this->state['transport_in_method']?$this->state['transport_in_method']:null;
                $existingReport->transport_in_method_patrollers = $this->state['transport_in_method_patrollers']?$this->state['transport_in_method_patrollers']:null;
                $existingReport->highest_care_on_hill = $this->state['highest_care_on_hill']?$this->state['highest_care_on_hill']:null;
                $existingReport->care_rendered = $this->state['care_rendered']?$this->state['care_rendered']:null;
                $existingReport->highest_care_in_aid_room = $this->state['highest_care_in_aid_room']?$this->state['highest_care_in_aid_room']:null;
                $existingReport->highest_care_in_aid_room_care_rendered = $this->state['highest_care_in_aid_room_care_rendered']?$this->state['highest_care_in_aid_room_care_rendered']:null;
                $existingReport->highest_care_in_aid_room_patrollers = $this->state['highest_care_in_aid_room_patrollers']?$this->state['highest_care_in_aid_room_patrollers']:null;
                $existingReport->transport_out_method = $this->state['transport_out_method']?$this->state['transport_out_method']:null;
                $existingReport->plan_to_ski_again_today = $this->state['plan_to_ski_again_today']?$this->state['plan_to_ski_again_today']:null;
                $existingReport->status = 'draft';
                $existingReport->save();
                $this->id = $existingReport->id;
                $this->state = $existingReport->toArray();
                $this->mode = 'update';
           
        }
        }
        

        
    }
    public function render()
    {
        
        $userId = auth()->user()->id;
        if($this->mode == 'listing'){
            if(\Laravel\Jetstream\Jetstream::findRole(auth()->user()->membership->role)->name=='Administrator'){
                $team = Team_user::where('user_id',auth()->user()->id)->first();
                $userids = Team_user::where('team_id',$team->team_id)->pluck('user_id')->toArray();
                $data = AccidentReport::select('*')->whereIn('user_id',$userids);
            }else{
                $data = AccidentReport::select('*')->where('user_id',$userId);
            }
            



            if ($this->name) {

                $data->where('first_name', $this->name)->orWhere('last_name',$this->name);

            }
            if ($this->member) {

                $data->where('user_id', $this->member);

            }
            if ($this->status) {

                $data->where('status', $this->status);

            }
            $reports = $data->get();
            return view('livewire.reports',compact('reports'));
        }elseif($this->mode == 'update'){
            $report = AccidentReport::where('id',$this->id)->first();
            return view('livewire.add_edit_report',compact('report'));
        }elseif($this->mode == 'view'){
            $report = AccidentReport::where('id',$this->id)->first();
            $this->state = $report->toArray();
            return view('livewire.view',compact('report'));
        }else{
            return view('livewire.add_edit_report');
        }
        
    }
}


