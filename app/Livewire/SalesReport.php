<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\ReportService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

class SalesReport extends Component
{
    #[Url]
    public string $startDate='';
    #[Url]
    public string $endDate='';
    #[Url]
    public $productId;
    public Collection $products;
    protected ReportService $reportService;

    public function __construct()
    {
        $this->reportService = new ReportService();
        $this->products = Product::query()->latest()->get();
    }



    public function mount(): void
    {
       /* $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = date("Y-m-d");*/
    }
    public function render(): Application|Factory|\Illuminate\Contracts\View\View|View
    {
        $builder = $this->reportService->getSalesQueryBuilder($this->startDate, $this->endDate, $this->productId);

        // Permission-based visibility:
        // If the current user does NOT have one of the managerial/dashboard permissions, restrict results to orders they created.
        // Users with VIEW_USER_SALES_PERFORMANCE, VIEW_FINANCIAL_DASHBOARD or VIEW_ADMIN_DASHBOARD can see all records.
        $user = auth()->user();
        $canSeeAll = $user && (
            $user->can(\App\Constants\Permission::VIEW_USER_SALES_PERFORMANCE) ||
            $user->can(\App\Constants\Permission::VIEW_FINANCIAL_DASHBOARD) ||
            $user->can(\App\Constants\Permission::VIEW_ADMIN_DASHBOARD)
        );

        if (!$canSeeAll && $user) {
            $builder->whereHas('order', function(\Illuminate\Database\Eloquent\Builder $q) use ($user) {
                $q->where('created_by', $user->id);
            });
        }

        $sales = $builder->get();

        $totalSales = $sales->sum('total');
        $totalExpenses= $this->reportService->getExpensesQueryBuilder($this->startDate, $this->endDate)->sum(DB::raw('amount'));
        $netProfit= $totalSales - $totalExpenses;

        // Calculate total margin: (sale unit price - purchase price) * quantity
        $totalMargin = $sales->reduce(function($carry, $item) {
            $purchasePrice = $item->purchase_price ?? 0;
            $unitMargin = $item->unit_price - $purchasePrice;
            return $carry + ($unitMargin * $item->quantity);
        }, 0);

        return view('livewire.sales-report',compact('sales', 'totalSales','totalExpenses','netProfit','totalMargin'));
    }
}
