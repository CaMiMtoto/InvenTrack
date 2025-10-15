<div>
    <div class="row">
        <div class="col-lg-6">
            <!--end::Label-->
            <div class="fw-semibold fs-7 text-gray-600 mb-1">Customer Name:</div>
            <!--end::Label-->
            <!--end::Info-->
            <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap mb-3">
                <span class="pe-2">{{ $customer->name }}</span>
            </div>
            <!--end::Info-->
        </div>
        <div class="col-lg-6">
            <!--end::Label-->
            <div class="fw-semibold fs-7 text-gray-600 mb-1">Nickname:</div>
            <!--end::Label-->
            <!--end::Info-->
            <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                <span class="pe-2">{{ $customer->nickname }}</span>
            </div>
            <!--end::Info-->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <!--end::Label-->
            <div class="fw-semibold fs-7 text-gray-600 mb-1">Address:</div>
            <!--end::Label-->
            <!--end::Info-->
            <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap mb-3">
                <span class="pe-2">{{ $customer->address }}</span>
            </div>
            <!--end::Info-->
        </div>
        <div class="col-lg-6">
            <!--end::Label-->
            <div class="fw-semibold fs-7 text-gray-600 mb-1">Phone:</div>
            <!--end::Label-->
            <!--end::Info-->
            <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                <span class="pe-2">{{ $customer->phone }}</span>
            </div>
            <!--end::Info-->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <!--end::Label-->
            <div class="fw-semibold fs-7 text-gray-600 mb-1">Landmark:</div>
            <!--end::Label-->
            <!--end::Info-->
            <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                <span class="pe-2">{{ $customer->landmark }}</span>
            </div>
            <!--end::Info-->
        </div>
        <div class="col-lg-6">
            <!--end::Label-->
            <div class="fw-semibold fs-7 text-gray-600 mb-1">District:</div>
            <!--end::Label-->
            <!--end::Info-->
            <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                <span class="pe-2">{{ $customer->district->name??'' }}</span>
            </div>
            <!--end::Info-->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <!--end::Label-->
            <div class="fw-semibold fs-7 text-gray-600 mb-1">Sector:</div>
            <!--end::Label-->
            <!--end::Info-->
            <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                <span class="pe-2">{{ $customer->sector->name??'' }}</span>
            </div>
            <!--end::Info-->
        </div>
        <div class="col-lg-6">
            <!--end::Label-->
            <div class="fw-semibold fs-7 text-gray-600 mb-1">Cell:</div>
            <!--end::Label-->
            <!--end::Info-->
            <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                <span class="pe-2">{{ $customer->cell->name??'' }}</span>
            </div>
            <!--end::Info-->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <!--end::Label-->
            <div class="fw-semibold fs-7 text-gray-600 mb-1">Village:</div>
            <!--end::Label-->
            <!--end::Info-->
            <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                <span class="pe-2">{{ $customer->village->name??'' }}</span>
            </div>
            <!--end::Info-->
        </div>
    </div>
</div>
