@can(\App\Constants\Permission::MANAGE_SHAREHOLDERS)
    <div class="d-flex justify-content-center">
        <button
            type="button"
            class="btn btn-sm btn-light-primary edit-btn btn-icon"
            data-id="{{ $shareholder->id }}"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="Edit Shareholder"
        >
            <x-lucide-pen-square class="tw-h-4 tw-w-4"/>
        </button>
        <button
            type="button"
            class="btn btn-sm btn-light-danger delete-btn ms-2 btn-icon"
            data-id="{{ $shareholder->id }}"
            data-name="{{ e($shareholder->name) }}"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="Delete Shareholder"
        >
            <x-lucide-trash-2 class="tw-h-4 tw-w-4"/>
        </button>
    </div>
@endcan
