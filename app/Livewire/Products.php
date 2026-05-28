<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class Products extends Component
{
    public string $name = '';
    public string $unit = 'pcs';
    public string $price = '';
    public string $costs = '';

    public function addProduct(): void
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'unit'  => 'required|string',
            'price' => 'required|numeric|min:0',
            'costs' => 'required|numeric|min:0',
        ]);

        Product::create([
            'name'  => $this->name,
            'unit'  => $this->unit,
            'price' => $this->price,
            'costs' => $this->costs,
        ]);

        $this->reset(['name', 'unit', 'price', 'costs']);
        $this->unit = 'pcs';
    }

    public function deleteProduct(int $id): void
    {
        Product::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.products', [
            'products' => Product::all(),
        ]);
    }
}
