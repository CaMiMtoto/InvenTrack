@props([
    /** @var \App\Models\SaleOrder */
    'saleOrder'
])

<div {{ $attributes->class(['my-3']) }}>

    <!--begin::Layout-->
    <div class="d-flex flex-column flex-xl-row">
        <!--begin::Content-->
        <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
            <!--begin::Invoice 2 content-->
            <div class="mt-n1">
                <!--begin::Top-->
                <div class="d-flex flex-stack pb-10">
                    <!--begin::Logo-->
                    <a href="#">
                        <img alt="Logo" src="{{ asset('assets/media/logos/logo.png') }}" class="tw-w-32">
                    </a>
                    <!--end::Logo-->

                    <!--begin::Action-->

                    <!--end::Action-->
                </div>
                <!--end::Top-->

                <!--begin::Wrapper-->
                <div class="m-0">


                    <!--begin::Row-->
                    <div class="row g-5 mb-11">
                        <!--end::Col-->
                        <div class="col-sm-6">
                            <div class=" fs-3 text-gray-800 mb-8">Order #
                                <strong>{{ $saleOrder->order_number }}</strong>
                            </div>
                            <!--end::Label-->
                            <div class="fw-semibold fs-7 text-gray-600 mb-1">Order Date:</div>
                            <!--end::Label-->
                            <!--end::Col-->
                            <div class="fw-bold fs-6 text-gray-800 mb-3">
                                {{ $saleOrder->order_date->format('d M Y') }}
                            </div>
                            <!--end::Col-->
                            <!--end::Label-->
                            <div class="fw-semibold fs-7 text-gray-600 mb-1">Order Status:</div>
                            <!--end::Label-->
                            <!--end::Col-->
                            <div class="fw-bold fs-6 text-gray-800 mb-3">
                                <span class="badge bg-{{$saleOrder->status_color}}-subtle fw-bold text-{{$saleOrder->statusColor}} ">
                                    {{ $saleOrder->status }}
                                </span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Col-->

                        <!--end::Col-->
                        <div class="col-sm-6">
                           <div class="row">
                               <!--end::Label-->
                               <div class="fw-semibold fs-7 text-gray-600 mb-1">Customer Name:</div>
                               <!--end::Label-->
                               <!--end::Info-->
                               <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap mb-3">
                                   <span class="pe-2">{{ $saleOrder->customer->name }}</span>
                               </div>
                               <!--end::Info-->
                               <!--end::Label-->
                               <div class="fw-semibold fs-7 text-gray-600 mb-1">Address:</div>
                               <!--end::Label-->
                               <!--end::Info-->
                               <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap mb-3">
                                   <span class="pe-2">{{ $saleOrder->customer->address }}</span>
                               </div>
                               <!--end::Info-->
                               <!--end::Label-->
                               <div class="fw-semibold fs-7 text-gray-600 mb-1">Phone:</div>
                               <!--end::Label-->
                               <!--end::Info-->
                               <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                   <span class="pe-2">{{ $saleOrder->customer->phone }}</span>
                               </div>
                               <!--end::Info-->
                           </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->

                    <hr>
                    <h4>
                        Products
                    </h4>
                    <p>
                        Below are the list of items ordered
                    </p>

                    <!--begin::Content-->
                    <div class="flex-grow-1">
                        <!--begin::Table-->
                        <div class="table-responsive border-bottom mb-9">
                            <table class="table mb-3 table-striped table-hover  table-row-dashed table-row-gray-300">
                                <thead>
                                <tr class=" fs-6 fw-bold text-uppercase text-gray-800">
                                    <th class="min-w-175px pb-2">Product</th>
                                    <th class="min-w-70px  pb-2">Price</th>
                                    <th class="min-w-80px  pb-2">Qty</th>
                                    <th class="min-w-100px  pb-2">Total</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($saleOrder->items as $item)
                                    <tr class="">
                                        <td class="pt-6">
                                            {{ $item->product->name }}
                                        </td>

                                        <td class="pt-6">
                                            {{number_format($item->unit_price, 0)}}
                                        </td>
                                        <td class="pt-6">
                                            {{ number_format($item->quantity,0) }} {{ $item->product->unit_measure }}
                                        </td>
                                        <td class="pt-6 text-dark fw-bolder">
                                            {{ number_format($item->total, 0) }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-end fw-bold text-gray-800 pt-6">Total</td>
                                    <td class=" fw-bold text-gray-800 pt-6">
                                        {{ number_format($saleOrder->total, 0) }}
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Invoice 2 content-->
        </div>
        <!--end::Content-->

    </div>
    <!--end::Layout-->

</div>
