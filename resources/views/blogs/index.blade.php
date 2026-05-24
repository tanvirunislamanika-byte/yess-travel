<x-app-layout>

<!-- Page title start -->
<div class="pageheader" style="background: url('{{ asset('images/blog/blog.jpg') }}') no-repeat center center; background-size: cover;">            
    <div class="container">
        <h1>{{__('Latest From Blog')}}</h1>
    </div>
</div>
<!-- Page title end -->

<style>

    /* Search Box */
    .top-search-box{
        margin-bottom: 30px;
        display: flex;
        justify-content: center;
    }

    .sidebar-search-form{
        position: relative;
        width: 100%;
        max-width: 700px;
    }

    .sidebar-search-form input{
        width: 100%;
        height: 55px;
        border: 1px solid #ddd;
        border-radius: 12px;
        padding: 0 60px 0 18px;
        font-size: 16px;
        outline: none;
    }

    .sidebar-search-form button{
        position: absolute;
        right: 5px;
        top: 5px;
        width: 45px;
        height: 45px;
        border: none;
        border-radius: 10px;
        background: #0e52a5;
        color: #fff;
        cursor: pointer;
    }

    /* Blog Cards */
    .subposts{
        background: #fff;
        border-radius: 14px;
        overflow: visible;
        box-shadow: 0 5px 18px rgba(0,0,0,0.06);
        margin-bottom: 35px;
        transition: 0.3s;
        position: relative;
    }

    .subposts:hover{
        transform: translateY(-4px);
    }

    .postimg{
        position: relative;
        overflow: hidden;
        border-radius: 14px 14px 0 0;
    }

    .postimg img{
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: 0.4s;
    }

    .subposts:hover .postimg img{
        transform: scale(1.05);
    }

    /* Date Button */
    .date{
        position: absolute;
        left: 15px;
        bottom: 95px;
        background: #0e52a5;
        color: #fff;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        z-index: 2;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .postinfo{
        padding: 22px 15px 15px;
    }

    .postinfo h3{
        font-size: 18px;
        line-height: 28px;
        margin-bottom: 0;
    }

    .postinfo h3 a{
        color: #111;
        text-decoration: none;
        transition: 0.3s;
    }

    .postinfo h3 a:hover{
        color: #0e52a5;
    }

    /* Sidebar */
    .blogsidebar .widget{
        background: #fff;
        padding: 20px;
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }

    .widget_title{
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .wdgtnav{
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .wdgtnav li{
        margin-bottom: 10px;
    }

    .wdgtnav li a{
        color: #333;
        text-decoration: none;
        transition: 0.3s;
    }

    .wdgtnav li a:hover{
        color: #0e52a5;
        padding-left: 5px;
    }

    .pagination-wrapper{
        margin-top: 20px;
    }

    @media(max-width: 991px){
        .blogsidebar{
            margin-top: 40px;
        }

        .date{
            bottom: 100px;
        }
    }

    @media(max-width: 767px){
        .postimg img{
            height: 190px;
        }

        .postinfo h3{
            font-size: 17px;
            line-height: 26px;
        }

        .date{
            font-size: 13px;
            padding: 7px 12px;
        }

        .sidebar-search-form{
            max-width: 100%;
        }
    }

</style>

<div class="hmblog parallax-section">
    <div class="container">

        <!-- Search Box Top -->
        <div class="top-search-box">

            <form action="{{ url('/blog') }}" method="GET" class="sidebar-search-form">

                <input 
                    type="search" 
                    name="keyword" 
                    placeholder="Search blog..." 
                    value="{{ request('keyword') }}"
                >

                <button type="submit">
                    <i class="fas fa-search"></i>
                </button>

            </form>

        </div>

        <div class="row">

            <!-- Posts -->
            <div class="col-lg-9">

                @if($blogs->isEmpty())

                    <h2 class="text-center">

                        No records found for 

                        @if(request('keyword'))
                            "{{ request('keyword') }}"
                        @elseif(request('category'))
                            "Category: {{ request('category') }}"
                        @endif

                    </h2>

                @else

                    <h4 class="mb-4">

                        {{ $blogs->total() }} records found for 

                        @if(request('keyword'))
                            "{{ request('keyword') }}"
                        @elseif(request('category'))
                            "Category: {{ request('category') }}"
                        @endif

                    </h4>

                    <div class="row">

                        @foreach($blogs as $blog)

                            <div class="col-lg-6 col-md-6">

                                <div class="subposts">

                                    <div class="postimg">

                                        <img src="{{ asset('images/' . $blog->image) }}" alt="Blog Image">

                                    </div>

                                    <!-- Date -->
                                    <div class="date">
                                        {{ date('d M Y', strtotime($blog->created_at)) }}
                                    </div>

                                    <div class="postinfo">

                                        <h3>
                                            <a href="{{ route('blogs.detail', $blog->slug) }}" class="pageLnks">
                                                {{ $blog->getTranslatedTitle() }}
                                            </a>
                                        </h3>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                    <!-- Pagination -->
                    <div class="pagination-wrapper mt-4">
                        {{ $blogs->links('pagination::bootstrap-4') }}
                    </div>

                @endif

            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">

                <div class="blogsidebar sticky-top">

                    <div class="widget">

                        <div class="widget_title">
                            Categories
                        </div>

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

        </div>

    </div>
</div>

</x-app-layout>