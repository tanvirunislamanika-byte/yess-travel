<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\WithFileUploads;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    public $state = [];
    public $photo;
    public $countries = [];
    public $states = [];
    public $cities = [];

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $user = auth()->user();
        $this->state = [
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'country' => $user->country,
            'state' => $user->state,
            'city' => $user->city,
        ];
        
        // Load countries for initial dropdown
        $this->countries = Country::select('name', 'id')->orderBy('name')->pluck('name', 'id')->toArray();
        
        // Load states if country is selected
        if ($user->country) {
            $this->states = State::where('country_id', $user->country)
                ->select('name', 'id')
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }
        
        // Load cities if state is selected
        if ($user->state) {
            $this->cities = City::where('state_id', $user->state)
                ->select('name', 'id')
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }
    }

    public function updatedStateCountry($value)
    {
        if ($value) {
            $this->states = State::where('country_id', $value)
                ->select('name', 'id')
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
            $this->state['state'] = '';
            $this->state['city'] = '';
        } else {
            $this->states = [];
            $this->cities = [];
            $this->state['state'] = '';
            $this->state['city'] = '';
        }
    }

    public function updatedStateState($value)
    {
        if ($value) {
            $this->cities = City::where('state_id', $value)
                ->select('name', 'id')
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
            $this->state['city'] = '';
        } else {
            $this->cities = [];
            $this->state['city'] = '';
        }
    }

    public function updateProfileInformation()
    {
        $user = auth()->user();

        if ($this->photo) {
            $path = $this->photo->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->name = $this->state['name'];
        $user->email = $this->state['email'];
        $user->mobile = $this->state['mobile'];
        $user->country = $this->state['country'];
        $user->state = $this->state['state'];
        $user->city = $this->state['city'];
        $user->save();

        $this->emit('saved');
        $this->emit('refresh');
    }

    public function deleteProfilePhoto()
    {
        $user = auth()->user();
        $user->profile_photo_path = null;
        $user->save();
        $this->emit('refresh');
    }

    public function render()
    {
        return view('profile.update-profile-information-form', [
            'countries' => $this->countries,
            'states' => $this->states,
            'cities' => $this->cities
        ]);
    }
}