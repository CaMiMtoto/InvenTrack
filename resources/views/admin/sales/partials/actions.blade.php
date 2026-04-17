<div class="dropdown">
    <button class="btn btn-light btn-sm btn-icon dropdown-toggle" type="button" id="dropdownMenuButton"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="bi bi-three-dots"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="{{ route('admin.orders.show', encodeId($saleOrder->id)) }}">Details</a>
    {{--    <a class="dropdown-item" href="{{route('admin.orders.print',encodeId( $saleOrder->id))}}"
           target="_blank">Print</a>--}}

        @if( $saleOrder->canBeCompleted())
            <a class="dropdown-item js-complete"
               href="{{route('admin.orders.mark-as-complete', encodeId($saleOrder->id))}}">
                Mark as Complete
            </a>
        @endif
        @if($saleOrder->order_status === \App\Constants\Status::Pending && $saleOrder->created_by === auth()->id())
            <a class="dropdown-item js-delete" href="{{ route('admin.orders.destroy', encodeId($saleOrder->id)) }}">Delete</a>
        @endif
    </div>
</div>
