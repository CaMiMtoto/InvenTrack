@can(\App\Constants\Permission::MANAGE_SHAREHOLDERS)
    <div class="dropdown">
        <button class="btn btn-sm btn-light btn-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <x-lucide-more-vertical class="tw-h-5 tw-w-5"/>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a href="{{ route('admin.shareholders.shares',encodeId($shareholder->id)) }}" type="button"
                   class="dropdown-item">
                    <x-lucide-list-collapse class="tw-h-4 tw-w-4 tw-mr-2"/>
                    Shares
                </a>
            </li>
            <li>
                <button type="button" class="dropdown-item edit-btn" data-url="{{ route('admin.shareholders.edit',encodeId($shareholder->id)) }}" >
                    <x-lucide-pen-square class="tw-h-4 tw-w-4 tw-mr-2"/>
                    Edit
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="dropdown-item delete-btn"
                    data-url="{{ route('admin.shareholders.destroy',encodeId($shareholder->id)) }}"
                    data-name="{{ e($shareholder->name) }}"
                >
                    <x-lucide-trash-2 class="tw-h-4 tw-w-4 tw-mr-2"/>
                    Delete
                </button>
            </li>
        </ul>
    </div>
@endcan
