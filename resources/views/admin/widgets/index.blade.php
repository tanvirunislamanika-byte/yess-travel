<x-admin-layout>
    <div class="container-xl px-4">
        <div class="card mb-4">
            <livewire:admin.common.header
                :title="'Widget List'"
                :content="'List of all Widgets'"
                :icon="'fa-school'"
                :term="__('Widget')"
                :slug="route('admin.widgets.add')"
                :button="__('Add New Widget')"
            />
            <div class="card-body">
                @if(session()->has('message.added'))
                    <div class="alert alert-{{ session('message.added') }} alert-dismissible fade show" role="alert">
                        <strong>{{__('Congratulations')}}!</strong> {!! session('message.content') !!}.
                    </div>
                @endif
                <table class="table table-bordered" id="table-modules-data">
                  <thead>
                     <tr>
                        <th>{{__('Widget Name')}}</th>
                        <th>{{__('Action')}}</th>
                     </tr>
                  </thead>
                  <tbody>
                     @if($widgets)
                     @foreach($widgets as $widget)
                     <tr>
                        <td>{{$widget->title}}</td>
                       
                        <td>
                          <a href="{{route('admin.widgets.edit',$widget->id)}}" class="tabledit-edit-button btn btn-primary waves-effect waves-light btn-sm"><span class="icofont icofont-ui-edit"></span>&nbsp {{__('Edit')}}</a>
                          
                           <a href="{{route('admin.widgets.delete',$widget->id)}}" class="tabledit-delete-button btn btn-danger waves-effect waves-light btn-sm"><span class="icofont icofont-ui-delete"></span>&nbsp {{__('Delete')}}</a>
                        </td>
                     </tr>
                     @endforeach
                     @endif
                  </tbody>
               </table>
               </div>
            </div>
         </div>
     </x-admin-layout>
