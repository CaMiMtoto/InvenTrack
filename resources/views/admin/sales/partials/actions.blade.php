<div class="dropdown">
    <button class="btn btn-light btn-sm btn-icon dropdown-toggle" type="button" id="dropdownMenuButton"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="bi bi-three-dots"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="{{ route('admin.orders.show', encodeId($saleOrder->id)) }}">Details</a>
        <a class="dropdown-item" href="{{route('admin.orders.print',encodeId( $saleOrder->id))}}"
           target="_blank">Print</a>

        @if( $saleOrder->canBeCompleted())
            <a class="dropdown-item js-complete"
               href="{{route('admin.orders.mark-as-complete', encodeId($saleOrder->id))}}">
                Mark as Complete
            </a>
        @endif
        {{--        <a class="dropdown-item js-delete" href="{{ route(route('admin.sale-orders.destroy', $saleOrder->id)) }}">Delete</a>--}}
    </div>
</div>
