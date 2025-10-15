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
                        {!! $pageDescription??'Initiative  Business Expertise' !!}
                    </p>
                </div>
            </div>

            @auth
           {{--     <div class="d-flex align-items-center ms-1 ms-lg-3">
                    <!--begin::Menu wrapper-->
                    <div
                        class="btn btn-icon btn-active-light-primary position-relative w-30px h-30px w-md-40px h-md-40px"
                        data-bs-toggle="tooltip" title="" data-bs-html="true" data-bs-placement="bottom"
                        id="kt_drawer_chat_toggle"
                        data-bs-original-title="Available in &lt;span class='badge badge-pro badge-light-danger fw-bold fs-9 px-2 py-1 ms-1'&gt;Pro&lt;/span&gt; version">
                        <!--begin::Svg Icon | path: icons/duotone/Communication/Group-chat.svg-->
                        <span class="svg-icon svg-icon-1">
											<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                                 viewBox="0 0 24 24" version="1.1">
												<path
                                                    d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z"
                                                    fill="#000000"></path>
												<path
                                                    d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z"
                                                    fill="#000000" opacity="0.3"></path>
											</svg>
										</span>
                        <!--end::Svg Icon-->
                        <span
                            class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink"></span>
                    </div>
                    <!--end::Menu wrapper-->
                </div>--}}
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
