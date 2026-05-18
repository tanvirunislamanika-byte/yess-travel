<x-admin-layout>
    <style>
        .badge-primary{
            font-size: 12px;
    background: #3f51b5;
    color: #fff;
        }
    </style>
    <div class="container-xl px-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8">{{$module->name}}</div>
                    <div class="col-md-4">
                        <div class="input-group input-group-joined border-0 add-button">
                            @if($module->is_preview)
                      @endif<a class="btn btn-danger btn-sm" href="{{url('/admin/'.$module->slug.'/add')}}">{{__('Add New '.$module->term)}}</a>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="card-body">
                @if(session()->has('message.added'))
                    <div class="alert alert-{{ session('message.added') }} alert-dismissible fade show" role="alert">
                        <strong>{{__('Congratulations')}}!</strong> {!! session('message.content') !!}.
                    </div>
                @endif
                <input type="hidden" id="table-modules-data-url" value="{{ Request::url() }}">
                <input type="hidden" id="is_enable_modules_data_action" value="{{ (in_array('modules.data.edit', permissions()) || in_array('modules.data.destroy', permissions())) ? 'yes' : 'no' }}">
                <table class="table table-bordered" id="table-modules-data">
                    <thead>
                        <tr>
                            @if($module->is_image)
                                <th>{{__('Image')}}</th>
                            @endif
                            
                            @if($module->is_preview)
                            <th>
                            <span><input type="checkbox" name="title[]" value="all" placeholder="" class=""></span>
                            </th>
                            @endif
                            
                            <th>
                            {{$module->term}}
                                
                            </th>
                            
                            @if($module->category)
                                <th>{!! $parent->term !!}</th>
                            @endif
                            @foreach($module->fields()->get() ?? [__('Created Date')] as $list)
                                <th>{{ $list->field_title ?? __('Created Date') }}</th>
                            @endforeach
                            <th>{{__('Status')}}</th>
                            

                            <th style="@if($module->is_preview) width: 120px; @endif">{{__('Action')}}</th>
                        </tr>
                        <tr role="row" class="filter">
                            @if($module->is_image)
                                <td></td>
                            @endif
                            @if($module->is_preview)
                            <td>
                                
                               
                                
                                
                            </td>
                            @endif
                            <td>
                                <input type="text" class="form-control" name="title" id="title" autocomplete="off" placeholder="Search Name">
                                
                            </td>
                            @if($module->category)
                                <td>{!! Form::select('category', [''=>'Select '.$parent->term]+dataArray($module->parent_id), null, array('class'=>'form-control', 'id'=>'category', 'required'=>'required')) !!}</td>
                            @endif
                            @foreach($module->fields()->get() ?? [] as $list)
                                <td>
                                    @if($list->field_type == 'select')
                                    {!! Form::select($list->field, [''=>'Select '.$list->field_title]+dataArray($list->field_attr), null, array('class'=>'form-control', 'id'=>$list->field, 'required'=>'required')) !!}
                                    @else
                                    <input type="text" class="form-control" name="{{ $list->field }}" id="{{ $list->field }}" autocomplete="off" placeholder="Search {{ $list->field_title }}">
                                    @endif
                                </td>
                            @endforeach
                           
                            <td>
                               <select name="status" id="status" class="form-control status">
                                    <option value="">Select Status</option>
                                    
                                    <option value="active">Active</option>
                                    <option value="blocked">Blocked</option>
                                    
                                </select>
                                
                            </td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" id="is_image" name="is_image" value="{{ $module->is_image ? 'yes' : 'no' }}">
    <input type="hidden" id="is_preview" name="is_preview" value="{{ $module->is_preview ? 'yes' : 'no' }}">
    <input type="hidden" id="is_category" name="is_category" value="{{ $module->category ? 'yes' : 'no' }}">
    <input type="hidden" id="message_added" name="message_added" value="{{ session()->has('message.added') ? 'yes' : 'no' }}">
    <input type="hidden" id="message" name="message" value="{!! session('message.content') !!}">
    <input type="hidden" id="modules_data_fetch" name="modules_data_fetch" value="{{ route('admin.modules.data.fetch') }}">
    <input type="hidden" id="module_id" name="module_id" value="{{ $module->id }}">
    <input type="hidden" id="module_fields" name="module_fields" value="{{ json_encode($module->fields()->get()) }}">
    <input type="hidden" id="base_url" name="base_url" value="{{ url('/') }}">

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Assign Contacts</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('admin.assign-contacts')}}" method="post">
                        @csrf
                        <input type="hidden" id="hiddenField" name="selectcontacts">
                    <div class="modal-body">
                        <!-- Your form goes here -->
                        
                         <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    
                                   
                                    <th>
                                    Check
                                    </th>
                                 </tr>
                            </thead> 
                            <tbody>
                                <?php $users = App\Models\User::where('role',4)->get(); ?>
                                 @if(null!==($users))
                                 @foreach($users as $user)
                                 <tr>
                                     <td>{{$user->name}}</td>
                                     <td><input type="radio" value="{{$user->id}}" name="selected_users" class="selected_users"></td>
                                 </tr>
                                 
                                 @endforeach
                                 @endif
                                 <tr>
                                     <td style="text-align: left !important;"><label for="">Start Date</label><input class="form-control" type="date" name="start_date"></td>
                                     <td style="text-align: left !important;"><label for="">End Date</label><input class="form-control" type="date" name="end_date"></td>
                                 </tr>
                                 <tr   >
                                     <td colspan="2" style="text-align: left !important;">
                                        <label for="">Task</label>
                                         <textarea name="task" class="form-control" id="" cols="30" rows="5"></textarea>
                                     </td>
                                 </tr>
                            </tbody>      
                        </table>
                        
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary btn-sm" type="submit">Assign</button>
                        
                    </div>
                    </form>
                    
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="matchingRecordsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="matchingRecordsModalLabel">Import Contacts</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="import-contacts" action="{{ route('admin.import.contacts') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="matchingRecordsCount"></p>

                    <div class="card-body crm-auto-scroll flex-max-content order-2">
                        <div class="">
                            <div class="form-group">
                                <span class="text-row text-color-secondary font-secondary"><b>Select a File</b></span>
                                <span class="text-row text-color-secondary font-secondary" style="margin-left: -0.2rem !important;">
                                    We only support CSV format. Download a
                                    <span class="text-color-secondary font-secondary cursor-pointer" style="color: #008cff;">sample CSV </span>
                                    file.
                                </span>
                                <span class="text-row text-color-secondary font-secondary" style="margin-left: -0.2rem !important;">Maximum limit up to 5000 Contacts</span>
                            </div>
                            <div class="form-group">
                                <div class="button-group">
                                    <span>
                                        <span class="btn btn-primary btn-default-width fake-file-chooser padding-top">From Computer</span>
                                        <input accept=".csv" class="file-chooser" style="display: none;" id="txtFileUpload" name="csv_file" type="file" />
                                    </span>
                                    &nbsp
                                </div>
                            </div>
                            <div class="form-group"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" appdebounceclick class="btn btn-primary btn-default-width margin-top-10px btn-import-contacts">Import Contacts</button>
                </div>
            </form>
        </div>
    </div>
</div>


        <div class="modal fade" id="matchingRecordsModal" tabindex="-1" role="dialog" aria-labelledby="matchingRecordsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="matchingRecordsModalLabel">Matching Records Found</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="matchingRecordsCount"></p>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="recordAction" id="overrideRecords" value="override" checked>
                    <label class="form-check-label" for="overrideRecords">
                        Override Existing Records
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="recordAction" id="ignoreRecords" value="ignore">
                    <label class="form-check-label" for="ignoreRecords">
                        Ignore Existing Records
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="exportMatchingRecordsBtn">Continue</button>
            </div>
        </div>
    </div>
</div>

    @push('js')
        <script src="{{ asset('admin_assets/js/modules.js?v=4.2') }}"></script>
        <script>
            $('.fake-file-chooser').click(function () {
            $('#txtFileUpload').click();
        });

             $('#txtFileUpload').change(function () {
            var selectedFile = $(this).prop('files')[0];
            if (selectedFile) {
                // Extract only the file name from the path
                var selectedFileName = selectedFile.name;
                
                // Perform actions with the selected file name or upload the file.
                $('.fake-file-chooser').text(selectedFileName);
            }
        });
        $('.btn-import-contacts').click(function () {
            var formData = new FormData($('#import-contacts')[0]);

            // Fetch CSRF token from the page
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Add CSRF token to headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            // Add selected tags to form data
        

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.import.contacts') }}',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.matchingRecords) {
                        // Show the popup with matching records count
                        showMatchingRecordsCountPopup(response.matchingRecords);
                    }else{
                      $('#myModal1').modal('hide');  
                      Swal.fire({







                  icon: 'success',







                  title: 'Success!',







                  text: "Successfully Imported",







                });
                    }
                    
                },
                error: function (error) {
                    // Handle error
                    console.log(error);
                }
            });
        });

        $('#exportMatchingRecordsBtn').click(function () {
            var formData = new FormData($('#import-contacts')[0]);

            // Fetch CSRF token from the page
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Add CSRF token to headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            // Add selected tags to form data
            

            formData.append('recordAction',$("input[name='recordAction']:checked").val())

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.import.contacts') }}',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {

                    $('#matchingRecordsModal').modal('hide');

                    Swal.fire({







                  icon: 'success',







                  title: 'Success!',







                  text: "Successfully Imported",







                });
                },
                error: function (error) {
                    // Handle error
                    console.log(error);
                }
            });
        });
        function showMatchingRecordsCountPopup(matchingRecords) {
            var count = Object.keys(matchingRecords).length;
            $('#matchingRecordsCount').html('Found ' + count + ' matching records. <a href="#" style="color: #0168fa;">Export Csv</a>');
            $('#matchingRecordsModal').modal('show');
        }
        function exportMatchingRecords(matchingRecords, selectedAction) {
            // Check the user's chosen action
            if (selectedAction === 'override') {
                // Handle overriding existing records
                console.log('Overriding records:', matchingRecords);
            } else if (selectedAction === 'ignore') {
                // Handle ignoring existing records
                console.log('Ignoring records:', matchingRecords);
            }

            // Convert matchingRecords array to CSV
            var csv = Papa.unparse(matchingRecords);

            // Create a Blob object from the CSV data
            var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });

            // Create a link element and trigger a download
            var link = document.createElement('a');
            var url = URL.createObjectURL(blob);
            link.href = url;
            link.setAttribute('download', 'matching_records.csv');
            document.body.appendChild(link);
            link.click();

            // Clean up
            document.body.removeChild(link);
            window.URL.revokeObjectURL(url);
        }
        function saveNewRecords(newRecords) {
        $.ajax({
            type: 'POST',
            url: '{{ route('admin.import.contacts') }}',
            data: {
                newRecords: newRecords,
            },
            success: function (response) {
                // Handle the server's response after processing new records
                console.log(response);
            },
            error: function (error) {
                // Handle error
                console.log(error);
            },
        });
    }     $(document).ready(function() {
              // Attach click event handler to checkboxes
              $(document).on('change', 'input[type="checkbox"]', function() {
                // Get the current value of the hidden field
                var currentValues = $('#hiddenField').val();

                // Initialize an empty array to store unique checked values
                var checkedValues = currentValues ? currentValues.split(',') : [];

                // Loop through all checked checkboxes
                $('input[name="checkedvals[]"]:checked').each(function() {
                  var checkboxValue = $(this).val();

                  // Check if the value is not already in the array before pushing it
                  if (checkedValues.indexOf(checkboxValue) === -1) {
                    checkedValues.push(checkboxValue);
                  }
                });

                // Create a comma-separated string from the array
                var resultString = checkedValues.join(',');

                // Set the resultString as the value of the hidden field
                $('#hiddenField').val(resultString);
              });
            });
                       
        </script>   
    @endpush
</x-admin-layout>
