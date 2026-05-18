<x-app-layout>


<!-- Page title start -->
<div class="pageheader">            
    <div class="container">
        <h1>{{__('Latest From Blog')}}</h1>
    </div>
</div>
<!-- Page title end -->

    <div class="hmblog parallax-section">
      <div class="container">

            <div class="row">
               <!-- Sidebar -->
               <div class="col-lg-3">
                  <div class="blogsidebar sticky-top">
                     <div class="widget">
                     <form action="{{ url('/blog') }}" method="GET" class="sidebar-search-form">
    <input type="search" name="keyword" placeholder="Search.." value="{{ request('keyword') }}">
    <button type="submit"><i class="fas fa-search"></i></button>
</form>

                     </div>


                     <div class="widget">
                         <div class="widget_title">Categories</div>
                         @if(null !== ($categories = module(24)))
                            <ul class="wdgtnav">
                                @foreach($categories as $category)
                                    <li>
                                    <a href="{{ url('/blog?category=' . $category->id) }}">
                                            {{ $category->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                     </div>                     
                 </div>
               </div>
               <!-- Posts -->
               <div class="col-lg-9">
               <div class="row">



               @if($blogs->isEmpty())
    <h2 class="text-center">No records found for 
        @if(request('keyword'))
            "{{ request('keyword') }}"
        @elseif(request('category'))
            "Category: {{ request('category') }}"
        @endif
    </h2>
@else
    <h4 class="mb-3">{{ $blogs->total() }} records found for 
        @if(request('keyword'))
            "{{ request('keyword') }}"
        @elseif(request('category'))
            "Category: {{ request('category') }}"
        @endif
    </h4>

    <div class="row">
        @foreach($blogs as $blog)
            <div class="col-lg-6">
                <div class="subposts">
                    <div class="postimg">
                        <img src="{{ asset('images/' . $blog->image) }}">
                    </div>
                    <div class="postinfo">
                        <h3><a href="{{ route('blogs.detail', $blog->slug) }}" class="pageLnks">{{ $blog->getTranslatedTitle() }}</a></h3>
                    </div>
                    <div class="date">{{ date('d M Y', strtotime($blog->created_at)) }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination Links -->
    <div class="pagination-wrapper">
    {{ $blogs->links('pagination::bootstrap-4') }}
    </div>
@endif





            
         </div>                
             
               </div>
            </div>




       
         
      </div>
   </div>



</x-app-layout>