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
    public string $startDate;
    #[Url]
    public string $endDate;
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
        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = date("Y-m-d");
    }
    public function render(): Application|Factory|\Illuminate\Contracts\View\View|View
    {
        $sales = $this->reportService->getSalesQueryBuilder($this->startDate, $this->endDate, $this->productId)->get();
        $totalSales = $sales->sum('total');
        $totalExpenses= $this->reportService->getExpensesQueryBuilder($this->startDate, $this->endDate)->sum(DB::raw('amount'));
        $netProfit= $totalSales - $totalExpenses;
        return view('livewire.sales-report',compact('sales', 'totalSales','totalExpenses','netProfit'));
    }
}
