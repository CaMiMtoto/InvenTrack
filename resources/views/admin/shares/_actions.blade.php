<a href="{{ route('admin.shares.show', encodeId($share->id)) }}" class="btn btn-light-primary btn-sm">
    View
</a>
@if(strtolower($share->status) === strtolower(\App\Constants\Status::Pending))
    <a href="#"
       class="btn btn-light-warning btn-sm ms-1 edit-share"
       data-url="{{ route('admin.shares.edit', encodeId($share->id)) }}"
       data-update_url="{{ route('admin.shares.update', encodeId($share->id)) }}">
        Edit
    </a>
    <button type="button" class="btn btn-danger btn-sm ms-1 delete-share"
            data-delete_url="{{ route('admin.shares.destroy', encodeId($share->id)) }}">
        Delete
    </button>
@endif
