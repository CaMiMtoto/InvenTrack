@php use App\Constants\Status; @endphp
<div class="modal-body">
    <div class="d-flex flex-column flex-xl-row">
        <!--begin::Content-->
        <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
            <!--begin::Invoice 2 content-->
            <div class="mt-n1">

                <!--begin::Wrapper-->
                <div class="m-0">

                    <!--begin::Row-->
                    <div class="row g-5 mb-11">
                        <!--end::Col-->
                        <div class="col-sm-6">
                            <div class=" fs-3 text-gray-800 mb-8">Order #
                                <strong>{{ $delivery->order->order_number }}</strong>
                            </div>
                            <!--end::Label-->
                            <div class="fw-semibold fs-7 text-gray-600 mb-1">Order Date:</div>
                            <!--end::Label-->
                            <!--end::Col-->
                            <div class="fw-bold fs-6 text-gray-800 mb-3">
                                {{ $delivery->order->order_date->format('d M Y') }}
                            </div>
                            <!--end::Col-->
                            <!--end::Label-->
                            <div class="fw-semibold fs-7 text-gray-600 mb-1">Delivery Status:</div>
                            <!--end::Label-->
                            <!--end::Col-->
                            <div class="fw-bold fs-6 text-gray-800 mb-3">
                                <span
                                    class="badge bg-{{$delivery->status_color}}-subtle fw-bold text-{{$delivery->statusColor}} ">
                                    {{ $delivery->status }}
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
                                    <span class="pe-2">{{ $delivery->order->customer->name }}</span>
                                </div>
                                <!--end::Info-->
                                <!--end::Label-->
                                <div class="fw-semibold fs-7 text-gray-600 mb-1">Address:</div>
                                <!--end::Label-->
                                <!--end::Info-->
                                <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap mb-3">
                                    <span class="pe-2">{{ $delivery->order->customer->address }}</span>
                                </div>
                                <!--end::Info-->
                                <!--end::Label-->
                                <div class="fw-semibold fs-7 text-gray-600 mb-1">Phone:</div>
                                <!--end::Label-->
                                <!--end::Info-->
                                <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                    <span class="pe-2">{{ $delivery->order->customer->phone }}</span>
                                </div>
                                <!--end::Info-->
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Invoice 2 content-->
        </div>
        <!--end::Content-->

    </div>

    <h4>
        Products
    </h4>
    <p>
        Below are the list of items ordered
    </p>

    <!--begin::Content-->
    <form action="{{ route('admin.deliveries.process-returns',encodeId($delivery->id)) }}" id="processReturnForm">
        @csrf
        <!--begin::Table-->
        <div class="table-responsive">
            <table class="table mb-3 table-striped table-hover border rounded table-row-dashed table-row-gray-300 gy-2">
                <thead>
                <tr class=" fs-6 fw-bold text-uppercase text-gray-800">
                    <th class="px-2">Product</th>
                    <th class="px-2">Ordered</th>
                    <th class="px-2">Delivered</th>
                    <th class="px-2">Returns</th>
                    <th class="px-2">Reason</th>
                </tr>
                </thead>

                <tbody>
                @foreach($delivery->items as $item)

                    <tr class="">
                        <td class="px-2">
                            <input type="hidden" name="product_ids[]" value="{{$item->orderItem->product_id}}"/>
                            {{ $item->orderItem->product->name }}</td>
                        <td class="px-2">{{ $item->quantity }}</td>
                        <td class="px-2">
                            <span class="js-delivered">{{$item->quantity}}</span>
                        </td>
                        <td class="px-2">
                            <input type="number" max="{{ $item->orderItem->quantity }}" min="0" value="0" required
                                   class="form-control form-control-sm js-item-returns" name="returns[]"/>
                        </td>
                        <td class="px-2">
                            <select name="return_reasons[]" class="form-select form-select-sm" >
                                <option value="">Select reason</option>
                                @foreach($reasons as $reason)
                                    <option value="{{$reason->id}}">
                                        {{ $reason->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!--end::Table-->
        {{-- Comment --}}
        <div class="mb-3">
            <label for="comment" class="form-label">Comment (optional)</label>
            <textarea name="comment" id="comment" rows="3" class="form-control"
                      placeholder="Add any additional details..."></textarea>
        </div>

        {{-- Submit --}}
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                Submit Return Request
            </button>
        </div>
    </form>
    <!--end::Content-->


</div>

