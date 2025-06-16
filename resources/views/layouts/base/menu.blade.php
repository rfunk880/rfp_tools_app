<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ url('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('img/InnerLogo.png') }}" height="45">
            </span>
        </a>
         <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
              <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
            </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-header small text-uppercase"></li>
        <li class="menu-item {{ request()->is('webpanel/dashboard') ? 'active' : '' }}">
            <a href="{{ url('webpanel/dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-dashboard"></i>
                <div>Dashboard</div>
            </a>
        </li>

      
            <li class="menu-item {{ request()->is('webpanel/companies*') ? 'active' : '' }}">
                <a href="{{ sysRoute('companies.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-address-book"></i>
                    <div>Contacts</div>
                </a>
            </li>
      

        {{-- @can(\App\Module::PROJECTS_VIEW) --}}
        <li class="menu-item {{ request()->is('webpanel/projects*') ? 'active' : '' }}">
            <a href="{{ sysRoute('projects.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-calendar"></i>
                <div>Projects</div>
            </a>
        </li>
        {{-- @endcan --}}


        <li class="menu-item {{ request()->is('webpanel/reports*') ? 'active' : '' }}">
            <a href="javascript:void(0);"
               class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-chart-pie"></i>
                <div>Reports</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item active">
                    <a href="{{ sysRoute('reports.bid') }}"
                       class="menu-link">
                        <div>Bid Report</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ sysRoute('reports.facility') }}"
                       class="menu-link">
                        <div>Facility Report</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ sysRoute('reports.coverage') }}"
                    class="menu-link">
                    <div>Coverage Report</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ sysRoute('reports.sales') }}"
                   class="menu-link">
                    <div>Sales Person Report</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ sysRoute('reports.meetings') }}"
                   class="menu-link">
                    <div>Meeting Report</div>
                </a>
            </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->is('webpanel/messages*') ? 'active' : '' }}">
            <a href="{{ url('webpanel/messages') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div>Messaging</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('webpanel/calllogs*') ? 'active' : '' }}">
            <a href="{{ url('webpanel/calllogs') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-phone"></i>
                <div>Call Logs</div>
            </a>
        </li>

        @if(isManagement())
            <li class="menu-item {{ request()->is('webpanel/facilities*') ? 'active' : '' }}">
                <a href="{{ sysRoute('facilities.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-tags"></i>
                    <div>Facilities & Tags</div>
                </a>
            </li>
            
             <li class="menu-item {{ request()->is('webpanel/users*') ? 'active' : '' }}">
                <a href="{{ sysRoute('users.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div>User Management</div>
                </a>
            </li>
        @endif

        <li class="menu-item">
            <a href="{{ route('clear.logout') }}" class="menu-link" onclick="event.preventDefault(); document.getElementById('menu-logout-form').submit();">
                <i class="menu-icon tf-icons ti ti-logout"></i>
                <div>Log Out</div>
            </a>
            <form id="menu-logout-form"
                  action="{{ route('clear.logout') }}"
                  method="POST"
                  class="d-none">
                @csrf
            </form>
        </li>
    </ul>

</aside>
