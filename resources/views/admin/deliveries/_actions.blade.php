@php use App\Constants\Permission;use App\Constants\Status; @endphp

<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
        Options
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item js-details" href="#"
               data-url="{{ route('admin.deliveries.show',encodeId($delivery->id)) }}">Details</a>
        </li>
        @if(strtolower($delivery->status)==strtolower(Status::PartiallyDelivered) && $delivery->returns_count==0 && auth()->user()->can(Permission::DELIVER_PRODUCTS))
            <li>
                <a class="dropdown-item js-returns"
                   href="{{ route('admin.deliveries.returns',encodeId($delivery->id)) }}">
                    Return Items
                </a>
            </li>
        @endif

    </ul>
</div>
