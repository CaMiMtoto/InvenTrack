<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\ReportService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;

class PurchaseReport extends Component
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

    public function render():\Illuminate\Contracts\View\View
    {
        $purchases = $this->reportService->getPurchaseQueryBuilder($this->startDate, $this->endDate, $this->productId)
            ->get();
        $total_purchase = $purchases->sum('total');
        return view('livewire.purchase-report', compact('purchases', 'total_purchase'));
    }
}
