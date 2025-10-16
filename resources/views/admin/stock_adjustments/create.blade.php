@extends('layouts.master')

@section('title', 'New Stock Adjustment')

@section('content')
    <x-toolbar title="New Adjustment"
               :breadcrumbs="[
    ['label'=>'Stock Adjustments','url'=>route('admin.stock-adjustments.index')],
    ['label'=>'Create']
]"
               :actions="[
    ['url'=>route('admin.stock-adjustments.index'),'label'=>'Back','class'=>'btn-primary']
]"
    />

    <div class="my-4">
        <p>
            Please fill in the form below to request stock adjustment for selected products
        </p>
        <form action="{{ route('admin.stock-adjustments.store') }}" method="POST" id="kt_stock_adjustment_form">
            @csrf
            <div class="mb-3">
                <label for="reason" class="form-label required">Reason for Adjustment</label>
                <textarea name="reason" id="reason" class="form-control" rows="3"
                          placeholder="e.g., Annual stock take results, Damaged goods disposal"
                          required>{{ old('reason') }}</textarea>
            </div>

            <h4 class="mb-3">Items to Adjust</h4>

            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table g-5 gs-0 mb-0 fw-bold text-gray-700" data-kt-element="items">
                    <thead>
                    <tr class="border-bottom fs-7 fw-bold text-gray-700 text-uppercase">
                        <th class="min-w-300px w-475px">Product</th>
                        <th class="min-w-150px w-150px">Type</th>
                        <th class="min-w-100px w-150px">Quantity</th>
                        <th class="min-w-75px w-75px text-end">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{-- This is the template for a new row --}}
                    <tr data-kt-element="item-template">
                        <td>
                            <select class="form-select form-select-sm" name="items[0][product_id]"
                                    data-kt-element="product">
                                <option value="">Select</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}(Current: {{ $product->stock }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="form-select form-select-sm" name="items[0][type]" data-kt-element="type">
                                <option value="increase">Increase</option>
                                <option value="decrease">Decrease</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" name="items[0][quantity]"
                                   placeholder="e.g., 5" value="1" min="1" data-kt-element="quantity"/>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-icon btn-light-danger"
                                    data-kt-element="remove-item">
                                <x-lucide-trash-2 class="tw-h-5 tw-w-5"/>
                            </button>
                        </td>
                    </tr>
                    {{-- This is the empty state --}}
                    <tr data-kt-element="empty-template" class="d-none">
                        <td colspan="4" class="text-muted text-center py-10">No items specified</td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr class="border-top">
                        <th colspan="4">
                            <button type="button" class="btn btn-light-primary" data-kt-element="add-item">
                                <i class="bi bi-plus-circle-fill"></i> Add Another Item
                            </button>
                        </th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <!--end::Table-->

            <div class="d-flex justify-content-end mt-5">
                <button type="submit" class="btn btn-primary">Submit for Approval</button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
    {{-- You can extract this to a separate JS file if you prefer --}}
    <script>
        $(function () {
            const form = document.getElementById('kt_stock_adjustment_form');
            const itemsTable = form.querySelector('[data-kt-element="items"]');
            const template = itemsTable.querySelector('[data-kt-element="item-template"]');
            const emptyTemplate = itemsTable.querySelector('[data-kt-element="empty-template"]');
            const addButton = itemsTable.querySelector('[data-kt-element="add-item"]');
            let rowIndex = 0;

            const handleEmptyState = () => {
                if (itemsTable.querySelectorAll('tbody tr[data-kt-element="item"]').length === 0) {
                    const clone = emptyTemplate.cloneNode(true);
                    clone.classList.remove('d-none');
                    itemsTable.querySelector('tbody').appendChild(clone);
                } else {
                    $(itemsTable).find('[data-kt-element="empty-template"]').remove();
                }
            };

            const addRow = () => {
                rowIndex++;
                const clone = template.cloneNode(true);
                clone.classList.remove('d-none');
                clone.setAttribute('data-kt-element', 'item');

                // Update name attributes to be unique
                clone.querySelector('[data-kt-element="product"]').name = `items[${rowIndex}][product_id]`;
                clone.querySelector('[data-kt-element="type"]').name = `items[${rowIndex}][type]`;
                clone.querySelector('[data-kt-element="quantity"]').name = `items[${rowIndex}][quantity]`;

                itemsTable.querySelector('tbody').appendChild(clone);

                // Initialize select2 on the new row's product dropdown
                /*    $(clone.querySelector('[data-kt-element="product"]')).select2({
                        placeholder: "Select a product",
                        allowClear: true
                    });*/

                handleEmptyState();
            };

            addButton.addEventListener('click', e => {
                e.preventDefault();
                addRow();
            });

            $(itemsTable).on('click', '[data-kt-element="remove-item"]', function (e) {
                e.preventDefault();
                $(this).closest('tr').remove();
                handleEmptyState();
            });

            // Add one row by default and initialize
            // addRow();
        });
    </script>
@endpush
