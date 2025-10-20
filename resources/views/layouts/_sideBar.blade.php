@php use App\Constants\Permission; @endphp
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true"
     data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
     data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start"
     data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Main-->
    <div
        class="d-flex flex-column justify-content-between h-100 hover-scroll-overlay-y my-2 d-flex flex-column"
        id="kt_app_sidebar_main" data-kt-scroll="true" data-kt-scroll-activate="true"
        data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_header"
        data-kt-scroll-wrappers="#kt_app_main" data-kt-scroll-offset="5px">
        <!--begin::Sidebar menu-->
        <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
             class="flex-column-fluid menu menu-sub-indention menu-column menu-rounded menu-active-bg mb-7">

            <div class="menu-item ">
                <a href="{{ route('admin.dashboard') }}"
                   class="menu-link {{ request()->fullUrl() ==route('admin.dashboard')?'active':'' }}">
                    <div class="menu-icon">
                        <x-lucide-gauge class="tw-w-6 tw-h-6"/>
                    </div>
                    <span class="menu-title">Dashboard</span>
                </a>
            </div>
            @can(Permission::VIEW_PRODUCT_CATALOG)
                <div class="menu-item ">
                    <a href="{{ route('admin.products.catalog') }}"
                       class="menu-link {{ Str::of(request()->url())->contains('admin/products/catalog')?'active':'' }}">
                        <div class="menu-icon">
                            <x-lucide-list-checks class="tw-w-6 tw-h-6"/>
                        </div>
                        <span class="menu-title">
                        Product Catalog
                    </span>
                    </a>
                </div>
            @endcan


            @canany([Permission::ManageSalesOrders()])
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{ Str::of(request()->url())->contains('/admin/orders')?'show':'' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                           <x-lucide-shopping-bag class="tw-w-6 tw-h-6"/>
                        </span>
                        <span class="menu-title">
                           Orders
                        </span>
                    <span class="menu-arrow"></span>
                </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        @can(Permission::APPROVE_ORDERS)
                            <a class="menu-link {{ request()->fullUrl()==route('admin.orders.index',['status'=>'pending'])?'active':'' }}"
                               href="{{ route('admin.orders.index',['status'=>'pending']) }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Pending Orders</span>
                            </a>
                        @endcan
                        @can(Permission::ASSIGN_DELIVERY)
                            <a class="menu-link {{ request()->fullUrl()==route('admin.orders.index',['status'=>'approved'])?'active':'' }}"
                               href="{{ route('admin.orders.index',['status'=>'approved']) }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Approved Orders</span>
                            </a>
                        @endcan
                        @can(Permission::ASSIGN_DELIVERY)
                            <a class="menu-link {{ request()->fullUrl()==route('admin.orders.index',['status'=>\App\Constants\Status::Delivered])?'active':'' }}"
                               href="{{ route('admin.orders.index',['status'=>\App\Constants\Status::Delivered]) }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Delivered Orders</span>
                            </a>
                        @endcan

                        <a class="menu-link {{ request()->fullUrl()==route('admin.orders.index')?'active':'' }}"
                           href="{{ route('admin.orders.index',['start_date'=>now()->addDays(-7)->format('Y-m-d'), 'end_date'=>date('Y-m-d')]) }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">All Orders</span>
                        </a>
                        <!--end:Menu link-->

                    </div>
                    <!--end:Menu item-->
                </div>
            @endcanany

            @canany(Permission::ManageOrderDeliveries())
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{ Str::of(request()->url())->contains('/admin/deliveries')?'show':'' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                           <x-lucide-bike class="tw-w-6 tw-h-6"/>
                        </span>
                        <span class="menu-title">
                           Delivery
                        </span>
                    <span class="menu-arrow"></span>
                </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        @can(Permission::NEW_ORDER)
                            <a class="menu-link {{ request()->url()==route('admin.deliveries.pending')?'active':'' }}"
                               href="{{ route('admin.deliveries.pending') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Pending Deliveries</span>
                            </a>
                        @endcan
                        @can(Permission::DELIVER_PRODUCTS)
                            <a class="menu-link {{ request()->fullUrl()==route('admin.deliveries.assigned-to-me')?'active':'' }}"
                               href="{{ route('admin.deliveries.assigned-to-me') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">My Deliveries</span>
                            </a>
                        @endcan


                        <a class="menu-link {{ request()->fullUrl()==route('admin.deliveries.index')?'active':'' }}"
                           href="{{ route('admin.deliveries.index') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">All Deliveries</span>
                        </a>
                        <!--end:Menu link-->

                    </div>
                    <!--end:Menu item-->
                </div>
            @endcanany
            @canany(Permission::ManageOrderReturns())
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{ Str::of(request()->url())->contains('/admin/returns')?'show':'' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                           <x-lucide-history class="tw-w-6 tw-h-6"/>
                        </span>
                        <span class="menu-title">
                           Returns
                        </span>
                    <span class="menu-arrow"></span>
                </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        @can(Permission::APPROVE_RETURNED_ITEMS)
                            <a class="menu-link {{ request()->url()==route('admin.returns.index')?'active':'' }}"
                               href="{{ route('admin.returns.index',['status'=>'pending']) }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Pending Deliveries</span>
                            </a>
                        @endcan

                        <a class="menu-link {{ request()->fullUrl()==route('admin.returns.index')?'active':'' }}"
                           href="{{ route('admin.returns.index') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">All Returns</span>
                        </a>
                        <!--end:Menu link-->

                    </div>
                    <!--end:Menu item-->
                </div>
            @endcanany
            @canany(Permission::ManageOrderPayments())
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{ Str::of(request()->url())->contains('/admin/payments')?'show':'' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                           <x-lucide-wallet-cards class="tw-w-6 tw-h-6"/>
                        </span>
                        <span class="menu-title">
                           Payments
                        </span>
                    <span class="menu-arrow"></span>
                </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        @can(Permission::ADD_ORDER_PAYMENT)
                            <a class="menu-link {{ request()->url()==route('admin.payments.create')?'active':'' }}"
                               href="{{ route('admin.payments.create') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">New Payment</span>
                            </a>
                        @endcan
                        @can(Permission::VIEW_SALES_PAYMENTS)
                            <a class="menu-link {{ request()->fullUrl()==route('admin.payments.index')?'active':'' }}"
                               href="{{ route('admin.payments.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Order Payments</span>
                            </a>
                        @endcan


                    </div>
                    <!--end:Menu item-->
                </div>
            @endcanany
            @canany(Permission::managePurchaseOrders())
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{ Str::of(request()->url())->contains('/admin/purchase-orders')?'show':'' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                           <x-lucide-ticket-check class="tw-w-6 tw-h-6"/>
                        </span>
                        <span class="menu-title">
                           Purchase
                        </span>
                    <span class="menu-arrow"></span>
                </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        @can(Permission::ADD_PURCHASE)
                            <a class="menu-link {{ request()->url()==route('admin.purchases.create')?'active':'' }}"
                               href="{{ route('admin.purchases.create') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">New Purchase</span>
                            </a>
                        @endcan

                        <a class="menu-link {{ request()->url()==route('admin.purchases.index')?'active':'' }}"
                           href="{{ route('admin.purchases.index') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">All Purchases</span>
                        </a>

                    </div>
                    <!--end:Menu item-->
                </div>
            @endcanany
            @canany(Permission::ManageProducts())
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{ Str::of(request()->fullUrl())->contains('/admin/products')?'show':'' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                        <x-lucide-scan-barcode class="tw-w-6 tw-h-6"/>
                        </span>
                        <span class="menu-title">
                           Products Management
                        </span>
                    <span class="menu-arrow"></span>
                </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">

                        <a class="menu-link {{ request()->url()==route('admin.products.categories.index')?'active':'' }}"
                           href="{{ route('admin.products.categories.index') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Categories</span>
                        </a>
                        <!--end:Menu link-->

                        <a class="menu-link {{ request()->url()==route('admin.products.index')?'active':'' }}"
                           href="{{ route('admin.products.index') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Products</span>
                        </a>
                        <!--end:Menu link-->

                    </div>
                    <!--end:Menu item-->
                </div>
            @endcanany
            @canany(Permission::ManageStock())
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{ Str::of(request()->url())->contains('/admin/stock')?'show':'' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                          <x-lucide-activity class="tw-w-6 tw-h-6"/>
                        </span>
                        <span class="menu-title">
                           Stock Management
                        </span>
                    <span class="menu-arrow"></span>
                </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">

                        @can(Permission::REQUEST_STOCK_ADJUSTMENT)
                            <a class="menu-link {{ request()->url()==route('admin.stock-adjustments.create')?'active':'' }}"
                               href="{{ route('admin.stock-adjustments.create') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">New Adjustment</span>
                            </a>
                        @endcan
                        @canany([Permission::VIEW_STOCK_ADJUSTMENT,Permission::APPROVE_STOCK_ADJUSTMENT,Permission::REQUEST_STOCK_ADJUSTMENT])
                            <a class="menu-link {{ request()->url()==route('admin.stock-adjustments.index')?'active':'' }}"
                               href="{{ route('admin.stock-adjustments.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Adjustments</span>
                            </a>
                        @endcanany
                        <!--end:Menu link-->
                        @canany([Permission::VIEW_STOCK_MOVEMENT])
                            <a class="menu-link {{ request()->url()==route('admin.stock-adjustments.index')?'active':'' }}"
                               href="{{ route('admin.stock-adjustments.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Movements</span>
                            </a>
                            <!--end:Menu link-->
                        @endcanany
                    </div>
                    <!--end:Menu item-->
                </div>
            @endcanany
            @can(Permission::MANAGE_EXPENSES)
                <div class="menu-item here">
                    <!--begin:Menu link-->
                    <a href="{{ route('admin.expenses.index') }}"
                       class="menu-link {{ request()->fullUrl() ==route('admin.expenses.index')?'active':'' }}">
                        <div class="menu-icon">
                            {{--                        <i class="bi bi-speedometer2 fs-2"></i>--}}
                            <x-lucide-trending-up class="tw-w-6 tw-h-6"/>
                        </div>
                        <span class="menu-title">Expenses</span>
                    </a>
                    <!--end:Menu link-->
                </div>
            @endcan
            @canany(Permission::ManageSettings())
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{ Str::of(request()->url())->contains('/admin/settings')?'show':'' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                    <span class="menu-icon">
                       <x-lucide-settings class="tw-w-6 tw-h-6"/>
                    </span>
                    <span class="menu-title">
                       System Settings
                    </span>
                <span class="menu-arrow"></span>
            </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        @can(Permission::MANAGE_SUPPLIERS)
                            <a class="menu-link {{ request()->url()==route('admin.settings.suppliers.index')?'active':'' }}"
                               href="{{ route('admin.settings.suppliers.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Suppliers</span>
                            </a>
                        @endcan
                        @can(Permission::MANAGE_CUSTOMERS)
                            <a class="menu-link {{ request()->url()==route('admin.settings.customers.index')?'active':'' }}"
                               href="{{ route('admin.settings.customers.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Customers</span>
                            </a>
                        @endcan
                        @can(Permission::MANAGE_PAYMENT_METHODS)
                            <a class="menu-link {{ request()->url()==route('admin.settings.payment-methods.index')?'active':'' }}"
                               href="{{ route('admin.settings.payment-methods.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Payment Methods</span>
                            </a>
                        @endcan
                        @can(Permission::MANAGE_EXPENSE_CATEGORIES)
                            <a class="menu-link {{ request()->url()==route('admin.settings.expense-categories.index')?'active':'' }}"
                               href="{{ route('admin.settings.expense-categories.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Expense Categories</span>
                            </a>
                        @endcan

                    </div>
                    <!--end:Menu item-->
                </div>
            @endcanany
            @canany([Permission::MANAGE_ROLES,Permission::MANAGE_PERMISSIONS,Permission::MANAGE_USERS])
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{ Str::of(request()->url())->contains('/admin/system')?'show':'' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                         <x-lucide-users class="tw-w-6 tw-h-6"/>
                        </span>
                        <span class="menu-title">
                            User Management
                        </span>
                    <span class="menu-arrow"></span>
                </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">

                        <!--begin:Menu item-->
                        <!--begin:Menu link-->
                        @can(Permission::MANAGE_USERS)
                            <a class="menu-link {{ request()->url()==route('admin.system.users.index')?'active':'' }}"
                               href="{{ route('admin.system.users.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Users</span>
                            </a>
                        @endcan
                        <!--end:Menu link-->
                        <!--begin:Menu link-->
                        @can(Permission::MANAGE_ROLES)
                            <a class="menu-link  {{ request()->url()==route('admin.system.roles.index')?'active':'' }}"
                               href="{{ route('admin.system.roles.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Roles</span>
                            </a>
                            <!--end:Menu link-->
                        @endcan

                        @can(Permission::MANAGE_PERMISSIONS)
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->url()==route('admin.system.permissions.index')?'active':'' }}"

                               href="{{ route('admin.system.permissions.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Permissions</span>
                            </a>
                            <!--end:Menu link-->
                        @endcan

                    </div>
                    <!--end:Menu item-->
                </div>
            @endcanany
            <div data-kt-menu-trigger="click"
                 class="menu-item menu-accordion {{ Str::of(request()->url())->contains('/admin/reports')?'show':'' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                           <span class="menu-icon">
                              <x-lucide-printer class="tw-h-6 tw-w-6"/>
                           </span>
                           <span class="menu-title">
                               Reports
                           </span>
                       <span class="menu-arrow"></span>
                   </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <a class="menu-link {{ \Illuminate\Support\Str::of(request()->url())==route('admin.reports.list')?'active':'' }}"
                       href="{{ route('admin.reports.list') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">List</span>
                    </a>

                    <!--begin:Menu item-->
                    @can(Permission::VIEW_SALES_REPORTS)
                        <a class="menu-link {{ \Illuminate\Support\Str::of(request()->url())==route('admin.reports.sales')?'active':'' }}"
                           href="{{ route('admin.reports.sales') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">
                                   Sales Report
                               </span>
                        </a>
                    @endcan
                    @can(Permission::VIEW_SALES_PAYMENT_REPORTS)
                        {{--   <a class="menu-link {{ \Illuminate\Support\Str::of(request()->url())==route('admin.reports.payments')?'active':'' }}"
                              href="{{ route('admin.reports.payments') }}">
                               <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                               <span class="menu-title">
                                      Sales Payments Report
                                  </span>
                           </a>--}}
                    @endcan
                    <!--end:Menu link-->
                    @can(Permission::VIEW_PURCHASE_REPORTS)
                        <a class="menu-link {{ request()->url()==route('admin.reports.purchase-orders.history')?'active':'' }}"
                           href="{{ route('admin.reports.purchase-orders.history') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Purchase Report</span>
                        </a>
                    @endcan
                    <!--end:Menu link-->
                    <!--end:Menu link-->
                    @can(Permission::VIEW_ITEMS_REPORTS)
                        {{--        <a class="menu-link {{ request()->url()==route('admin.reports.items')?'active':'' }}"
                                   href="{{ route('admin.reports.items') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Items Report</span>
                                </a>--}}
                    @endcan
                    <!--end:Menu link-->
                    <!--end:Menu link-->
                    @can(Permission::VIEW_EXPENSES_REPORTS)
                        {{--      <a class="menu-link {{ request()->url()==route('admin.reports.expenses')?'active':'' }}"
                                 href="{{ route('admin.reports.expenses') }}">
                                  <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                  <span class="menu-title">Expenses Report</span>
                              </a>--}}
                    @endcan
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
            </div>
        </div>


    </div>
    <!--end::Sidebar menu-->

</div>
<!--end::Main-->
