<div class="drop-down dropdown-action">
    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots-vertical"></i>
    </a>
    <ul class="dropdown-menu dropdown-menu-right">
        {{-- Edit button --}}
        <li>
            <a href="{{ route('admin.settings.departments.show', encodeId($item->id)) }}" data-toggle="tooltip" data-id="{{ $item->id }}"
               data-original-title="Edit" class="dropdown-item js-edit">
                Edit
            </a>
        </li>

        {{-- Delete button --}}
        <li>
            <a href="{{ route('admin.settings.departments.destroy',encodeId($item->id)) }}" data-toggle="tooltip"
               data-id="{{ $item->id }}" data-original-title="Delete" class="dropdown-item js-delete">
                Delete
            </a>
        </li>
    </ul>
</div>
