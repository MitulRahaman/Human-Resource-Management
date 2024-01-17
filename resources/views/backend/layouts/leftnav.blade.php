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
                <?php
                $menus = session('user_data')['user_menus'];
                $parentMenus = $menus->filter(function ($menu) {
                    return $menu->parent_menu === null;
                });
                ?>
                @foreach($parentMenus as $pm)
                    @if($pm->title!='Logs')
                    <?php
                    $subMenus = $menus->filter(function ($menu) use($pm) {
                        return $menu->parent_menu === $pm->id;
                    });
                    $isOpen = $subMenus->contains('title', $sub_menu);
                    ?>
                    <li class="nav-main-item {{ $isOpen ? 'open' : '' }}">
                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="">
                            <i class="nav-main-link-icon {{$pm->icon}}"></i>
                            <span class="nav-main-link-name">{{$pm->title}}</span>
                        </a>
                        <ul class="nav-main-submenu">
                            @foreach($subMenus as $sm)
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{ strcasecmp($sub_menu, $sm->title) == 0 ? 'active' : '' }}" href="{{ url($sm->url) }}">
                                        <span class="nav-main-link-name">{{$sm->title}}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    @endif
                @endforeach
                @if($parentMenus->contains('title', 'Logs'))
                <li class="nav-main-item">
                    <a class="nav-main-link {{ strcasecmp($sub_menu, 'Logs') == 0 ? 'active' : '' }}" href="{{ url('log') }}">
                        <i class="nav-main-link-icon far fa-clone"></i>
                        <span class="nav-main-link-name">Logs</span>
                    </a>
                </li>
                @endif


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

            </ul>
        </div>
        <!-- END Side Navigation -->
    </div>
    <!-- END Sidebar Scrolling -->
</nav>

