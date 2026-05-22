<?php

namespace App\Livewire;

use App\Models\Order;
use Carbon\Carbon;
use Livewire\Component;

class Overview extends Component
{
    public string $filterTyp = 'tyden';
    public string $selTyden  = '';
    public string $selMesiac = '';
    public string $selRok    = '';

    public function mount(): void
    {
        $now = Carbon::now();
        $this->selTyden  = sprintf('%s-W%02d', $now->format('o'), (int) $now->format('W'));
        $this->selMesiac = $now->format('Y-m');
        $this->selRok    = $now->format('Y');
    }

    private function getOrders()
    {
        $query = Order::where('stav', 'vybavena')->with('polozky.vyrobok');

        if ($this->filterTyp === 'tyden' && $this->selTyden) {
            [$year, $weekNum] = explode('-W', $this->selTyden);
            $start = Carbon::now()->setISODate((int) $year, (int) $weekNum)->startOfDay();
            $end   = $start->copy()->addDays(6)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($this->filterTyp === 'mesiac' && $this->selMesiac) {
            $start = Carbon::parse($this->selMesiac . '-01')->startOfMonth();
            $end   = Carbon::parse($this->selMesiac . '-01')->endOfMonth();
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($this->filterTyp === 'rok' && $this->selRok) {
            $query->whereYear('created_at', $this->selRok);
        }

        return $query->get();
    }

    public function render()
    {
        $orders = $this->getOrders();

        $productStats = [];
        foreach ($orders as $order) {
            foreach ($order->polozky as $item) {
                $vid = $item->vyrobok_id;
                if (!isset($productStats[$vid])) {
                    $productStats[$vid] = [
                        'nazov'    => $item->vyrobok->nazov,
                        'jednotka' => $item->vyrobok->jednotka,
                        'mnozstvo' => 0.0,
                        'trzby'    => 0.0,
                        'naklady'  => 0.0,
                    ];
                }
                $productStats[$vid]['mnozstvo'] += (float) $item->mnozstvo;
                $productStats[$vid]['trzby']    += (float) $item->mnozstvo * (float) $item->cena_za_jednotku;
                $productStats[$vid]['naklady']  += (float) $item->mnozstvo * (float) $item->vyrobok->naklady;
            }
        }

        $trzbyTotal   = collect($productStats)->sum('trzby');
        $nakladyTotal = collect($productStats)->sum('naklady');
        $currentYear  = Carbon::now()->year;

        return view('livewire.overview', [
            'pocetZakaziek' => $orders->count(),
            'trzbyTotal'    => $trzbyTotal,
            'nakladyTotal'  => $nakladyTotal,
            'ziskTotal'     => $trzbyTotal - $nakladyTotal,
            'productStats'  => $productStats,
            'roky'          => range($currentYear, max(2024, $currentYear - 5)),
        ]);
    }
}
