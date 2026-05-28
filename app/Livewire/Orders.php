<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;

class Orders extends Component
{
    public string $customer = '';
    public array $items = [
        ['product_id' => '', 'quantity' => 1],
    ];

    public function addItem(): void
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1];
    }

    public function removeItem(int $index): void
    {
        if (count($this->items) > 1) {
            array_splice($this->items, $index, 1);
            $this->items = array_values($this->items);
        }
    }

    public function decreaseQuantity(int $index): void
    {
        $current = (float) ($this->items[$index]['quantity'] ?? 1);
        $this->items[$index]['quantity'] = max(0.5, $current - 0.5);
    }

    public function saveOrder(): void
    {
        $this->validate([
            'customer'           => 'required|string|max:255',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|numeric|min:0.001',
        ], [
            'customer.required'           => 'Zadaj meno zákazníka.',
            'items.*.product_id.required' => 'Vyber výrobok pre každú položku.',
            'items.*.quantity.min'        => 'Množstvo musí byť kladné číslo.',
        ]);

        $products = Product::whereIn('id', collect($this->items)->pluck('product_id'))
            ->get()
            ->keyBy('id');

        $total = collect($this->items)->sum(
            fn($p) => $products[$p['product_id']]->price * (float) $p['quantity']
        );

        $order = Order::create([
            'customer' => $this->customer,
            'total'    => $total,
            'status'   => 'pending',
        ]);

        foreach ($this->items as $p) {
            $order->items()->create([
                'product_id'     => $p['product_id'],
                'quantity'       => $p['quantity'],
                'price_per_unit' => $products[$p['product_id']]->price,
            ]);
        }

        $this->customer = '';
        $this->items = [['product_id' => '', 'quantity' => 1]];
    }

    public function fulfillOrder(int $id): void
    {
        Order::findOrFail($id)->update(['status' => 'fulfilled']);
    }

    public function returnOrder(int $id): void
    {
        Order::findOrFail($id)->update(['status' => 'pending']);
    }

    public function deleteOrder(int $id): void
    {
        Order::findOrFail($id)->delete();
    }

    public function render()
    {
        $products = Product::all();
        $productMap = $products->keyBy('id');

        $totalPrice = collect($this->items)->sum(function ($p) use ($productMap) {
            $pid = $p['product_id'] ?? '';
            return ($pid && isset($productMap[$pid]))
                ? $productMap[$pid]->price * (float) ($p['quantity'] ?? 0)
                : 0;
        });

        return view('livewire.orders', [
            'products'        => $products,
            'totalPrice'      => $totalPrice,
            'pendingOrders'   => Order::with('items.product')->where('status', 'pending')->latest()->get(),
            'fulfilledOrders' => Order::with('items.product')->where('status', 'fulfilled')->latest()->get(),
        ]);
    }
}
