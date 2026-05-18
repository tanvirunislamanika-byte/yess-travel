<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion pb-5" id="accordionSidenav">
                     
            <!-- Dashboard -->
            <div class="sidenav-menu-heading">Core</div>
            <a class="nav-link" href="{{route('admin.dashboard')}}">
                <div class="nav-link-icon"><i class="fa-solid fa-house"></i></div>
                Dashboard
            </a>

            <!-- Users Management -->
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseUsers" aria-expanded="false" aria-controls="collapseUsers">
                <div class="nav-link-icon"><i class="fa-solid fa-users"></i></div>
                Users Management
                <div class="sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseUsers" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav">
                    <a class="nav-link" href="{{url('admin/users')}}">
                        All Users
                    </a>
                </nav>
            </div>

            <div class="sidenav-menu-heading">Main Modules</div>
            <!-- Hotels Management -->
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseHotels" aria-expanded="false" aria-controls="collapseHotels">
                <div class="nav-link-icon"><i class="fa-solid fa-bed"></i></div>
                Hotels Management
                <div class="sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseHotels" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav">
            <a class="nav-link" href="{{route('admin.modules.data','hotels')}}">
                        All Hotels
            </a>
            <a class="nav-link" href="{{route('admin.modules.data.add','hotels')}}">
                Add New Hotel
            </a>
            <a class="nav-link" href="{{route('admin.modules.data','hotel-types')}}">
                Hotel Types
            </a>
            <a class="nav-link" href="{{route('admin.modules.data','hotel-star')}}">
                        Hotel Star Ratings
            </a>
            <a class="nav-link" href="{{route('admin.modules.data','destinations')}}">
                Destinations
            </a>
            <a class="nav-link" href="{{url('admin/hotel-bookings')}}">
                        Hotel Bookings
                    </a>
                </nav>
            </div>

            <!-- Flights Management -->
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseFlights" aria-expanded="false" aria-controls="collapseFlights">
                <div class="nav-link-icon"><i class="fa-solid fa-plane"></i></div>
                Flights Management
                <div class="sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseFlights" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav">
                    <a class="nav-link" href="{{route('admin.modules.data','airline-companies')}}">
                        Airline Companies
                    </a>
                    <a class="nav-link" href="{{url('admin/flight-orders')}}">
                        Flight Bookings
                    </a>
                    <a class="nav-link" href="{{url('admin/airports')}}">
                        List Airports
                    </a>
                    <a class="nav-link" href="{{url('admin/airports/create')}}">
                        Add New Airport
                    </a>
                </nav>
            </div>           

            <!-- Tours Management -->
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseTours" aria-expanded="false" aria-controls="collapseTours">
                <div class="nav-link-icon"><i class="fa-solid fa-tree"></i></div>
                Tours Management
                <div class="sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseTours" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav">
                    <a class="nav-link" href="{{route('admin.modules.data','tour')}}">
                        Tours List
                    </a>
                    <a class="nav-link" href="{{route('admin.modules.data.add','tour')}}">
                        Add New Tour
                    </a>
                    <a class="nav-link" href="{{route('admin.modules.data','trip-type')}}">
                        Trip Types
                    </a>
                    <a class="nav-link" href="{{route('admin.modules.data','trip-transport')}}">
                        Trip Transport Types
                    </a>
                    <a class="nav-link" href="{{route('admin.tour-bookings.index')}}">
                        Tour Bookings
                    </a>
                </nav>
            </div>

            <div class="sidenav-menu-heading">Content Management</div>
            <!-- Blog Management -->
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseBlog" aria-expanded="false" aria-controls="collapseBlog">
                <div class="nav-link-icon"><i class="fa-solid fa-blog"></i></div>
                Blog Posts
                <div class="sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseBlog" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav">
            <a class="nav-link" href="{{route('admin.modules.data','blogs')}}">
                Blog List
            </a>
            <a class="nav-link" href="{{route('admin.modules.data.add','blogs')}}">
                Add New Post
            </a>
            <a class="nav-link" href="{{route('admin.modules.data','blog-categories')}}">
                Blog Categories
                    </a>
                </nav>
            </div>

            <!-- Content Management -->
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseContent" aria-expanded="false" aria-controls="collapseContent">
                <div class="nav-link-icon"><i class="fa-solid fa-file-lines"></i></div>
                Pages
                <div class="sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseContent" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav">
                    <a class="nav-link" href="{{route('admin.modules.data','cms-pages')}}">
                        CMS Pages
                    </a>
                    <a class="nav-link" href="{{route('admin.modules.data.add','cms-pages')}}">
                        Add New Page
                    </a>
                    <a class="nav-link" href="{{route('admin.modules.data','services')}}">
                        Services
                    </a>
                    <a class="nav-link" href="{{route('admin.modules.data.add','services')}}">
                        Add New Service
                    </a>
                </nav>
            </div>


            <!-- Team Management -->
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseTeam" aria-expanded="false" aria-controls="collapseTeam">
                <div class="nav-link-icon"><i class="fa-solid fa-user-tie"></i></div>
                Our Team
                <div class="sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseTeam" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav">
                    <a class="nav-link" href="{{route('admin.modules.data','customer-support-team')}}">
                        Team List
            </a>
            <a class="nav-link" href="{{route('admin.modules.data.add','customer-support-team')}}">
                Add New Team
            </a>
                </nav>
            </div>

            

            <!-- Location Management -->
            <div class="sidenav-menu-heading">Location Management</div>
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseCountry" aria-expanded="false" aria-controls="collapseCountry">
                <div class="nav-link-icon"><i class="fa-solid fa-globe"></i></div>
                Country
                <div class="sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseCountry" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav">
                    <a class="nav-link" href="{{route('admin.countries.index')}}">
                        Countries
                    </a>
                    <a class="nav-link" href="{{route('admin.countries.create')}}">
                        Add New Country
                    </a>
                </nav>
            </div>

            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseState" aria-expanded="false" aria-controls="collapseState">
                <div class="nav-link-icon"><i class="fa-solid fa-globe"></i></div>
                State
                <div class="sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseState" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav">                   
                    <a class="nav-link" href="{{route('admin.states.index')}}">
                        States/Provinces
                    </a>
                    <a class="nav-link" href="{{route('admin.states.create')}}">
                        Add New State
                    </a>
                </nav>
            </div>


            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseCity" aria-expanded="false" aria-controls="collapseCity">
                <div class="nav-link-icon"><i class="fa-solid fa-globe"></i></div>
                City
                <div class="sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseCity" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav">
                    
                    <a class="nav-link" href="{{route('admin.cities.index')}}">
                        Cities
                    </a>                   
                    <a class="nav-link" href="{{route('admin.cities.create')}}">
                        Add New City
                    </a>
                </nav>
            </div>






            <!-- Language Management -->
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseLanguage" aria-expanded="false" aria-controls="collapseLanguage">
                <div class="nav-link-icon"><i class="fa-solid fa-language"></i></div>
                Languages
                <div class="sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLanguage" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav">
                    <a class="nav-link" href="{{route('admin.languages.index')}}">
                        List Languages
                    </a>
                </nav>
            </div>

            <div class="sidenav-menu-heading">Other Management</div>

            <!-- Other Management -->
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseMenus" aria-expanded="false" aria-controls="collapseMenus">
                <div class="nav-link-icon"><i class="fa-solid fa-list"></i></div>
                Menus Management
                <div class="sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseMenus" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav">
                    <a class="nav-link" href="{{url('admin/menus')}}">
                        All Menus
                    </a>
                    <a class="nav-link" href="{{route('admin.menus.translations')}}">
                        Menu Translations
                    </a>
                </nav>
            </div>
            <a class="nav-link" href="{{route('admin.modules.data','testimonials')}}">
                <div class="nav-link-icon"><i class="fa-solid fa-star"></i></div>
                Testimonials
            </a>
            <a class="nav-link" href="{{route('admin.contact-us-messages')}}">
                <div class="nav-link-icon"><i class="fa-solid fa-envelope"></i></div>
                Contact Messages
            </a>

            <div class="sidenav-menu-heading">Translations</div>
            <!-- Content Translations -->
            <a class="nav-link" href="{{ route('admin.content.translations.index') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-language"></i></div>
                Content Translations
            </a>
            <!-- Widget Translations -->
            <a class="nav-link" href="{{ route('admin.widget.translations.index') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-puzzle-piece"></i></div>
                Widget Translations
            </a>

            <div class="sidenav-menu-heading">Settings</div>
            <!-- Settings -->
            <?php 
          $w_pages = App\Models\WidgetPages::where('status','active')->get();
          ?>
          @if(null!==($w_pages))
                    <a class="nav-link" href="{{route('admin.currency-settings.index')}}">
                        <div class="nav-link-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
                        Currency Settings
                    </a>
                    @foreach($w_pages as $w_p)
                    <a class="nav-link" href="{{route('admin.widgets_data',$w_p->slug)}}">
                    <div class="nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                        {{$w_p->title}}
                    </a>
                    @endforeach 
            @endif
            
        </div>
    </div>
</nav>
