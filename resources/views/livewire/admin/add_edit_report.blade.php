<div class="container-xl px-4">

        <div class="card mb-4">
            <div  class="card-header">
                <div class="row">
                    <div class="col-md-10">Report</div>
                    <div class="col-md-2">
                        <div class="input-group input-group-joined border-0 add-button">
                            <a class="btn btn-danger btn-sm" href="javascript:;" wire:click="reports()">All Reports</a>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="card-body">
                
               <div class="tab-content" id="nav-tabContent"> 
                <div class="tab-pane fade show active" id="nav-customer" role="tabpanel" aria-labelledby="nav-customer-tab">
                <form wire:submit.prevent="submitForm">
                 @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif   

               <div class="sbp-preview">

                  <div class="sbp-preview-content">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('Date', 'Date', ['class' => '']) !!}
                                {!! Form::date('Date', null, array('wire:model.live'=>'state.date','class'=>'form-control', 'id'=>'date', 'placeholder'=>'Date', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('time', 'Time of Incident', ['class' => '']) !!}
                                {!! Form::time('time', null, array('wire:model.live'=>'state.time','class'=>'form-control', 'id'=>'time', 'placeholder'=>'Time of Incident', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                {!! Form::label('injury_location', 'Injury Location', ['class' => '']) !!}
                                {!! Form::text('injury_location', null, array('wire:model.live'=>'state.injury_location','class'=>'form-control', 'id'=>'injury_location', 'placeholder'=>'Injury Location', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('trail', 'Select Trail', ['class' => '']) !!}
                                {!! Form::select('trail', [''=>'Select Trail']+dropdown(2), null, array('wire:model.live'=>'state.trail','class'=>'form-control', 'id'=>'trail', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'trail') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('experience_on_trail', 'Experience on Trail', ['class' => '']) !!}
                                {!! Form::select('experience_on_trail', [''=>'Select Experience']+dropdown(3), null, array('wire:model.live'=>'state.experience_on_trail','class'=>'form-control', 'id'=>'experience_on_trail', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'experience_on_trail') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('ski_lift', 'Ski Lift', ['class' => '']) !!}
                                {!! Form::select('ski_lift', [''=>'Select Ski Lift']+dropdown(4), null, array('wire:model.live'=>'state.ski_lift','class'=>'form-control', 'id'=>'ski_lift', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'state.ski_lift') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('experience_on_lift', 'Experience on Lift', ['class' => '']) !!}
                                {!! Form::select('experience_on_lift', [''=>'Select Experience on Lift']+dropdown(5), null, array('wire:model.live'=>'state.experience_on_lift','class'=>'form-control', 'id'=>'experience_on_lift', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'experience_on_lift') !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                {!! Form::label('tubing', 'Tubing', ['class' => '']) !!}
                                {!! Form::select('tubing', [''=>'Select Tubing']+dropdown(7), null, array('wire:model.live'=>'state.tubing','class'=>'form-control', 'id'=>'tubing', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'tubing') !!}
                            </div>
                        </div>
                        @if(@$state['tubing']=='21')
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('equipment', 'Equipment', ['class' => '']) !!}
                                {!! Form::select('equipment', [''=>'Select Equipment']+dropdown(8), null, array('wire:model.live'=>'state.equipment','class'=>'form-control', 'id'=>'equipment', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'equipment') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('removed_by_patrol', 'Removed by Patrol', ['class' => '']) !!}
                                {!! Form::select('removed_by_patrol', [''=>'Select Removed by Patrol']+dropdown(9), null, array('wire:model.live'=>'state.removed_by_patrol','class'=>'form-control', 'id'=>'removed_by_patrol', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'removed_by_patrol') !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                {!! Form::label('rental_skis_snowboard', 'Rental Skis / Snowboard', ['class' => '']) !!}
                                {!! Form::select('rental_skis_snowboard', [''=>'Select Rental Skis / Snowboard']+dropdown(11), null, array('wire:model.live'=>'state.rental_skis_snowboard','class'=>'form-control', 'id'=>'rental_skis_snowboard', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'rental_skis_snowboard') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('experience_on_skis_snowboard', 'Experience on Skis / Snowboard', ['class' => '']) !!}
                                {!! Form::select('experience_on_skis_snowboard', [''=>'Select Experience on Skis / Snowboard']+dropdown(13), null, array('wire:model.live'=>'state.experience_on_skis_snowboard','class'=>'form-control', 'id'=>'experience_on_skis_snowboard', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'experience_on_skis_snowboard') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('wearing_helmet', 'Wearing Helmet', ['class' => '']) !!}
                                {!! Form::select('wearing_helmet', [''=>'Select']+dropdown(14), null, array('wire:model.live'=>'state.wearing_helmet','class'=>'form-control', 'id'=>'wearing_helmet', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'wearing_helmet') !!}
                            </div>
                        </div>
                        @if(@$state['wearing_helmet']=='38')
                        <div class="col-md-12">
                            <div class="mb-3">
                                {!! Form::label('removed_at_accident', 'Removed at Accident', ['class' => '']) !!}
                                {!! Form::select('removed_at_accident', [''=>'Select']+dropdown(15), null, array('wire:model.live'=>'state.removed_at_accident','class'=>'form-control', 'id'=>'removed_at_accident', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'removed_at_accident') !!}
                            </div>
                        </div>
                        @endif
                        @endif
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('first_name', 'First Name', ['class' => '']) !!}
                                {!! Form::text('first_name', null, array('wire:model.live'=>'state.first_name','class'=>'form-control', 'id'=>'first_name', 'placeholder'=>'First Name', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('last_name', 'Last Name', ['class' => '']) !!}
                                {!! Form::text('last_name', null, array('wire:model.live'=>'state.last_name','class'=>'form-control', 'id'=>'last_name', 'placeholder'=>'Last Name', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                {!! Form::label('street_address', 'Street Address', ['class' => '']) !!}
                                {!! Form::text('street_address', null, array('wire:model.live'=>'state.street_address','class'=>'form-control', 'id'=>'street_address', 'placeholder'=>'Street Address', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('city', 'City', ['class' => '']) !!}
                                {!! Form::text('city', null, array('wire:model.live'=>'state.city','class'=>'form-control', 'id'=>'city', 'placeholder'=>'City', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('state', 'State', ['class' => '']) !!}
                                {!! Form::text('state', null, array('wire:model.live'=>'state.state','class'=>'form-control', 'id'=>'state', 'placeholder'=>'State', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('dob', 'Date of Birth', ['class' => '']) !!}
                                {!! Form::date('dob', null, array('wire:model.live'=>'state.dob','class'=>'form-control', 'id'=>'dob', 'placeholder'=>'Date of Birth', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('sex', 'Sex', ['class' => '']) !!}
                                {!! Form::select('sex', [''=>'Select Sex']+dropdown(16), null, array('wire:model.live'=>'state.sex','class'=>'form-control', 'id'=>'sex', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'sex') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('weight', 'Weight', ['class' => '']) !!}
                                {!! Form::text('weight', null, array('wire:model.live'=>'state.weight','class'=>'form-control', 'id'=>'weight', 'placeholder'=>'Weight', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'weight') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('height', 'Height', ['class' => '']) !!}
                                {!! Form::text('height', null, array('wire:model.live'=>'state.height','class'=>'form-control', 'id'=>'height', 'placeholder'=>'Height', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'height') !!}
                            </div>
                        </div>
                        <?php 
                            $birthDate = date('Y-m-d',strtotime(@$state['dob']));

                            // Create a Carbon instance for the birthdate
                            $birthdateObj = Carbon\Carbon::parse($birthDate);

                            // Get the current date
                            $currentDate = Carbon\Carbon::now();

                            // Calculate the difference in years
                            $age = $currentDate->diffInYears($birthdateObj);
                            
                            //dd($age);
                            $lab = 'Email Address';
                            $lab2 = 'Phone Number';


                         ?>
                         @if($age<18)
                         <div class="col-md-12 mb-2 mt-4">
                             <div class="card-header" style="background-color: #eeeeee;color: #888b90 !important;">
                                <div class="row">
                                    <div class="col-md-10">Parent Contact</div>
                                    
                                </div>
                                
                            </div>
                        </div>
                        @endif
                         <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('email_address', $lab, ['class' => '']) !!}
                                {!! Form::email('email_address', null, array('wire:model.live'=>'state.email_address','class'=>'form-control', 'id'=>'email_address', 'placeholder'=>$lab, 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'email_address') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('phone_number', $lab2, ['class' => '']) !!}
                                {!! Form::number('phone_number', null, array('wire:model.live'=>'state.phone_number','class'=>'form-control', 'id'=>'phone_number', 'placeholder'=>$lab2, 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'phone_number') !!}
                            </div>
                        </div>
                        @if($age<18)
                        <div class="col-md-12 ">
                        <br>
                        <hr>
                        
                        <br>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('work_lessons_at_bousquet', 'Work / Lessons at Bousquet', ['class' => '']) !!}
                                {!! Form::select('work_lessons_at_bousquet', [''=>'Select Work / Lessons at Bousquet']+dropdown(17), null, array('wire:model.live'=>'state.work_lessons_at_bousquet','class'=>'form-control', 'id'=>'work_lessons_at_bousquet', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'work_lessons_at_bousquet') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('contacts_eyeglasses', 'Contacts / Eyeglasses', ['class' => '']) !!}
                                {!! Form::select('contacts_eyeglasses', [''=>'Select Contacts / Eyeglasses']+dropdown(18), null, array('wire:model.live'=>'state.contacts_eyeglasses','class'=>'form-control', 'id'=>'contacts_eyeglasses', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'contacts_eyeglasses') !!}
                            </div>
                        </div>
                        @if(@$state['contacts_eyeglasses']=='96')
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('witness_first_name', 'Witness First Name', ['class' => '']) !!}
                                {!! Form::text('witness_first_name', null, array('wire:model.live'=>'state.witness_first_name','class'=>'form-control', 'id'=>'witness_first_name', 'placeholder'=>'Witness First Name', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('witness_last_name', 'Witness Last Name', ['class' => '']) !!}
                                {!! Form::text('witness_last_name', null, array('wire:model.live'=>'state.witness_last_name','class'=>'form-control', 'id'=>'witness_last_name', 'placeholder'=>'Witness Last Name', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                {!! Form::label('witness_street_address', 'Witness Street Address', ['class' => '']) !!}
                                {!! Form::text('witness_street_address', null, array('wire:model.live'=>'state.witness_street_address','class'=>'form-control', 'id'=>'witness_street_address', 'placeholder'=>'Witness Street Address', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('witness_city', 'Witness City', ['class' => '']) !!}
                                {!! Form::text('witness_city', null, array('wire:model.live'=>'state.witness_city','class'=>'form-control', 'id'=>'witness_city', 'placeholder'=>'Witness City', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('witness_state', 'Witness State', ['class' => '']) !!}
                                {!! Form::text('witness_state', null, array('wire:model.live'=>'state.witness_state','class'=>'form-control', 'id'=>'witness_state', 'placeholder'=>'Witness State', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('witness_phone_number', 'Witness Phone Number', ['class' => '']) !!}
                                {!! Form::text('witness_phone_number', null, array('wire:model.live'=>'state.witness_phone_number','class'=>'form-control', 'id'=>'witness_phone_number', 'placeholder'=>'Witness Phone Number', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('involved_with_accident', 'Involved with Accident', ['class' => '']) !!}
                                {!! Form::select('involved_with_accident', [''=>'Select Contacts / Eyeglasses']+dropdown(19), null, array('wire:model.live'=>'state.involved_with_accident','class'=>'form-control', 'id'=>'involved_with_accident', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'involved_with_accident') !!}
                            </div>
                        </div>
                        @endif
                        <div class="col-md-12 mb-2 mt-4">
                             <div class="card-header" style="background-color: #eeeeee;color: #888b90 !important;">
                                <div class="row">
                                    <div class="col-md-10">Injury Graphic with Pages</div>
                                    
                                </div>
                                
                            </div>
                        </div>
                        @if(@$state['transport_in_method']=='102')
                        <div class="col-md-6">
                        @else
                        <div class="col-md-12">
                        @endif
                            <div class="mb-3">
                                {!! Form::label('transport_in_method', 'Transport in Method', ['class' => '']) !!}
                                {!! Form::select('transport_in_method', [''=>'Select Transport in Method']+dropdown(20), null, array('wire:model.live'=>'state.transport_in_method','class'=>'form-control', 'id'=>'transport_in_method', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'transport_in_method') !!}
                            </div>
                        </div>
                        @if(@$state['transport_in_method']=='102')
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('transport_in_method_patrollers', 'Add Patrollers', ['class' => '']) !!}
                                {!! Form::text('transport_in_method_patrollers', null, array('wire:model.live'=>'state.transport_in_method_patrollers','class'=>'form-control', 'id'=>'transport_in_method_patrollers', 'placeholder'=>'Add Patrollers', 'required'=>'required')) !!}
                            </div>
                        </div>
                        @endif
                        @if(@$state['highest_care_on_hill']=='106')
                        <div class="col-md-6">
                        @else
                        <div class="col-md-12">
                        @endif
                            <div class="mb-3">
                                {!! Form::label('highest_care_on_hill', 'Highest Care on Hill', ['class' => '']) !!}
                                {!! Form::select('highest_care_on_hill', [''=>'Select Highest Care on Hill']+dropdown(21), null, array('wire:model.live'=>'state.highest_care_on_hill','class'=>'form-control', 'id'=>'highest_care_on_hill', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'highest_care_on_hill') !!}
                            </div>
                        </div>
                        @if(@$state['highest_care_on_hill']=='106')
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('care_rendered', 'Care Rendered', ['class' => '']) !!}
                                {!! Form::select('care_rendered', [''=>'Select Care Rendered']+dropdown(22), null, array('wire:model.live'=>'state.care_rendered','class'=>'form-control', 'id'=>'care_rendered', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'care_rendered') !!}
                            </div>
                        </div>
                        @endif 


                        @if(@$state['highest_care_in_aid_room']=='112')
                        <div class="col-md-6">
                        @else
                        <div class="col-md-12">
                        @endif
                            <div class="mb-3">
                                {!! Form::label('highest_care_in_aid_room', 'Highest Care in Aid Room', ['class' => '']) !!}
                                {!! Form::select('highest_care_in_aid_room', [''=>'Select Highest Care on Hill']+dropdown(23), null, array('wire:model.live'=>'state.highest_care_in_aid_room','class'=>'form-control', 'id'=>'highest_care_in_aid_room', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'highest_care_in_aid_room') !!}
                            </div>
                        </div>
                        @if(@$state['highest_care_in_aid_room']=='112')
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('highest_care_in_aid_room_care_rendered', 'Care Rendered', ['class' => '']) !!}
                                {!! Form::select('highest_care_in_aid_room_care_rendered', [''=>'Select Care Rendered']+dropdown(22), null, array('wire:model.live'=>'state.highest_care_in_aid_room_care_rendered','class'=>'form-control', 'id'=>'highest_care_in_aid_room_care_rendered', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'highest_care_in_aid_room_care_rendered') !!}
                            </div>
                        </div>
                        @endif
                        <div class="col-md-12">
                            <div class="mb-3">
                                {!! Form::label('highest_care_in_aid_room_patrollers', 'Add Patrollers', ['class' => '']) !!}
                                {!! Form::text('highest_care_in_aid_room_patrollers', null, array('wire:model.live'=>'state.highest_care_in_aid_room_patrollers','class'=>'form-control', 'id'=>'highest_care_in_aid_room_patrollers', 'placeholder'=>'Add Patrollers', 'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('transport_out_method', 'Transport out Method', ['class' => '']) !!}
                                {!! Form::select('transport_out_method', [''=>'Select Transport out Method']+dropdown(24), null, array('wire:model.live'=>'state.transport_out_method','class'=>'form-control', 'id'=>'transport_out_method', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'transport_out_method') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                {!! Form::label('plan_to_ski_again_today', 'Plan to Ski Again Today', ['class' => '']) !!}
                                {!! Form::select('plan_to_ski_again_today', [''=>'Select']+dropdown(25), null, array('wire:model.live'=>'state.plan_to_ski_again_today','class'=>'form-control', 'id'=>'plan_to_ski_again_today', 'required'=>'required')) !!}
                                {!! APFrmErrHelp::showErrors($errors, 'plan_to_ski_again_today') !!}
                            </div>
                        </div>
                    </div>


                     <div class="col-sm-12 col-md-12 text-center">

                        <button type="submit" class="btn btn-primary w-100">{{__('Create')}} &nbsp <i class="fa-solid fa-arrow-right"></i></button>

                     </div>

                  </div>

               </div>
           </div>
               </div>
           </div>
       </form>
           </div>

            </div>

        </div>

        

    </div>
</div>