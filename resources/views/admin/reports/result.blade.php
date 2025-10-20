@extends('layouts.master')
@section('title', $report->name)

@section('styles')
    {{-- Styles for printing --}}
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #print-area, #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .card-header, .card-body {
                border: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-between">
        <x-toolbar :title="$report->name . ' Report'" :breadcrumbs="[
        ['label' => 'Reports', 'url' => route('admin.reports.list')],
        ['label' => $report->name, 'url' => route('admin.reports.show', encodeId($report->id))],
        ['label' => 'Result'] ]"
        />
        <div class="card-toolbar">

            <!--begin::Export form-->
            <form action="{{ route('admin.reports.export', encodeId($report->id)) }}" method="POST" id="export_form">
                @csrf
                @foreach($input as $key => $value)
                    @if(is_array($value))
                        @foreach($value as $subKey => $subValue)
                            @if(is_array($subValue))
                                @foreach($subValue as $deepKey => $deepValue)
                                    <input type="hidden" name="{{ $key }}[{{ $subKey }}][{{ $deepKey }}]"
                                           value="{{ $deepValue }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}[{{ $subKey }}]" value="{{ $subValue }}">
                            @endif
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <button type="submit" class="btn btn-light-success btn-sm">
                    <x-lucide-cloud-download class="tw-h-5 tw-w-5"/>
                    Export To Excel
                </button>
            </form>
            <!--end::Export form-->

        </div>
    </div>

    <div id="print-area">

        <div>
            @if($data->isEmpty())
                <div class="text-center text-muted py-10">
                    No data found for the selected criteria.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="kt_report_table">
                        <thead>
                        <tr class="fw-bold fs-6 text-gray-800">
                            @foreach($selectedColumns as $column)
                                <th>{{ ucwords(str_replace('_', ' ', $column)) }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $row)
                            <tr>
                                @foreach($selectedColumns as $column)
                                    <td>{{ $row->{$column} }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        "use strict";

        // Class definition
        var KTReportView = function () {
            // Shared variables
            var table;
            var datatable;

            // Private functions
            var initDatatable = function () {
                // Init datatable --- more info on datatables: https://datatables.net/manual/
                datatable = $(table).DataTable({
                    "info": false,
                    'order': [],
                    'pageLength': 10,
                    'columnDefs': [
                        {orderable: false, targets: -1} // Disable ordering on the last column (actions)
                    ]
                });
            }


            // Public methods
            return {
                init: function () {
                    table = document.querySelector('#kt_report_table');
                    if (!table) {
                        return;
                    }
                    initDatatable();
                }
            };
        }();

        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            KTReportView.init();
        });
    </script>
@endpush
