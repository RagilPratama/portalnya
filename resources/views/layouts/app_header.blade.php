<!-- begin:: Header -->
<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

    <!-- begin:: Header Menu -->

    <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper" style="padding: 10px; margin-left: -25px; ">

        @auth()

        <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
            <ul class="kt-menu__nav ">
                <li class="kt-menu__item kt-menu__item--submenu kt-menu__item--rel kt-menu__item--active kt-menu__item--open-dropdown kt-menu__item--hover"
                    data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="javascript:;"
                        class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">Beranda</span><i
                            class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <!--<div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
									                
									            </div> -->
                </li>
                <li class="kt-menu__item kt-menu__item--submenu kt-menu__item--rel kt-menu__item--active kt-menu__item--open-dropdown kt-menu__item"
                    data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="javascript:;"
                        class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">Pengaturan</span><i
                            class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
                        <ul class="kt-menu__subnav">
                            <li class="kt-menu__item " aria-haspopup="true"><a href="/metronic/preview/demo1/index.html"
                                    class="kt-menu__link "><span class="kt-menu__link-text">User</span></a>
                            </li>

                            <li class="kt-menu__item " aria-haspopup="true"><a href="javascript:;"
                                    class="kt-menu__link "><span class="kt-menu__link-text">Indikator Proses</span><span
                                        class="kt-menu__link-badge"></span></a>
                            </li>



                        </ul>
                    </div>
                </li>

                <li class="kt-menu__item kt-menu__item--submenu kt-menu__item--rel kt-menu__item--active kt-menu__item--open-dropdown "
                    data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="javascript:;"
                        class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">Monitoring</span><i
                            class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
                        <ul class="kt-menu__subnav">
                            <li class="kt-menu__item " aria-haspopup="true"><a href="javascript:;"
                                    class="kt-menu__link "><span class="kt-menu__link-text">Target</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>


        @endauth()

    </div>

    <!-- end:: Header Menu -->

    <!-- begin:: Header Topbar -->
    <div class="kt-header__topbar">

        <!--begin: User Bar -->
        @guest
        <div class="kt-header__topbar-item kt-header__topbar-item--user">
            <div class="kt-header__topbar-wrapper">
                <div class="kt-header__topbar-user"><a href="{{ route('login') }}" class="btn btn-primary"><i
                            class="fas fa-sign-in-alt"></i> Signin</a>
                </div>
            </div>
        </div>
        @endguest
        @auth
        <!-- Top first Bar  -->



        <!-- <div class="kt-header__topbar-item dropdown">
            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="30px,0px" aria-expanded="true">
                <span class="kt-header__topbar-icon kt-pulse kt-pulse--brand">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"></rect>
                            <path
                                d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z"
                                fill="#000000" opacity="0.3"></path>
                            <path
                                d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.560	66017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z"
                                fill="#000000"></path>
                        </g>
                    </svg> <span class="kt-pulse__ring"></span>
                </span>
            </div>
            <div
                class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-lg">
                <form>
                    <div class="kt-head kt-head--skin-dark kt-head--fit-x kt-head--fit-b"
                        style="background-image: url(/metronic/themes/metronic/theme/default/demo1/dist/assets/media/misc/bg-1.jpg)">
                        <h3 class="kt-head__title">
                            User Notifications
                            &nbsp;
                            <span class="btn btn-success btn-sm btn-bold btn-font-md">1 new</span>
                        </h3>


                    </div>


                    <div class="tab-content">
                        <div class="tab-pane active show" id="topbar_notifications_notifications" role="tabpanel">
                            <div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll ps" data-scroll="true"
                                data-height="300" data-mobile-height="200" style="height: 300px; overflow: hidden;">
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-line-chart kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New order has been received
                                        </div>
                                        <div class="kt-notification__item-time">
                                            2 hrs ago
                                        </div>
                                    </div>
                                </a>

                                <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                                    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                                </div>
                                <div class="ps__rail-y" style="top: 0px; right: 0px;">
                                    <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="topbar_notifications_logs" role="tabpanel">
                            <div class="kt-grid kt-grid--ver" style="min-height: 200px;">
                                <div
                                    class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
                                    <div class="kt-grid__item kt-grid__item--middle kt-align-center">
                                        All caught up!
                                        <br>No new notifications.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div> -->



        <div class="kt-header__topbar-item kt-header__topbar-item--user">
            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">

                <div class="kt-header__topbar-user">
                    <div class="kt-header__topbar-user">
                        <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
                        <span
                            class="kt-header__topbar-username kt-hidden-mobile">{{ currentUser('NamaLengkap') }}</span>
							
                        <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                        <span
                            class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">{{substr(ucfirst(currentUser('NamaLengkap')), 0, 1)}}</span>
                    </div>
                </div>
            </div>
            <div
                class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">


                <!--begin: Navigation -->
                <div class="kt-notification">
                    <a href="{{ url('user/profile') }}" class="kt-notification__item">
                        <div class="kt-notification__item-icon">
                            <i class="flaticon2-calendar-3 kt-font-success"></i>
                        </div>
                        <div class="kt-notification__item-details">
                            <div class="kt-notification__item-title kt-font-bold">
                                My Profile
                            </div>
                        </div>
                    </a>
                    <div class="kt-notification__custom kt-space-between">
                        <a href="{{ url('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                            class="btn btn-label btn-label-brand btn-sm btn-bold">Sign Out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>

                <!--end: Navigation -->
            </div>
        </div>
        <!--------End First Bar ------------>

        <!-- Second TopBar -->

        <div class="kt-subheader   kt-grid__item" id="kt_subheader">
            <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">

                    <h3 class="kt-subheader__title">@hasSection('title') @yield('title') @endif <small>@hasSection('subtitle') @yield('subtitle') @endif</small></h3>

                    <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                    <!--<span class="kt-subheader__desc">#XRS-45670</span>-->

                    <!--<a href="#" class="btn btn-label-primary btn-bold btn-icon-h kt-margin-l-10">
							                Add New
							            </a> -->
                </div>
                <div class="kt-subheader__toolbar">
                    <div class="kt-subheader__wrapper">
                        <a href="#" class="btn kt-subheader__btn-daterange" id="kt_dashboard_daterangepicker"
                            data-toggle="kt-tooltip" title="" data-placement="left"
                            data-original-title="Select dashboard daterange">
                            <span class="kt-subheader__btn-daterange-title"
                                id="kt_dashboard_daterangepicker_title">Tanggal :</span>&nbsp;
                            <span class="kt-subheader__btn-daterange-date" id="kt_dashboard_daterangepicker_date"><?php echo date("j M Y");?></span>
                            <i class="flaticon2-calendar-1"></i>
                        </a>

                        <!--<a href="#" class="btn kt-subheader__btn-primary btn-icon">
							                    <i class="flaticon2-file"></i>
							                </a>

							                <a href="#" class="btn kt-subheader__btn-primary btn-icon">
							                    <i class="flaticon-download-1"></i>
							                </a>

							                <a href="#" class="btn kt-subheader__btn-primary btn-icon">
							                    <i class="flaticon2-fax"></i>
							                </a>

							                <a href="#" class="btn kt-subheader__btn-primary btn-icon">
							                    <i class="flaticon2-settings"></i>
							                </a>-->

                        <!--<div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="" data-placement="left" data-original-title="Quick actions">
							                    <a href="#" class="btn btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success kt-svg-icon--md">
							    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
							        <polygon points="0 0 24 0 24 24 0 24"></polygon>
							        <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
							        <path d="M11,14 L9,14 C8.44771525,14 8,13.5522847 8,13 C8,12.4477153 8.44771525,12 9,12 L11,12 L11,10 C11,9.44771525 11.4477153,9 12,9 C12.5522847,9 13,9.44771525 13,10 L13,12 L15,12 C15.5522847,12 16,12.4477153 16,13 C16,13.5522847 15.5522847,14 15,14 L13,14 L13,16 C13,16.5522847 12.5522847,17 12,17 C11.4477153,17 11,16.5522847 11,16 L11,14 Z" fill="#000000"></path>
							    </g>
							</svg>                        <!--<i class="flaticon2-plus"></i>-->
                        <!--</a>
							                    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-md dropdown-menu-right">
							                        <!--begin::Nav-->
                        <!--<ul class="kt-nav">
							                            <li class="kt-nav__head">
							                                Export Options:                                    
							                                <i class="flaticon2-correct kt-font-warning" data-toggle="kt-tooltip" data-placement="right" title="" data-original-title="Click to learn more..."></i>
							                            </li>
							                            <li class="kt-nav__separator"></li>
							                            <!--<li class="kt-nav__item">
							                                <a href="#" class="kt-nav__link">
							                                    <i class="kt-nav__link-icon flaticon2-drop"></i>
							                                    <span class="kt-nav__link-text">Orders</span>
							                                </a>
							                            </li>                        
							                            <li class="kt-nav__item">
							                                <a href="#" class="kt-nav__link">
							                                    <i class="kt-nav__link-icon flaticon2-new-email"></i>
							                                    <span class="kt-nav__link-text">Members</span>
							                                    <span class="kt-nav__link-badge">
							                                        <span class="kt-badge kt-badge--brand kt-badge--rounded">15</span>
							                                    </span>
							                                </a>
							                            </li>
							                            <li class="kt-nav__item">
							                                <a href="#" class="kt-nav__link">
							                                    <i class="kt-nav__link-icon flaticon2-calendar-8"></i>
							                                    <span class="kt-nav__link-text">Reports</span>
							                                </a>
							                            </li>
							                            <li class="kt-nav__item">
							                                <a href="#" class="kt-nav__link">
							                                    <i class="kt-nav__link-icon flaticon2-link"></i>
							                                    <span class="kt-nav__link-text">Finance</span>
							                                </a>
							                            </li> -->
                        <!--<li class="kt-nav__separator"></li>
							                            <li class="kt-nav__foot">
							                                <a class="btn btn-label-brand btn-bold btn-sm" href="#">More options</a>                                    
							                                <a class="btn btn-clean btn-bold btn-sm kt-hidden" href="#" data-toggle="kt-tooltip" data-placement="right" title="" data-original-title="Click to learn more...">Learn more</a>
							                            </li>
							                        </ul>
							                        <!--end::Nav-->
                        <!--</div>
							                </div>-->
                    </div>
                </div>
            </div>
        </div>

        <!-- ------------------>


        @endauth
        <!--end: User Bar -->
    </div>

    <!-- end:: Header Topbar -->
</div>

<!-- end:: Header -->
