@extends('layouts.master')

@section('content')
    <div>
        <!--begin::Toolbar-->
        <x-toolbar
            :breadcrumbs="[
        ['label' => 'Purchase Orders', 'url' => route('admin.purchases.index')],
        ['label' => 'New Order', 'url' => '']
    ]"
            title="New Order"
            :actions="[
                 ['label' => 'Go Back', 'url' => route('admin.purchases.index'), 'class'=>'btn-light-primary', 'icon'=>'<i class=\'bi bi-arrow-left fs-4\'></i>']
            ]"
        />
        <!--end::Toolbar-->
        <div>
            <form action="{{ route('admin.purchases.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Supplier</label>
                            <select name="supplier_id" class="form-select">
                                <option value="">-- None --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Purchase Date</label>
                            <input type="date" name="purchase_date" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">

                    </div>
                    <div class="col-md-6">

                    </div>
                </div>


                <hr>
                <h5>Products</h5>
                <div id="items">
                    <div class="row g-3 mb-2 item-row">
                        <div class="col-md-5">
                            <select name="items[0][product_id]" class="form-select" required>
                                <option value="">Select product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="items[0][quantity]" class="form-control qty" placeholder="Qty"
                                   min="1" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="items[0][unit_price]" class="form-control price"
                                   placeholder="Unit Price" step="0.01" required>
                        </div>
                        {{--                        total and action--}}
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <input type="number" name="items[0][total]" class="form-control line-total" placeholder="Total"
                                       step="0.01" required readonly>
                                <button type="button" class="btn btn-icon rounded-pill btn-light-danger "
                                        onclick="this.closest('.item-row').remove();">
                                    <x-lucide-trash class="tw-h-5 tw-w-5"/>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

              <div class="d-flex justify-content-between align-items-center">
                  <button type="button" id="addItem" class="btn btn-sm btn-secondary">+ Add Item</button>
                  <div class="text-end mt-3">
                      <h5>Grand Total: <span id="grandTotal">0.00</span> RWF</h5>
                  </div>
              </div>

                <hr>

                <button type="submit" class="btn btn-success">Save Purchase</button>
            </form>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        let itemIndex = 1;

        // add new item row
        document.getElementById('addItem').addEventListener('click', function () {
            const itemsDiv = document.getElementById('items');
            const row = document.createElement('div');
            row.classList.add('row', 'g-3', 'mb-2', 'item-row');
            row.innerHTML = `
            <div class="col-md-5">
                <select name="items[${itemIndex}][product_id]" class="form-select" required>
                    <option value="">Select product</option>
                    @foreach ($products as $product)
            <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[${itemIndex}][quantity]" class="form-control qty" placeholder="Qty" min="1" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${itemIndex}][unit_price]" class="form-control price" placeholder="Unit Price" step="0.01" required>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <input type="number" name="items[${itemIndex}][total]" class="form-control line-total" placeholder="Total" step="0.01" readonly>
                    <button type="button" class="btn btn-icon rounded-pill btn-light-danger remove-row">
                        <x-lucide-trash class="tw-h-5 tw-w-5"/>
                    </button>
                </div>
            </div>
        `;
            itemsDiv.appendChild(row);
            itemIndex++;
        });

        // delegate event handling for dynamic rows
        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
                const row = e.target.closest('.item-row');
                const qty = parseFloat(row.querySelector('.qty').value) || 0;
                const price = parseFloat(row.querySelector('.price').value) || 0;
                const total = qty * price;
                row.querySelector('.line-total').value = total.toFixed(2);
                updateGrandTotal();
            }
        });

        // remove row
        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('.item-row').remove();
                updateGrandTotal();
            }
        });

        function updateGrandTotal() {
            let grand = 0;
            document.querySelectorAll('.line-total').forEach(input => {
                grand += parseFloat(input.value) || 0;
            });
            document.getElementById('grandTotal').innerText = grand.toFixed(2);
        }
    </script>
@endpush

