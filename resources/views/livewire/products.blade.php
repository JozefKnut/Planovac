<div class="bg-[#f4f6fb] min-h-screen p-8">

    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-1">Správa výrobkov</h2>
        <p class="text-gray-500 m-0">Pridaj výrobky, nastav cenu a výrobné náklady</p>
    </div>

    <div class="bg-white rounded-xl shadow-[0_1px_4px_rgba(0,0,0,0.07)] p-6 mb-5">
        <div class="font-semibold text-base mb-4 border-l-[3px] border-l-violet-700 pl-2.5">Pridať nový výrobok</div>
        <div class="flex gap-3 flex-wrap items-end">
            <div class="flex flex-col flex-[2] min-w-[180px]">
                <label class="text-xs font-semibold text-gray-500 uppercase mb-1.5">Názov výrobku</label>
                <input wire:model="name" type="text" placeholder="napr. Chlieb, Stôl, Svíčka…"
                    class="border border-gray-200 rounded-lg px-3.5 py-2.5 text-[0.95rem] outline-none" />
                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="flex flex-col min-w-[140px]">
                <label class="text-xs font-semibold text-gray-500 uppercase mb-1.5">Jednotka</label>
                <select wire:model="unit"
                    class="border border-gray-200 rounded-lg px-3.5 py-2.5 text-[0.95rem] outline-none">
                    <option value="pcs">ks (kus)</option>
                    <option value="kg">kg (kilogram)</option>
                    <option value="m">m (meter)</option>
                    <option value="l">l (liter)</option>
                </select>
            </div>
            <div class="flex flex-col min-w-[160px]">
                <label class="text-xs font-semibold text-gray-500 uppercase mb-1.5">Cena za jednotku (€)</label>
                <input wire:model="price" type="number" placeholder="0.00" min="0" step="0.01"
                    class="border border-gray-200 rounded-lg px-3.5 py-2.5 text-[0.95rem] outline-none" />
                @error('price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="flex flex-col min-w-[160px]">
                <label class="text-xs font-semibold text-gray-500 uppercase mb-1.5">Výrobné náklady (€/j.)</label>
                <input wire:model="costs" type="number" placeholder="0.00" min="0" step="0.01"
                    class="border border-gray-200 rounded-lg px-3.5 py-2.5 text-[0.95rem] outline-none" />
                @error('costs') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <button wire:click="addProduct"
                class="bg-violet-700 text-white border-0 rounded-lg px-5 py-2.5 text-[0.95rem] font-semibold cursor-pointer">
                + Pridať
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-[0_1px_4px_rgba(0,0,0,0.07)] p-6">
        <div class="font-semibold text-base mb-4 border-l-[3px] border-l-violet-700 pl-2.5">Zoznam výrobkov</div>
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Názov</th>
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Jednotka</th>
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Cena</th>
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Náklady</th>
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Zisk / j.</th>
                    <th class="px-3 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-b border-gray-100">
                        <td class="px-3 py-3">{{ $product->name }}</td>
                        <td class="px-3 py-3">{{ $product->unit }}</td>
                        <td class="px-3 py-3">{{ number_format($product->price, 2) }} €</td>
                        <td class="px-3 py-3">{{ number_format($product->costs, 2) }} €</td>
                        <td class="px-3 py-3">{{ number_format($product->price - $product->costs, 2) }} €</td>
                        <td class="px-3 py-3">
                            <button wire:click="deleteProduct({{ $product->id }})"
                                class="text-red-500 bg-transparent border-0 cursor-pointer text-sm">Vymazať</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-400 italic">Zatiaľ žiadne výrobky.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
