<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AccidentReport;
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

    public function delete($id)
    {
        $this->id = $id;
        $report = AccidentReport::where('id',$this->id)->delete();
        $this->mode = 'listing';
        session()->flash('success', 'Accident report deleted successfully.');
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
    public function render()
    {
        if($this->mode == 'listing'){
            $data = AccidentReport::select('*');



            if ($this->name) {

                $data->where('first_name', $this->name)->orWhere('last_name',$this->name);

            }

            if ($this->member) {

                $data->where('user_id', $this->member);

            }
            $reports = $data->get();
            return view('livewire.admin.reports',compact('reports'));
        }elseif($this->mode == 'update'){
            $report = AccidentReport::where('id',$this->id)->first();
            return view('livewire.admin.add_edit_report',compact('report'));
        }elseif($this->mode == 'view'){
            $report = AccidentReport::where('id',$this->id)->first();
            $this->state = $report->toArray();
            return view('livewire.admin.view',compact('report'));
        }else{
            return view('livewire.admin.add_edit_report');
        }
        
    }
}


