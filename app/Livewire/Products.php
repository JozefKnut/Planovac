<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class Products extends Component
{
    public string $nazov = '';
    public string $jednotka = 'ks';
    public string $cena = '';
    public string $naklady = '';

    public function pridatVyrobok(): void
    {
        $this->validate([
            'nazov' => 'required|string|max:255',
            'jednotka' => 'required|string',
            'cena' => 'required|numeric|min:0',
            'naklady' => 'required|numeric|min:0',
        ]);

        Product::create([
            'nazov' => $this->nazov,
            'jednotka' => $this->jednotka,
            'cena' => $this->cena,
            'naklady' => $this->naklady,
        ]);

        $this->reset(['nazov', 'jednotka', 'cena', 'naklady']);
        $this->jednotka = 'ks';
    }

    public function vymazatVyrobok(int $id): void
    {
        Product::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.products', [
            'vyrobky' => Product::all(),
        ]);
    }
}
