@extends('layouts.master')
@section('title', 'New Purchase')
@section('styles')

@endsection
@section('content')
    <div>
        <!--begin::Toolbar-->
        <x-toolbar title="New Purchase"
                   :breadcrumbs="[
   ['label'=>'Purchase Orders','url'=>route('admin.purchases.index')],
   ['label'=>'New Order']
]" :actions="[
    ['label'=>'Back to List','url'=>route('admin.purchases.index'),'class'=>'btn-light-secondary','icon'=>'<i class=\'bi bi-arrow-left fs-4\'></i>']
]"/>
        <!--end::Toolbar-->

    <ul>
        @foreach($errors as $error)
            <li>
                {{ $error }}
            </li>
        @endforeach
    </ul>


        <!--begin::Content-->
        <form action="{{ route('admin.purchases.store') }}" method="post" class="my-3" autocapitalize="off">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="delivery_date" class="form-label">
                            Delivery Date
                        </label>
                        <input type="date" class="form-control" id="purchased_at" name="purchased_at">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select class="form-select" id="supplier_id" name="supplier_id">
                            <option value=""></option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}
                                    - {{ $supplier->address }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="my-3">
                <h4>Items</h4>
                <p>
                    Below are the items that you can add to the purchase order. You can add multiple items to the
                    purchase order.
                </p>
            </div>
            <div class="table-responsive min-vh-100">
                <table class="table table-borderless table-hover table-striped ">
                    <thead>
                    <tr class="text-uppercase fw-semibold">
                        <th>Product</th>
                        <th>Exp.Date</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <select class="form-select js-product min-w-200px" name="product_ids[]" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-id="{{ $product->id }}"
                                            data-quantity="{{ $product->stock_quantity }}"
                                            data-price="{{ $product->price }}">
                                        {{ $product->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="date" class="form-control  tw-w-44"
                                   name="exp_dates[]"/>
                        </td>
                        <td>
                            <input type="number" class="form-control  js-price tw-w-44" required
                                   name="prices[]"/>
                        </td>
                        <td>
                            <input type="number" class="form-control  js-qty tw-w-32" required step="0.01"
                                   name="quantities[]"/>
                        </td>
                        <td>
                            <input type="text" disabled class="form-control  js-total" name="total"/>
                        </td>
                        <td>
                            <button class="btn  btn-icon  btn-light-danger js-remove">
                                <i class="bi bi-trash fs-1"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5" class="text-end">
                            <h4>Total:</h4>
                            <span id="js-total-order">0</span>
                        </td>
                    </tr>
                    </tfoot>
                </table>

                <div class="my-3 d-flex justify-content-between align-items-center">
                    <button class="btn btn-sm btn-light-primary" id="addRow" type="button">
                        <i class="bi bi-plus"></i>
                        New Row
                    </button>
                    <button class="btn btn-sm btn-primary" type="submit">
                        <i class="bi bi-save"></i>
                        Save Purchase
                    </button>
                </div>

            </div>


        </form>
        <!--end::Content-->
    </div>
@endsection
@push('scripts')

    <script>
        $(function () {
            $('#addRow').on('click', function () {
                let uniqueId = new Date().getTime();
                let row = `
                    <tr>
                        <td>
                            <select class="form-select min-w-200px js-product" name="product_ids[]" required id="product_ids_${uniqueId}">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                <option value="{{ $product->id }}" data-id="{{ $product->id }}"
                                            data-price="{{ $product->price }}" >{{ $product->name }} </option>
                                @endforeach
                </select>
            </td>
             <td>
                <input type="date" class="form-control   tw-w-44" name="exp_dates[]"/>
            </td>
             <td>
                <input type="number" class="form-control  js-price tw-w-44" name="prices[]" required/>
            </td>
            <td>
                <input type="number" class="form-control  js-qty  tw-w-32" name="quantities[]" required  step="0.01"/>
            </td>
            <td>
                <input type="text" disabled class="form-control  js-total" name="total"/>
            </td>
            <td>
                <button class="btn  btn-icon  btn-light-danger js-remove">
                    <i class="bi bi-trash fs-1"></i>
                </button>
            </td>
        </tr>
`;
                $('table tbody').append(row);
                $('#product_ids_' + uniqueId).selectize();
            });

            let $table = $('table');
            $table.on('click', '.js-remove', function () {
                $(this).closest('tr').remove();
            });

            const jsTotalOrder = document.getElementById('js-total-order');

            const updateTotalOrder = () => {
                let total = 0;
                let prices = $table.find('.js-price');
                let quantities = $table.find('.js-qty');
                for (let i = 0; i < prices.length; i++) {
                    let price = Number(prices[i].value);
                    let quantity = Number(quantities[i].value);
                    let totalPrice = price * quantity;
                    total += totalPrice;
                }
                jsTotalOrder.textContent = Number(total).toLocaleString();
            }

            $table.on('change', '.js-qty, .js-price', function () {
                let qty = $(this).closest('tr').find('.js-qty').val();
                let price = $(this).closest('tr').find('.js-price').val();

                let total = qty * price;
                $(this).closest('tr').find('.js-total').val(Number(total).toLocaleString());
                updateTotalOrder();
            });


            $table.on('change', '.js-product', function () {

                // Get the selected product ID
                let productId = $(this).val();
                if (!productId) {
                    return;
                }

                // Check for duplicate products (exclude the current row)
                let duplicateFound = false;
                $table.find('.js-product').not(this).each(function () {
                    if (Number($(this).val()) === Number(productId)) {
                        duplicateFound = true;
                        return false; // exit loop
                    }
                });

                if (duplicateFound) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Product already selected!',
                    });
                    // Optionally reset the current product selection to prevent duplicates
                    $(this).val('').trigger('change');
                }
            });


        });
    </script>
@endpush
