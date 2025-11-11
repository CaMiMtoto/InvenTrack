@extends('layouts.master')
@section('title','Shares')

@section('content')
    <div>
        <div class="d-flex justify-content-between align-items-center">
            <x-toolbar title="Shares" :breadcrumbs="[['label'=>'Shares']]"/>
            {{-- @can(\App\Constants\Permission::MANAGE_SHAREHOLDERS)
                 <button type="button" class="btn btn-sm btn-light-primary px-4 py-3" id="addBtn">
                     <i class="bi bi-plus fs-3"></i>
                     Add New
                 </button>
             @endcan--}}
        </div>
        <div class="my-3">
            <div class="table-responsive">
                <table
                    class="table ps-2 align-middle table-hover table-row-dashed table-row-gray-300 align-middle  fs-6 gy-1"
                    id="myTable">
                    <thead>
                    <tr>
                        <th>Created At</th>
                        <th>Shareholder</th>
                        <th>Value</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            let $myTable = $('#myTable');
            $myTable.DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! request()->fullUrl() !!}',
                columns: [
                    {data: 'created_at', name: 'created_at'},
                    {data: 'shareholder.name', name: 'shareholder.name'},
                    {data: 'value', name: 'value'},
                    {data: 'quantity', name: 'quantity'},
                    {
                        data: 'total', name: 'total', render: function (data, type, row, meta) {
                            return Number(data).toLocaleString()
                        }
                    },
                    {data: 'status', name: 'status',
                        render: function (data, type, row) {
                            return `<span class="badge badge-light-${row.status_color}">${data}</span>`;
                        }
                    },
                    {data: 'action', name: 'action', searchable: false, orderable: false},
                ],
                order: [[0, 'desc']],
                drawCallback: function () {
                    $('[data-bs-toggle="tooltip"]').tooltip();
                    // format total
                }
            });
        });
    </script>
@endpush
