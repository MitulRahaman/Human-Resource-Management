<nav id="sidebar" aria-label="Main Navigation">
    <!-- Side Header -->
    <div class="content-header bg-white-5">
        <!-- Logo -->
        <a class="font-w600 text-dual" href="#">
                        <span class="smini-visible">
                            <i class="fa fa-circle-notch text-primary"></i>
                        </span>
            <span class="smini-hide font-size-h5 tracking-wider">
                            App<span class="font-w400">Nap</span>
                        </span>
        </a>
        <!-- END Logo -->

        <!-- Extra -->
        <div>
            <!-- Options -->
            <div class="dropdown d-inline-block ml-2">
                <a class="btn btn-sm btn-dual" id="sidebar-themes-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="si si-drop"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right font-size-sm smini-hide border-0" aria-labelledby="sidebar-themes-dropdown">
                    <!-- Color Themes -->
                    <!-- Layout API, functionality initialized in Template._uiHandleTheme() -->
                    <a class="dropdown-item d-flex align-items-center justify-content-between font-w500" data-toggle="theme" data-theme="default" href="#">
                        <span>Default</span>
                        <i class="fa fa-circle text-default"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between font-w500" data-toggle="theme" data-theme="backend/css/themes/amethyst.min.css" href="#">
                        <span>Amethyst</span>
                        <i class="fa fa-circle text-amethyst"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between font-w500" data-toggle="theme" data-theme="backend/css/themes/city.min.css" href="#">
                        <span>City</span>
                        <i class="fa fa-circle text-city"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between font-w500" data-toggle="theme" data-theme="backend/css/themes/flat.min.css" href="#">
                        <span>Flat</span>
                        <i class="fa fa-circle text-flat"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between font-w500" data-toggle="theme" data-theme="backend/css/themes/modern.min.css" href="#">
                        <span>Modern</span>
                        <i class="fa fa-circle text-modern"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between font-w500" data-toggle="theme" data-theme="backend/css/themes/smooth.min.css" href="#">
                        <span>Smooth</span>
                        <i class="fa fa-circle text-smooth"></i>
                    </a>
                    <!-- END Color Themes -->

                    <div class="dropdown-divider"></div>

                    <!-- Sidebar Styles -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <a class="dropdown-item font-w500" data-toggle="layout" data-action="sidebar_style_light" href="#">
                        <span>Sidebar Light</span>
                    </a>
                    <a class="dropdown-item font-w500" data-toggle="layout" data-action="sidebar_style_dark" href="#">
                        <span>Sidebar Dark</span>
                    </a>
                    <!-- Sidebar Styles -->

                    <div class="dropdown-divider"></div>

                    <!-- Header Styles -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <a class="dropdown-item font-w500" data-toggle="layout" data-action="header_style_light" href="#">
                        <span>Header Light</span>
                    </a>
                    <a class="dropdown-item font-w500" data-toggle="layout" data-action="header_style_dark" href="#">
                        <span>Header Dark</span>
                    </a>
                    <!-- Header Styles -->
                </div>
            </div>
            <!-- END Options -->

            <!-- Close Sidebar, Visible only on mobile screens -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <a class="d-lg-none btn btn-sm btn-dual ml-1" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
                <i class="fa fa-fw fa-times"></i>
            </a>
            <!-- END Close Sidebar -->
        </div>
        <!-- END Extra -->
    </div>
    <!-- END Side Header -->

    <!-- Sidebar Scrolling -->
    <div class="js-sidebar-scroll">
        <!-- Side Navigation -->
        <div class="content-side">
            <ul class="nav-main">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ strcasecmp($main_menu, 'Dashboard') == 0 ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="nav-main-link-icon si si-speedometer"></i>
                        <span class="nav-main-link-name">Dashboard</span>
                    </a>
                </li>
                <li class="nav-main-heading">User Interface</li>
                <li class="nav-main-item {{ (strcasecmp($sub_menu, 'Assets Type') == 0 || strcasecmp($sub_menu, 'Banks') == 0 || strcasecmp($sub_menu, 'Institutes') == 0 || strcasecmp($sub_menu, 'Branches') == 0 || strcasecmp($sub_menu, 'Degree') == 0 || strcasecmp($sub_menu, 'Calender') == 0 || strcasecmp($sub_menu, 'Designations') == 0 || strcasecmp($sub_menu, 'Departments') == 0 || strcasecmp($sub_menu, 'Leaves') == 0 || strcasecmp($sub_menu, 'Roles') == 0) ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon si si-settings"></i>
                        <span class="nav-main-link-name">System Settings</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Assets Type') == 0 ? 'active' : '' }}" href="{{ url('assetsType') }}">
                                <span class="nav-main-link-name">Assets Type</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Banks') == 0 ? 'active' : '' }}" href="{{ url('bank') }}">
                                <span class="nav-main-link-name">Banks</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Branches') == 0 ? 'active' : '' }}" href="{{ url('branch') }}">
                                <span class="nav-main-link-name">Branches</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Calender') == 0 ? 'active' : '' }}" href="{{ url('calender') }}">
                                <span class="nav-main-link-name">Calender</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Degree') == 0 ? 'active' : '' }}" href="{{ url('degree') }}">
                                <span class="nav-main-link-name">Degree</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Departments') == 0 ? 'active' : '' }}" href="{{ url('department') }}">
                                <span class="nav-main-link-name">Departments</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Designations') == 0 ? 'active' : '' }}" href="{{ url('designation') }}">
                                <span class="nav-main-link-name">Designations</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Institutes') == 0 ? 'active' : '' }}" href="{{ url('institute') }}">
                                <span class="nav-main-link-name">Institutes</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Leaves') == 0 ? 'active' : '' }}" href="{{ url('leave') }}">
                                <span class="nav-main-link-name">Leaves</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Roles') == 0 ? 'active' : '' }}" href="{{ url('role') }}">
                                <span class="nav-main-link-name">Roles</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-main-item {{ (strcasecmp($sub_menu, 'Add User') == 0 || strcasecmp($sub_menu, 'Manage Users') == 0) ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon si si-users"></i>
                        <span class="nav-main-link-name">Users</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Add User') == 0 ? 'active' : '' }}" href="{{ url('user/create') }}">
                                <span class="nav-main-link-name">Add User</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Manage Users') == 0 ? 'active' : '' }}" href="{{ url('user/manage') }}">
                                <span class="nav-main-link-name">Manage Users</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-main-item {{ (strcasecmp($sub_menu, 'Add Asset') == 0 || strcasecmp($sub_menu, 'User Assets') == 0 || strcasecmp($sub_menu, 'Manage Assets') == 0) ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon si si-grid"></i>
                        <span class="nav-main-link-name">Assets</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Add Asset') == 0 ? 'active' : '' }}" href="{{ url('asset/add') }}">
                                <span class="nav-main-link-name">Add Asset</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'User Assets') == 0 ? 'active' : '' }}" href="{{ url('asset/user_assets') }}">
                                <span class="nav-main-link-name">User Assets</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Manage Assets') == 0 ? 'active' : '' }}" href="{{ url('asset/') }}">
                                <span class="nav-main-link-name">Manage Assets</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-main-item {{ (strcasecmp($sub_menu, 'Request') == 0 || strcasecmp($sub_menu, 'Manage') == 0) ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon far  fa-plus-square"></i>
                        <span class="nav-main-link-name">Requisition</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Request') == 0 ? 'active' : '' }}" href="{{ url('requisition/request' )}}">
                                <span class="nav-main-link-name">Request</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Manage') == 0 ? 'active' : '' }}" href="{{ url('requisition/' )}}">
                                <span class="nav-main-link-name">Manage</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-main-item {{ (strcasecmp($sub_menu, 'Apply Leave') == 0 || strcasecmp($sub_menu, 'Manage Leaves') == 0) ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="nav-main-link-icon si si-note"></i>
                        <span class="nav-main-link-name">Apply for Leave</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Apply Leave') == 0 ? 'active' : '' }}" href="{{ url('leaveApply/apply' )}}">
                                <span class="nav-main-link-name">Apply Leave</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Manage Leaves') == 0 ? 'active' : '' }}" href="{{ url('leaveApply/manage' )}}">
                                <span class="nav-main-link-name">Manage Leaves</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-main-item {{ (strcasecmp($sub_menu, 'Add Tickets') == 0 || strcasecmp($sub_menu, 'Manage Tickets') == 0) ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon fa fa-tasks"></i>
                        <span class="nav-main-link-name">Tickets</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Add Tickets') == 0 ? 'active' : '' }}" href="{{ url('ticket/add' )}}">
                                <span class="nav-main-link-name">Add</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ strcasecmp($sub_menu, 'Manage Tickets') == 0 ? 'active' : '' }}" href="{{ url('ticket/' )}}">
                                <span class="nav-main-link-name">Manage Tickets</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-main-heading">Admin Console</li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ strcasecmp($sub_menu, 'Menus') == 0 ? 'active' : '' }}" href="{{ url('menu') }}">
                        <span class="nav-main-link-name">Menu</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ strcasecmp($sub_menu, 'Permissions') == 0 ? 'active' : '' }}" href="{{ url('permission') }}">
                        <span class="nav-main-link-name">Permissions</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ strcasecmp($sub_menu, 'Logs') == 0 ? 'active' : '' }}" href="{{ url('log') }}">
                        <span class="nav-main-link-name">Logs</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- END Side Navigation -->
    </div>
    <!-- END Sidebar Scrolling -->
</nav>
