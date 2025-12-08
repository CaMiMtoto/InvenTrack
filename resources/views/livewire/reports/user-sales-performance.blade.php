<div>


    <div class="d-flex justify-content-between align-items-center">
        <x-toolbar title="User Sales Performance" :breadcrumbs="[['label' => 'Sales Performance']]"/>
        <!-- Filter Section -->
        <div class="row mb-4 g-3  align-items-end">
            <div class="col-md-6">
                <label for="startDate" class="form-label">Start Date</label>
                <input type="date" id="startDate" wire:model.lazy="startDate" class="form-control">
            </div>
            <div class="col-md-6">
                <label for="endDate" class="form-label">End Date</label>
                <input type="date" id="endDate" wire:model.lazy="endDate" class="form-control">
            </div>

        </div>


    </div>

    <div class="my-3">
        <!-- Performance Table -->
        <div class="table-responsive">
            <table class="table ps-2 align-middle  rounded table-row-dashed fs-6 gy-2" id="myTable">
                <thead class="table-light">
                <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                    <th scope="col">User</th>
                    <th scope="col">Performance Score</th>
                    <th scope="col">Total Orders</th>
                    <th scope="col">Total Items</th>
                </tr>
                </thead>
                <tbody>
                @forelse($performance as $user)
                    <tr>
                        <td>{{ $user->user_name }}</td>
                        <td>{{ number_format($user->performance_score) }}</td>
                        <td>{{ number_format($user->total_orders) }}</td>
                        <td>{{ number_format($user->total_items) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center text-muted">
                            No performance data found for the selected period.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>


</div>

