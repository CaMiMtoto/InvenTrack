@extends('layouts.master')
@section('title', 'Permissions')
@section('content')
    <div>
        <!--begin::Toolbar-->
      <x-toolbar title="Manage Permissions" :breadcrumbs="[['label'=>'Permissions']]"/>
        <!--end::Toolbar-->
        <!--begin::Content-->
            <div class="table-responsive">
                <table class="table ps-2 align-middle  table-row-bordered table-row-gray-200 align-middle  fs-6 gy-2" id="myTable">
                    <thead>
                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                        <th>Created At</th>
                        <th>Description</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($permissions as $item)
                        <tr>
                            <td>{{ $item->created_at->format('Y-m-d H:m') }}</td>
                            <td>{{ $item->description }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-light-primary  btn-icon" id="editBtn">
                                    <i class="bi bi-pencil fs-3"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        <!--end::Content-->
    </div>

@endsection

@push('scripts')
    <script>
        $(function () {
            let myTable = $('#myTable').DataTable();
        });
    </script>
@endpush
