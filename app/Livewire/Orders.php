<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Livewire\Component;

class Orders extends Component
{
    public string $zakaznik = '';
    public array $polozky = [
        ['vyrobok_id' => '', 'mnozstvo' => 1],
    ];

    public function pridatPolozku(): void
    {
        $this->polozky[] = ['vyrobok_id' => '', 'mnozstvo' => 1];
    }

    public function odstranitPolozku(int $index): void
    {
        if (count($this->polozky) > 1) {
            array_splice($this->polozky, $index, 1);
            $this->polozky = array_values($this->polozky);
        }
    }

    public function znizitMnozstvo(int $index): void
    {
        $current = (float) ($this->polozky[$index]['mnozstvo'] ?? 1);
        $this->polozky[$index]['mnozstvo'] = max(0.5, $current - 0.5);
    }

    public function ulozitZakazku(): void
    {
        $this->validate([
            'zakaznik'               => 'required|string|max:255',
            'polozky'                => 'required|array|min:1',
            'polozky.*.vyrobok_id'   => 'required|exists:vyrobky,id',
            'polozky.*.mnozstvo'     => 'required|numeric|min:0.001',
        ], [
            'zakaznik.required'            => 'Zadaj meno zákazníka.',
            'polozky.*.vyrobok_id.required' => 'Vyber výrobok pre každú položku.',
            'polozky.*.mnozstvo.min'        => 'Množstvo musí byť kladné číslo.',
        ]);

        $products = Product::whereIn('id', collect($this->polozky)->pluck('vyrobok_id'))
            ->get()
            ->keyBy('id');

        $celkom = collect($this->polozky)->sum(
            fn($p) => $products[$p['vyrobok_id']]->cena * (float) $p['mnozstvo']
        );

        $zakazka = Order::create([
            'zakaznik' => $this->zakaznik,
            'celkom'   => $celkom,
            'stav'     => 'nevybavena',
        ]);

        foreach ($this->polozky as $p) {
            $zakazka->polozky()->create([
                'vyrobok_id'       => $p['vyrobok_id'],
                'mnozstvo'         => $p['mnozstvo'],
                'cena_za_jednotku' => $products[$p['vyrobok_id']]->cena,
            ]);
        }

        $this->zakaznik = '';
        $this->polozky = [['vyrobok_id' => '', 'mnozstvo' => 1]];
    }

    public function vybaviZakazku(int $id): void
    {
        Order::findOrFail($id)->update(['stav' => 'vybavena']);
    }

    public function vratZakazku(int $id): void
    {
        Order::findOrFail($id)->update(['stav' => 'nevybavena']);
    }

    public function vymazatZakazku(int $id): void
    {
        Order::findOrFail($id)->delete();
    }

    public function render()
    {
        $vyrobky = Product::all();
        $productMap = $vyrobky->keyBy('id');

        $celkovaCena = collect($this->polozky)->sum(function ($p) use ($productMap) {
            $vid = $p['vyrobok_id'] ?? '';
            return ($vid && isset($productMap[$vid]))
                ? $productMap[$vid]->cena * (float) ($p['mnozstvo'] ?? 0)
                : 0;
        });

        return view('livewire.orders', [
            'vyrobky'           => $vyrobky,
            'celkovaCena'       => $celkovaCena,
            'nevybaveneZakazky' => Order::with('polozky.vyrobok')->where('stav', 'nevybavena')->latest()->get(),
            'vybaveneZakazky'   => Order::with('polozky.vyrobok')->where('stav', 'vybavena')->latest()->get(),
        ]);
    }
}
