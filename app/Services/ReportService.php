<?php

namespace App\Services;

use App\Constants\Status;
use App\Models\Expense;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\PurchaseItem;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HigherOrderWhenProxy;

class ReportService
{

    /**
     * @param string $startDate
     * @param string $endDate
     * @param $productId
     * @return HigherOrderWhenProxy|Builder
     */
    public function getPurchaseQueryBuilder(string $startDate, string $endDate, $productId): HigherOrderWhenProxy|Builder
    {
        return PurchaseItem::query()
            ->with(['purchase.supplier', 'product'])
            ->whereHas('purchase', function (Builder $query) use ($endDate, $startDate) {
                $query->when($startDate, function (Builder $query) use ($startDate) {
                    $query->whereDate('purchased_at', '>=', $startDate);
                })->when($endDate, function (Builder $query) use ($endDate) {
                    $query->whereDate('purchased_at', '<=', $endDate);
                });
            })
            ->when($productId, function (Builder $query) use ($productId) {
                $query->where('product_id', '=', $productId);
            });
    }

    public function getSalesQueryBuilder($startDate, $endDate, $productId, $status = null, $doneById = null, $includePurchasePrice = true): OrderItem|Builder|\LaravelIdea\Helper\App\Models\_IH_OrderItem_QB
    {
        // Build base order items query. Optionally include a purchase_price subselect for convenience.
        $qb = OrderItem::query()->with(['order.customer', 'product', 'order.doneBy']);

        if ($includePurchasePrice) {
            $qb->addSelect([
                '*',
                \DB::raw("COALESCE((select AVG(unit_price) from purchase_items where product_id = order_items.product_id),0) as purchase_price"),
            ]);
        } else {
            $qb->select(['order_items.*']);
        }

        // Apply order-level filters
        $qb->whereHas('order', function (Builder $query) use ($status, $startDate, $endDate, $doneById) {
            $query->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('order_date', [$startDate, $endDate]);
            })
                ->when($status, function ($query) use ($status) {
                    $query->where('order_status', '=', $status);
                })
                ->when(is_null($status), function ($query) {
                    $query->where(\DB::raw('LOWER(order_status)'), '!=', strtolower(Status::Cancelled));
                })
                ->when($doneById, function ($query) use ($doneById) {
                    $query->where('created_by', $doneById);
                });
        });

        // Apply product filter on order_items table
        $qb->when($productId, function ($query) use ($productId) {
            $query->where('product_id', '=', $productId);
        });

        return $qb;
    }




    public function getExpensesQueryBuilder($startDate, $endDate, $categoryId = null)
    {
        return Expense::query()
            ->with('category') // Eager load related customer data
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', '=', $categoryId);
            });
    }


}
