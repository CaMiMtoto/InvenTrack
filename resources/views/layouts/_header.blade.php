<div id="kt_app_header" class="app-header d-flex flex-column flex-stack">
    <!--begin::Header main-->
    <div class="d-flex align-items-center flex-stack flex-grow-1">
        <div class="app-header-logo d-flex align-items-center flex-stack px-lg-11 mb-2" id="kt_app_header_logo">
            <!--begin::Sidebar mobile toggle-->
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px ms-3 me-2 d-flex d-lg-none"
                 id="kt_app_sidebar_mobile_toggle">
                <i class="ki-duotone ki-abstract-14 fs-2">
                    <x-lucide-square-arrow-left class="tw-w-8 tw-h-8"/>
                </i>
            </div>
            <!--end::Sidebar mobile toggle-->
            <!--begin::Logo-->
            <a href="" class="app-sidebar-logo">
                <img alt="Logo" src="{{ asset('assets/media/logos/logo.png') }}"
                     class="tw-h-16  tw-border-primary  theme-light-show"/>
                <img alt="Logo" src="{{ asset('assets/media/logos/logo.png') }}"
                     class="tw-h-16  tw-border-primary theme-dark-show"/>
            </a>
            <!--end::Logo-->
            <!--begin::Sidebar toggle-->
            <div id="kt_app_sidebar_toggle"
                 class="app-sidebar-toggle btn btn-sm btn-icon btn-color-primary me-n2 d-none d-lg-flex"
                 data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
                 data-kt-toggle-name="app-sidebar-minimize">
                <i class="ki-duotone ki-exit-left fs-2x rotate-180">
                    <x-lucide-square-arrow-left class="tw-w-8 tw-h-8"/>

                </i>
            </div>
            <!--end::Sidebar toggle-->
        </div>
        <!--begin::Navbar-->
        <div class="app-navbar  flex-grow-1 justify-content-end" id="kt_app_header_navbar">
            <div class="app-navbar-item  d-none d-lg-flex align-items-stretch flex-lg-grow-1 me-2 me-lg-0">
                <div class="container-fluid">
                    <h2 class="fw-bolder">
                        {{ config('app.name') }}
                    </h2>
                    <p>
                        {!! $pageDescription??'' !!}
                    </p>
                </div>
            </div>

            @auth
                <!--begin::User menu-->
                <div class="app-navbar-item ms-3 ms-lg-4 m-2" id="kt_header_user_menu_toggle">
                    <!--begin::Menu wrapper-->
                    <div class="cursor-pointer symbol symbol-30px symbol-lg-40px"
                         data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                         data-kt-menu-placement="bottom-end">
                        <img src="{{optional( auth()->user())->profilePhotoUrl }}" alt="user" class=" rounded-circle"/>
                    </div>
                    <!--begin::User account menu-->
                    <div
                        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                        data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-50px me-5">
                                    <img alt="Logo" class=" rounded-circle"
                                         src="{{ optional(auth()->user())->profilePhotoUrl }}"/>
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Username-->
                                <div class="d-flex flex-column">
                                    <div class="fw-bold d-flex align-items-center fs-5">
                                        {{ Auth::user()->name }}
                                    </div>
                                    <a href="#"
                                       class="fw-semibold text-muted text-hover-primary fs-7">
                                        {{ Auth::user()->email }}
                                    </a>
                                </div>
                                <!--end::Username-->
                            </div>
                        </div>
                        <!--end::Menu item-->


                        <!--end::Menu item-->
                        <!--begin::Menu separator-->
                        <div class="separator my-2"></div>
                        <!--end::Menu separator-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                             data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                            <a href="#" class="menu-link px-5">
											<span class="menu-title position-relative">Mode
											<span class="ms-5 position-absolute translate-middle-y top-50 end-0">
												<span class="ki-duotone ki-night-day theme-light-show fs-2">
                                                    <x-lucide-sun-moon class="tw-w-6 tw-h-6"/>
												</span>
												<span class="theme-dark-show fs-2">
													    <x-lucide-moon class="tw-w-6 tw-h-6"/>
												</span>
											</span></span>
                            </a>
                            <!--begin::Menu-->
                            <div
                                class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                                data-kt-menu="true" data-kt-element="theme-mode-menu">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3 my-0">
                                    <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                       data-kt-value="light">
                                        <span class="menu-icon" data-kt-element="icon">
                                            <x-lucide-sun class="tw-h-6 tw-w-6"/>
                                        </span>
                                        <span class="menu-title">Light</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3 my-0">
                                    <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                       data-kt-value="dark">
                                        <span class="menu-icon" data-kt-element="icon">
                                          <x-lucide-moon class="tw-h-6 tw-w-6"/>
                                        </span>
                                        <span class="menu-title">Dark</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3 my-0">
                                    <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                       data-kt-value="system">
                                        <span class="menu-icon" data-kt-element="icon">
                                            <x-lucide-sun-moon class="tw-h-6 tw-w-6"/>
                                        </span>
                                        <span class="menu-title">System</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                        </div>
                        <!--end::Menu item-->


                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" class="menu-link px-5"
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    Sign Out
                                </a>
                            </form>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::User account menu-->
                    <!--end::Menu wrapper-->
                </div>
                <!--end::User menu-->
            @endauth

            <!--begin::Action-->
            <!--end::Action-->

        </div>
        <!--end::Navbar-->
    </div>
    <!--end::Header main-->
    <!--begin::Separator-->
    {{--    <div class="app-header-separator"></div>--}}
    <!--end::Separator-->
</div>
