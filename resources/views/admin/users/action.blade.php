<div class="drop-down dropdown-action">
    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots-vertical"></i>
    </a>
    <ul class="dropdown-menu dropdown-menu-right">
        {{-- Edit button --}}
        <li>
            <a href="{{ route('admin.system.users.show', $user->id) }}" data-toggle="tooltip" data-id="{{ $user->id }}" data-original-title="Edit" class="dropdown-item js-edit">
                Edit
            </a>
        </li>

        {{-- Activate/Deactivate button --}}
        <li>
            <a href="{{ route('admin.system.users.active-toggle', $user->id) }}"
               data-isActive="{{ $user->is_active }}"
               data-toggle="tooltip" data-id="{{ $user->id }}" data-original-title="Activate/Deactivate" class="dropdown-item js-toggle-active">
               {{ $user->is_active ? 'Deactivate' : 'Activate' }}
            </a>
        </li>

        {{-- Delete button --}}
        <li>
            <a href="{{ route('admin.system.users.destroy',$user->id) }}" data-toggle="tooltip" data-id="{{ $user->id }}" data-original-title="Delete" class="dropdown-item js-delete">
                Delete
            </a>
        </li>
    </ul>
</div>
