<x-app-layout>
    
    
<!-- Page title start -->
<div class="pageheader">            
    <div class="container">
        <h1>{{__('Post Overview')}}</h1>
    </div>
</div>
<!-- Page title end -->




<section class="innerPages">
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
                <div class="postimage"><img src="{{asset('images/'.$blog->image)}}" alt="{{$blog->getTranslatedTitle()}}"></div>
                <div class="blogdetail">
                    <h2 class="post-title">{{$blog->getTranslatedTitle()}}</h2>    
                    <div class="date"><i class="fas fa-calendar-alt"></i> {{date('d M Y',strtotime($blog->created_at))}}</div>               

                    <div class="postcontent">
                    {!!$blog->getTranslatedDescription()!!}
                    </div>
                  </div>
               </div>
            </div>

    




</div>    
</section>




</x-app-layout>