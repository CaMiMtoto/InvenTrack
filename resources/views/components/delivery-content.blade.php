 <div {{ $attributes->class(['my-4']) }}>
        <div class="table-responsive">
            <table class="table ps-2 align-middle  rounded table-row-dashed fs-6  gy-2" id="myTable">
                <thead>
                <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                    <th>Created At</th>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Options</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="myModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Delivery Details
                    </h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="bi bi-x"></i>
                    </div>
                    <!--end::Close-->
                </div>
                <div id="results"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="returnModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Delivery Returns
                    </h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="bi bi-x"></i>
                    </div>
                    <!--end::Close-->
                </div>
                <div id="delivery_results"></div>
            </div>
        </div>
    </div>
