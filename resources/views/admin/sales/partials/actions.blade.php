<div class="dropdown">
    <button class="btn btn-light btn-sm btn-icon dropdown-toggle" type="button" id="dropdownMenuButton"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="bi bi-three-dots"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="{{ route('admin.orders.show', encodeId($saleOrder->id)) }}">Details</a>
        <a class="dropdown-item" href="{{route('admin.orders.print',encodeId( $saleOrder->id))}}" target="_blank">Print</a>
        @if($saleOrder->status != \App\Constants\Status::Cancelled && auth()->user()->can(\App\Constants\Permission::MANAGE_SALES_DELIVERY))
            <a class="dropdown-item" href="">Deliveries</a>
        @endif
        @if( auth()->user()->can(\App\Constants\Permission::CANCEL_SALES_ORDERS))
            <a class="dropdown-item js-cancel"
               href="{{route('admin.orders.cancel', encodeId($saleOrder->id))}}">Cancel</a>
            <a class="dropdown-item js-edit" href="{{ route('admin.orders.edit', encodeId($saleOrder->id)) }}">Edit</a>
        @endif
        {{--        <a class="dropdown-item js-delete" href="{{ route(route('admin.sale-orders.destroy', $saleOrder->id)) }}">Delete</a>--}}
    </div>
</div>
