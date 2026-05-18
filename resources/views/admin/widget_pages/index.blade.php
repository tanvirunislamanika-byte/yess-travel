<x-admin-layout>

    <div class="container-xl px-4">

        <div class="card mb-4">
            <livewire:admin.common.header

                :title="'Widget Pages List'"

                :content="'List of all Widget Pages are below'"

                :icon="'fa-school'"

                :term="__('Widget Page')"

                :slug="route('admin.widget_pages.add')"

                :button="__('Add new Widget Page')"

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

                                    <th>{{__('Widget Page Name')}}</th>

                                    <th>{{__('Widget Page Slug')}}</th>

                                    <th>{{__('Action')}}</th>

                                 </tr>

                              </thead>

                              <tbody>

                                 @if($widget_pages)

                                 @foreach($widget_pages as $widget_page)

                                 <tr>

                                    <td>{{$widget_page->title}}</td>

                                    <td>{{$widget_page->slug}}</td>

                                    <td>

                                      <a href="{{route('admin.widget_pages.edit',$widget_page->id)}}" class="tabledit-edit-button btn btn-primary waves-effect waves-light btn-sm"><span class="icofont icofont-ui-edit"></span>&nbsp {{__('Edit')}}</a>

                                      

                                       <a href="{{route('admin.widget_pages.delete',$widget_page->id)}}" class="tabledit-delete-button btn btn-danger waves-effect waves-light btn-sm"><span class="icofont icofont-ui-delete"></span>&nbsp {{__('Delete')}}</a>

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

