<?php

namespace App\Livewire;

use App\Models\Order;
use Carbon\Carbon;
use Livewire\Component;

class Overview extends Component
{
    public string $filterType = 'week';
    public string $selWeek    = '';
    public string $selMonth   = '';
    public string $selYear    = '';

    public function mount(): void
    {
        $now = Carbon::now();
        $this->selWeek  = sprintf('%s-W%02d', $now->format('o'), (int) $now->format('W'));
        $this->selMonth = $now->format('Y-m');
        $this->selYear  = $now->format('Y');
    }

    private function getOrders()
    {
        $query = Order::where('status', 'fulfilled')->with('items.product');

        if ($this->filterType === 'week' && $this->selWeek) {
            [$year, $weekNum] = explode('-W', $this->selWeek);
            $start = Carbon::now()->setISODate((int) $year, (int) $weekNum)->startOfDay();
            $end   = $start->copy()->addDays(6)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($this->filterType === 'month' && $this->selMonth) {
            $start = Carbon::parse($this->selMonth . '-01')->startOfMonth();
            $end   = Carbon::parse($this->selMonth . '-01')->endOfMonth();
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($this->filterType === 'year' && $this->selYear) {
            $query->whereYear('created_at', $this->selYear);
        }

        return $query->get();
    }

    public function render()
    {
        $orders = $this->getOrders();

        $productStats = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $pid = $item->product_id;
                if (!isset($productStats[$pid])) {
                    $productStats[$pid] = [
                        'name'     => $item->product->name,
                        'unit'     => $item->product->unit,
                        'quantity' => 0.0,
                        'revenue'  => 0.0,
                        'costs'    => 0.0,
                    ];
                }
                $productStats[$pid]['quantity'] += (float) $item->quantity;
                $productStats[$pid]['revenue']  += (float) $item->quantity * (float) $item->price_per_unit;
                $productStats[$pid]['costs']    += (float) $item->quantity * (float) $item->product->costs;
            }
        }

        $revenueTotal = collect($productStats)->sum('revenue');
        $costsTotal   = collect($productStats)->sum('costs');
        $currentYear  = Carbon::now()->year;

        return view('livewire.overview', [
            'orderCount'   => $orders->count(),
            'revenueTotal' => $revenueTotal,
            'costsTotal'   => $costsTotal,
            'profitTotal'  => $revenueTotal - $costsTotal,
            'productStats' => $productStats,
            'years'        => range($currentYear, max(2024, $currentYear - 5)),
        ]);
    }
}
