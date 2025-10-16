@extends('layouts.master')

@section('title', 'New Stock Adjustment')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">New Stock Adjustment</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.stock-adjustments.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="reason" class="form-label">Reason</label>
                    <textarea name="reason" id="reason" class="form-control" rows="3"></textarea>
                </div>

                <h4>Items</h4>
                <table class="table table-row-dashed table-row-gray-300 gy-7">
                    <thead>
                        <tr class="fw-bolder fs-6 text-gray-800">
                            <th>Product</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody id="items-tbody">
                        <tr>
                            <td>
                                <select name="items[0][product_id]" class="form-control">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="items[0][quantity]" class="form-control" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" id="add-item" class="btn btn-secondary">Add Item</button>
                <hr>
                <button type="submit" class="btn btn-primary">Submit for Approval</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let itemIndex = 1;
        $('#add-item').on('click', function () {
            let newRow = `
                <tr>
                    <td>
                        <select name="items[${itemIndex}][product_id]" class="form-control">
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="items[${itemIndex}][quantity]" class="form-control" />
                    </td>
                </tr>
            `;
            $('#items-tbody').append(newRow);
            itemIndex++;
        });
    </script>
@endpush
