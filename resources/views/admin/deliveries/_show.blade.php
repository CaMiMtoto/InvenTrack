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
                            <div class=" fs-3 text-gray-800 mb-8">
                                <div>Order #</div>
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
                                <!--end::Label-->
                                <div class="fw-semibold fs-7 text-gray-600 mb-1">Landmark:</div>
                                <!--end::Label-->
                                <!--end::Info-->
                                <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                    <span class="pe-2">{{ $delivery->order->customer->landmark }}</span>
                                </div>
                                <!--end::Info-->
                                @if($delivery->order->customer->latitude && $delivery->order->customer->longitude)
                                    <!--end::Label-->
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">
                                        Direction
                                    </div>
                                    <!--end::Label-->
                                    <!--end::Info-->
                                    <a
                                        href="https://www.google.com/maps/dir/?api=1&destination={{ $delivery->order->customer->latitude }},{{ $delivery->order->customer->longitude }}"
                                        target="_blank">
                                        <x-lucide-map-pin class="tw-h-5 tw-w-5"/>
                                        Get Directions
                                    </a>
                                    <!--end::Info-->
                                @endif

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
    <div>
        <!--begin::Table-->
        <div class="table-responsive mb-9">
            <table class="table mb-3 table-striped table-hover border rounded table-row-dashed table-row-gray-300 gy-2">
                <thead>
                <tr class=" fs-6 fw-bold text-uppercase text-gray-800">
                    <th class="px-2">Product</th>
                    <th class="px-2">Price</th>
                    <th class="px-2">Qty</th>
                    <th class="px-2">Total</th>
                </tr>
                </thead>

                <tbody>
                @foreach($delivery->items as $item)
                    <tr class="">
                        <td class="px-2">
                            {{ $item->orderItem->product->name }}
                        </td>

                        <td class="px-2">
                            {{number_format($item->orderItem->unit_price, 0)}}
                        </td>
                        <td class="px-2">
                            {{ number_format($item->orderItem->quantity,0) }} {{ $item->orderItem->product->unit_measure }}
                        </td>
                        <td class="px-2 text-dark fw-bolder">
                            {{ number_format($item->orderItem->total, 0) }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-end fw-bold text-gray-800 pt-6">Total</td>
                    <td class=" fw-bold text-gray-800 pt-6">
                        {{ number_format($delivery->order->total, 0) }}
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
        <!--end::Table-->
    </div>
    <!--end::Content-->

    @if($delivery->statusCanBeUpdated())
        <div class=" mt-4">
            <div>
                <h1>Update Status</h1>
                <p>
                    Please review the delivery details above and choose appropriate status. You may also leave a
                    comment.
                </p>
            </div>
            <form action="{{ route('admin.deliveries.update-status', encodeId($delivery->id)) }}" method="POST"
                  id="updateDeliveryStatusForm">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value=""></option>
                        <option value="transit">Transit</option>
                        <option value="partially delivered">Partially Delivered</option>
                        <option value="delivered">Delivered</option>
                    </select>
                </div>

                {{-- Comment --}}
                <div class="mb-3">
                    <label for="comment" class="form-label">Comment (optional)</label>
                    <textarea name="comment" id="comment" rows="3" class="form-control"
                              placeholder="Leave a note if any"></textarea>
                    @error('comment')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Buttons --}}
                <div class="d-flex gap-2">
                    <button type="submit" name="action" value="approved" class="btn btn-success">
                        Save Changes
                    </button>
                </div>
            </form>

        </div>
    @endif


</div>
