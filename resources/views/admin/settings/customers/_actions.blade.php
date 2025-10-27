<div class="btn-group">
    <button type="button" class="btn btn-sm btn-icon dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
        <i class="bi bi-three-dots-vertical"></i>
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item js-edit" href="{{ route('admin.settings.customers.show', encodeId($row->id)) }}">Edit</a>
        <a class="dropdown-item js-delete" href="{{ route('admin.settings.customers.destroy', encodeId($row->id)) }}">Delete</a>
        @if($row->latitude && $row->longitude)
            <a class="dropdown-item"
               href="https://www.google.com/maps/dir/?api=1&destination={{ $row->latitude }},{{ $row->longitude }}"
               target="_blank">Get Directions</a>
        @endif
    </div>
</div>
