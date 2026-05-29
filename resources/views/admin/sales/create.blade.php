@extends('layouts.master')
@php
    $isEdit = isset($order);
    $orderItems = old('items', $isEdit
        ? $order->items->map(fn ($item) => [
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'unit_price' => $item->unit_price,
        ])->toArray()
        : [[
            'product_id' => '',
            'quantity' => 1,
            'unit_price' => 0,
        ]]
    );
@endphp
@section('title', $isEdit?'Edit Order':'Create Order')

@section('styles')
@endsection

@section('content')
    <div>
        <x-toolbar
            :breadcrumbs="[
            ['label' => 'Sales Orders', 'url' => route('admin.orders.index')],
            ['label' => $isEdit?'Edit Order':'New Order', 'url' => '']
        ]"
            title="{{ $isEdit?'Edit Order':'New Order' }}"
        />


        <form
            action="{{ $isEdit ? route('admin.orders.update', encodeId($order->id)) : route('admin.orders.store') }}"
            method="post"
            class="my-3"
            id="submitForm"
        >
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="order_date" class="form-label">Order Date</label>
                    <input
                        type="date"
                        class="form-control  @error('order_date') is-invalid @enderror "
                        id="order_date"
                        name="order_date"
                        value="{{ old('order_date', $isEdit ? $order->order_date->toDateString() : now()->toDateString()) }}"
                    />
                    @error('order_date')
                    <span class="invalid-feedback d-block small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="customer_id" class="form-label">Customer</label>
                    <select class="form-select  @error('customer_id') is-invalid @enderror
                     " id="customer_id" name="customer_id">
                        <option value="">Select customer</option>
                        @foreach($customers as $customer)
                            <option
                                value="{{ $customer->id }}"
                                @selected(old('customer_id', $isEdit ? $order->customer_id : '') == $customer->id)
                            >
                                {{ $customer->name }} - {{ $customer->address }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                    <span class="invalid-feedback d-block small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Items</h4>
                <button type="button" class="btn btn-sm btn-primary" id="addRow">Add Item</button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-row-bordered align-middle">
                    <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody id="itemsBody">
                    @foreach($orderItems as $index => $row)
                        <tr>
                            <td>
                                <select class="form-select product-select" name="items[{{ $index }}][product_id]"
                                        required>
                                    <option value="">Select item</option>
                                    @foreach($items as $product)
                                        <option
                                            value="{{ $product->id }}"
                                            data-price="{{ $product->price ?? 0 }}"
                                            @selected($row['product_id'] == $product->id)
                                        >
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input
                                    type="number"
                                    name="items[{{ $index }}][quantity]"
                                    class="form-control quantity"
                                    min="1"
                                    value="{{ $row['quantity'] }}"
                                    required
                                >
                            </td>

                            <td>
                                <input
                                    type="number"
                                    name="items[{{ $index }}][unit_price]"
                                    class="form-control unit-price"
                                    min="0"
                                    step="0.01"
                                    value="{{ $row['unit_price'] }}"
                                    required
                                >
                            </td>

                            <td>
                                <input
                                    type="number"
                                    class="form-control row-total"
                                    value="{{ number_format($row['quantity'] * $row['unit_price'], 2, '.', '') }}"
                                    readonly
                                >
                            </td>

                            <td>
                                <button type="button" class="btn btn-sm btn-danger remove-row">Remove</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">
                    {{ $isEdit ? 'Update Order' : 'Save Order' }}
                </button>
            </div>
        </form>

    </div>
@endsection

@push('scripts')
    <script>
        let rowIndex = {{ count($orderItems) }};

        function calculateRow(row) {
            const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;

            row.querySelector('.row-total').value = (quantity * unitPrice).toFixed(2);
        }

        function bindRowEvents(row) {
            row.querySelector('.product-select').addEventListener('change', function () {
                const price = this.options[this.selectedIndex].dataset.price || 0;
                row.querySelector('.unit-price').value = parseFloat(price).toFixed(2);
                calculateRow(row);
            });

            row.querySelector('.quantity').addEventListener('input', () => calculateRow(row));
            row.querySelector('.unit-price').addEventListener('input', () => calculateRow(row));

            row.querySelector('.remove-row').addEventListener('click', function () {
                if (document.querySelectorAll('#itemsBody tr').length > 1) {
                    row.remove();
                }
            });
        }

        document.querySelectorAll('#itemsBody tr').forEach(bindRowEvents);

        document.getElementById('addRow').addEventListener('click', function () {
            const firstRow = document.querySelector('#itemsBody tr');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelectorAll('select, input').forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(/\[\d+]/, `[${rowIndex}]`);
                }

                if (input.classList.contains('quantity')) {
                    input.value = 1;
                } else if (input.classList.contains('unit-price') || input.classList.contains('row-total')) {
                    input.value = 0;
                } else {
                    input.value = '';
                }
            });

            document.getElementById('itemsBody').appendChild(newRow);
            bindRowEvents(newRow);
            rowIndex++;
        });
        document.getElementById('submitForm').addEventListener('submit', function (event) {
            const selectedProducts = [];
            let hasDuplicate = false;

            document.querySelectorAll('.product-select').forEach(select => {
                if (!select.value) {
                    return;
                }

                if (selectedProducts.includes(select.value)) {
                    hasDuplicate = true;
                }

                selectedProducts.push(select.value);
            });

            if (hasDuplicate) {
                event.preventDefault();
                let message = 'The same product cannot be added more than once on the same order.';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                });
            }
        });
    </script>
@endpush
