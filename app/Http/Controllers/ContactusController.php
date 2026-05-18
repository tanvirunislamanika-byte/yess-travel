<?php



namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Anhskohbo\NoCaptcha\NoCaptcha;

use App\Models\Contact_us;
use App\Mail\Contactus;
use App\Mail\ContactMail;

use Mail;


class ContactusController extends Controller

{

    public function index(){



        return view('contact_us');



    } 



    public function client(){



        return view('client');



    } 

    

    public function faqs(){



        return view('faqs');



    } 



    public function store(Request $request){

        $this->validate($request, [

            'first_name' => 'required',

            'email_address' => 'required',

            'message' => 'required'

        ], [

            'first_name.required' => 'Full Name is required.',

            'last_name.required' => 'Last Name is required.',

            'email_address.required' => 'Email Address is required.',

            'phone_number.required' => 'Phone Number is required.',

            'message.required' => 'Message is required.',

        ]);



        $contact = new Contact_us();

        $contact->first_name = $request->first_name;

        $contact->email_address = $request->email_address;

        $contact->message = $request->message;

        $data = $request->all();

        $data['subject'] = $request->subject;
        $data['contact'] = $contact;

        Mail::to(widget(1)->extra_field_2)->send(new ContactMail($data));

        $request->session()->flash('message.added', 'success');

        $request->session()->flash('message.content', 'Your message has been received. Our team will contact you shortly');

        return redirect()->back();

    }  





    public function clientStore(Request $request){

        $this->validate($request, [

            'con_name' => 'required',

            'con_email' => 'required',

            'job_title' => 'required',

            'con_phone' => 'required',

            'job_location' => 'required',

            'job_type' => 'required',

            'con_message' => 'required',

        ]);



        $data['con_name'] = $request->con_name;

        $data['con_email'] = $request->con_email;

        $data['job_title'] = $request->job_title;

        $data['con_phone'] = $request->con_phone;

        $data['job_location'] = $request->job_location;

        $data['job_type'] = $request->job_type;

        $data['con_message'] = $request->con_message;

        $data = array();

        $data['name'] = $request->con_name;
        $data['email'] = $request->con_email;
        $data['job_title'] = $request->job_title;
        $data['phone'] = $request->con_phone;
        $data['location'] = $request->job_location;
        $data['job_type'] = $request->job_type;
        $data['con_message'] = $request->con_message;

        

        $request->session()->flash('message.added', 'success');

        $request->session()->flash('message.content', 'Your form is successfully submitted');

        return redirect()->back();

    }  

}

