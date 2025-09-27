<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect('admin/dashboard');
    }

    public function dashboard(Request $request, DashboardService $dashboardService)
    {
        $user = auth()->user();
        $summary = $dashboardService->getSummary($user, $request->from, $request->to);
        $chartData = $this->getDailyChartData($user, $request->from, $request->to);
        $flowChartData = $this->getPaymentFlowChartData($user, $request->from, $request->to);

        return view('admin.dashboard', [
            'summary' => $summary,
            'chartData' => $chartData,
            'flowChartData' => $flowChartData,
            'topMerchants' => $this->getTopMerchants(),
        ]);
    }

    private function getDailyChartData($user, $from = null, $to = null): array
    {

        return [
            'labels' => ['2024-06-01', '2024-06-02', '2024-06-03', '2024-06-04', '2024-06-05', '2024-06-06', '2024-06-07'],
            'airtime' => [1000, 1500, 2000, 2500, 3000, 3500, 4000],
            'water' => [2000, 2500, 3000, 3500, 4000, 4500, 5000],
            'electricity' => [3000, 3500, 4000, 4500, 5000, 5500, 6000]
        ];
    }

    private function getPaymentFlowChartData($user, $from = null, $to = null): array
    {


        return [
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'received' => [500, 600, 700, 800, 900, 1000, 1100],
            'disbursed' => [300, 400, 500, 600, 700, 800, 900]
        ];
    }

    private function getTopMerchants(): array
    {
        return [
            ['name' => 'Merchant A', 'total_transactions' => 150, 'total_amount' => 75000],
            ['name' => 'Merchant B', 'total_transactions' => 120, 'total_amount' => 60000],
            ['name' => 'Merchant C', 'total_transactions' => 100, 'total_amount' => 50000],
        ];
    }


}
