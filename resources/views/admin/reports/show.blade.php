@extends('layouts.master')
@section('title',$report->name)

@section('content')
    <x-toolbar :title="$report->name" :breadcrumbs="[
        ['label' => 'Reports', 'url' => route('admin.reports.list')],
        ['label' => $report->name]
    ]"/>

    <div class="my-5">
        <h4>
            Generate Report
        </h4>
        <form action="{{ route('admin.reports.generate', encodeId($report->id)) }}" method="get" target="_blank">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name">Report Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $report->name }}"/>
                    </div>
                </div>
            </div>

            <h4>Select Columns</h4>
            <p class="text-muted">Choose the columns you want to include in the report.</p>
            <div class="row mb-5">
                @foreach($columns as $column)
                    <div class="col-md-6">
                        <div class="form-check form-check-custom  mb-3">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="{{ $column }}"
                                   id="col_{{ $column }}" checked/>
                            <label class="form-check-label" for="col_{{ $column }}">
                                {{ ucwords(str_replace('_', ' ', $column)) }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr class="my-10">

            <h4>Filters</h4>
            <p class="text-muted">Add conditions to filter your report data.</p>
            <div id="filters-container"></div>
            <div class="form-group mt-5">
                <button type="button" id="add-filter-btn" class="btn btn-light-primary  btn-sm">
                    <i class="ki-duotone ki-plus fs-3"></i>
                    Add Filter
                </button>
            </div>

            <hr class="my-10">

            <h4>Sorting Options</h4>
            <div class="row">
                <div class="col-md-6 mb-5">
                    <label for="sort_by" class="form-label">Sort By</label>
                    <select name="sort_by" id="sort_by" class="form-select" data-control="select2"
                            data-placeholder="Select a column to sort by">
                        <option></option>
                        @foreach($columns as $column)
                            <option value="{{ $column }}">{{ ucwords(str_replace('_', ' ', $column)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-5">
                    <label for="sort_dir" class="form-label">Sort Direction</label>
                    <select name="sort_dir" id="sort_dir" class="form-select" data-control="select2">
                        <option value=""></option>
                        <option value="asc">Ascending</option>
                        <option value="desc">Descending</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-5">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-file-earmark-arrow-down"></i>
                    Generate Report
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // This script updates the "Sort By" dropdown based on the selected columns.
        $(document).ready(function () {
            const updateSortByOptions = () => {
                const $sortBySelect = $('#sort_by');
                const selectedColumns = $('input[name="columns[]"]:checked').map(function () {
                    return {value: this.value, text: $(this).siblings('label').text().trim()};
                }).get();

                const currentSortVal = $sortBySelect.val();
                $sortBySelect.empty().append(new Option('', '')); // Add placeholder

                selectedColumns.forEach(col => {
                    $sortBySelect.append(new Option(col.text, col.value));
                });

                // Try to re-select the previously selected value
                $sortBySelect.val(currentSortVal).trigger('change');
            };

            $('input[name="columns[]"]').on('change', updateSortByOptions);

            // Initial call
            updateSortByOptions();

            // --- Custom Repeater Logic ---
            let filterIndex = 0;
            const filtersContainer = $('#filters-container');
            // Function to add a new filter row
            const addFilterRow = () => {
                filterIndex++;
                const filterHtml = `
                     <div class="form-group row mb-5 filter-row">
                         <div class="col-md-3">
                             <label class="form-label">Column</label>
                             <select name="filters[${filterIndex}][column]" class="form-select form-select-sm filter-column" data-control="select2" data-placeholder="Select column">
                                 <option></option>
                                 @foreach($columns as $column)
                <option value="{{ $column }}">{{ ucwords(str_replace('_', ' ', $column)) }}</option>
                                 @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Operator</label>
                <select name="filters[${filterIndex}][operator]" class="form-select form-select-sm" data-control="select2" data-placeholder="Select operator">
                                 <option></option>
                                 <option value="=">Equals (=)</option>
                                 <option value="!=">Not Equals (!=)</option>
                                 <option value=">">Greater Than (>)</option>
                                 <option value="<">Less Than (<)</option>
                                 <option value=">=">Greater Than or Equal (>=)</option>
                                 <option value="<=">Less Than or Equal (<=)</option>
                                 <option value="like">Contains (LIKE)</option>
                             </select>
                         </div>
                         <div class="col-md-4">
                             <label class="form-label">Value</label>
                             <input type="text" name="filters[${filterIndex}][value]" class="form-control form-control-sm" placeholder="Enter value"/>
                         </div>
                         <div class="col-md-2">
                             <button type="button" class="btn btn-sm btn-light-danger mt-3 mt-md-8 remove-filter-btn">
                            -
                             </button>
                         </div>
                     </div>
                 `;
                const $newRow = $(filterHtml);
                filtersContainer.append($newRow);

                // Initialize Select2 on the new dropdowns
                $newRow.find('[data-control="select2"]').select2();
            };
            // Event listener for the "Add Filter" button
            $('#add-filter-btn').on('click', addFilterRow);

            // Event listener for the "Remove" button (using event delegation)
            filtersContainer.on('click', '.remove-filter-btn', function () {
                $(this).closest('.filter-row').slideUp(function () {
                    $(this).remove();
                });
            });
        });
    </script>
@endpush
