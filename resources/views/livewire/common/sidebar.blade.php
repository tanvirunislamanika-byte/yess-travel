<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <!-- Sidenav Menu Heading (Account)-->
            <!-- * * Note: * * Visible only on and above the sm breakpoint-->
            <form class="form-inline me-auto d-none d-lg-block mt-2 sidenav-menu-heading ">
                <div class="input-group input-group-joined input-group-solid">
                    <input class="form-control pe-0" type="search" id="menu-search" placeholder="Search" aria-label="Search" />
                    <div class="input-group-text"><i data-feather="search"></i></div>
                </div>
            </form>
            
            <!-- Sidenav Menu Heading (Core)-->
            <div class="sidenav-menu-heading  sidenav-menu-heading-account">Core</div>
            <!-- Sidenav Accordion (Dashboard)-->
            <a class="nav-link collapsed" href="{{route('admin.dashboard')}}" >
                <div class="nav-link-icon"><i data-feather="home"></i></div>
                Dashboards
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a> 

            <?php $adminMenus = frontMenus(); ?>


            @if(null!==($adminMenus))
            @foreach($adminMenus as $key=>$menu)
            <!-- Sidenav Heading (Custom)-->
            <div class="sidenav-menu-heading">{{$key}}</div>

            <!-- Sidenav Accordion (Pages)-->
            @if(null!==($menu))
            @foreach($menu as $ke=>$men)
            <?php $collapse = str_replace(' ', '', $ke); ?>
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapse{{$collapse}}" aria-expanded="false" aria-controls="collapse{{$collapse}}">
                <div class="nav-link-icon"><i data-feather="{{@$men['icon']}}"></i></div>
                {{__($ke)}}
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapse{{$collapse}}" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                    @if(null!==($men))
                    @foreach ($men as $k => $value)
                    @if($k !== 'icon')
                    <a class="nav-link collapsed" href="{{$value}}">
                        {{__($k)}}
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    @endif
                    @endforeach
                    @endif

                </nav>
            </div>
            @endforeach
            @endif
            @endforeach
            @endif


            
           
           
            
            <!-- Sidenav Link (Tables)-->
        </div>
    </div>
    <!-- Sidenav Footer-->
    <!-- <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title">Valerie Luna</div>
        </div>
    </div> -->
</nav>
