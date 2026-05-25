<div class="bg-[#f4f6fb] min-h-screen p-8">

    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-1">Zákazky</h2>
        <p class="text-gray-500 m-0">Eviduj prijaté objednávky — jeden zákazník môže objednať viac výrobkov</p>
    </div>

    {{-- Nová zákazka --}}
    <div class="bg-white rounded-xl shadow-[0_1px_4px_rgba(0,0,0,0.07)] p-6 mb-5">
        <div class="font-semibold text-base mb-4 border-l-[3px] border-l-violet-700 pl-2.5">Nová zákazka</div>

        <div class="mb-4">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Zákazník</label>
            <input wire:model="zakaznik" type="text" placeholder="Meno zákazníka"
                class="border border-gray-200 rounded-lg px-3.5 py-2.5 text-[0.95rem] outline-none w-[340px] max-w-full" />
            @error('zakaznik') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2.5">
            Výrobky v zákazke
        </div>

        @foreach($polozky as $i => $polozka)
            <div class="flex items-center gap-2.5 mb-2.5 flex-wrap">
                <div class="w-7 h-7 bg-violet-700 text-white rounded-full flex items-center justify-center text-xs font-bold shrink-0">
                    {{ $i + 1 }}
                </div>
                <select wire:model.live="polozky.{{ $i }}.vyrobok_id"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none flex-1 min-w-[180px] max-w-[340px]">
                    <option value="">— Vyber výrobok —</option>
                    @foreach($vyrobky as $v)
                        <option value="{{ $v->id }}">{{ $v->nazov }}</option>
                    @endforeach
                </select>
                <input wire:model.live="polozky.{{ $i }}.mnozstvo" type="number" placeholder="Množstvo"
                    min="0.5" step="0.5"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none w-[110px]" />
                <button wire:click="znizitMnozstvo({{ $i }})"
                    class="bg-gray-100 border border-gray-200 rounded-lg w-[34px] h-9 text-lg cursor-pointer flex items-center justify-center text-gray-700">—</button>
                <button wire:click="odstranitPolozku({{ $i }})"
                    class="bg-red-100 border-0 rounded-lg w-[34px] h-9 text-base cursor-pointer flex items-center justify-center text-red-600">✕</button>
            </div>
            @error("polozky.{$i}.vyrobok_id") <div class="text-red-500 text-xs mb-1.5 pl-9">{{ $message }}</div> @enderror
        @endforeach

        <button wire:click="pridatPolozku"
            class="bg-white text-violet-700 border-[1.5px] border-violet-700 rounded-lg px-4 py-2 text-sm font-semibold cursor-pointer mb-5">
            + Pridať výrobok
        </button>

        <div class="flex justify-end items-center gap-5 pt-4 border-t border-gray-100">
            <div class="text-right">
                <div class="text-[0.72rem] font-semibold text-gray-500 uppercase tracking-wide">Celková cena zákazky</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($celkovaCena, 2, '.', '') }} €</div>
            </div>
            <button wire:click="ulozitZakazku"
                class="bg-violet-700 text-white border-0 rounded-lg px-6 py-3 text-[0.95rem] font-semibold cursor-pointer">
                ✓ Uložiť zákazku
            </button>
        </div>
    </div>

    {{-- Nevybavené zákazky --}}
    <div class="bg-white rounded-xl shadow-[0_1px_4px_rgba(0,0,0,0.07)] p-6 mb-5">
        <div class="font-semibold text-base mb-4 border-l-[3px] border-l-violet-700 pl-2.5">Nevybavené zákazky</div>
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Dátum</th>
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Zákazník</th>
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Výrobky</th>
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Celkom</th>
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Stav</th>
                    <th class="px-3 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($nevybaveneZakazky as $z)
                    <tr class="border-b border-gray-100">
                        <td class="px-3 py-3 whitespace-nowrap">{{ $z->created_at->format('j. n. Y') }}</td>
                        <td class="px-3 py-3 font-medium">{{ $z->zakaznik }}</td>
                        <td class="px-3 py-3">
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($z->polozky as $p)
                                    <span class="bg-violet-100 text-violet-800 rounded-md px-2 py-0.5 text-[0.8rem] font-medium whitespace-nowrap">
                                        {{ $p->vyrobok->nazov }} × {{ (float)$p->mnozstvo }} {{ $p->vyrobok->jednotka }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-3 py-3 font-semibold">{{ number_format($z->celkom, 2) }} €</td>
                        <td class="px-3 py-3">
                            <span class="bg-amber-100 text-amber-600 rounded-full px-2.5 py-0.5 text-[0.72rem] font-bold uppercase tracking-wide">Nevybavené</span>
                        </td>
                        <td class="px-3 py-3">
                            <div class="flex gap-2 justify-end">
                                <button wire:click="vybaviZakazku({{ $z->id }})"
                                    class="bg-emerald-600 text-white border-0 rounded-lg px-3.5 py-1.5 text-sm font-semibold cursor-pointer whitespace-nowrap">
                                    ✓ Vybaviť
                                </button>
                                <button wire:click="vymazatZakazku({{ $z->id }})"
                                    wire:confirm="Naozaj chceš vymazať túto zákazku?"
                                    class="bg-red-100 text-red-600 border-0 rounded-lg px-2.5 py-1.5 text-sm cursor-pointer">✕</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-7 text-gray-400 italic">Žiadne nevybavené zákazky.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Vybavené zákazky --}}
    <div class="bg-white rounded-xl shadow-[0_1px_4px_rgba(0,0,0,0.07)] p-6">
        <div class="font-semibold text-base mb-4 border-l-[3px] border-l-violet-700 pl-2.5">Vybavené zákazky</div>
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Dátum</th>
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Zákazník</th>
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Výrobky</th>
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Celkom</th>
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Stav</th>
                    <th class="px-3 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($vybaveneZakazky as $z)
                    <tr class="border-b border-gray-100">
                        <td class="px-3 py-3 whitespace-nowrap">{{ $z->created_at->format('j. n. Y') }}</td>
                        <td class="px-3 py-3 font-medium">{{ $z->zakaznik }}</td>
                        <td class="px-3 py-3">
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($z->polozky as $p)
                                    <span class="bg-violet-100 text-violet-800 rounded-md px-2 py-0.5 text-[0.8rem] font-medium whitespace-nowrap">
                                        {{ $p->vyrobok->nazov }} × {{ (float)$p->mnozstvo }} {{ $p->vyrobok->jednotka }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-3 py-3 font-semibold">{{ number_format($z->celkom, 2) }} €</td>
                        <td class="px-3 py-3">
                            <span class="bg-emerald-100 text-emerald-600 rounded-full px-2.5 py-0.5 text-[0.72rem] font-bold uppercase tracking-wide">Vybavené</span>
                        </td>
                        <td class="px-3 py-3">
                            <div class="flex gap-2 justify-end">
                                <button wire:click="vratZakazku({{ $z->id }})"
                                    class="bg-white text-indigo-600 border-[1.5px] border-indigo-600 rounded-lg px-3.5 py-1.5 text-sm font-semibold cursor-pointer whitespace-nowrap">
                                    ← Späť
                                </button>
                                <button wire:click="vymazatZakazku({{ $z->id }})"
                                    wire:confirm="Naozaj chceš vymazať túto zákazku?"
                                    class="bg-red-100 text-red-600 border-0 rounded-lg px-2.5 py-1.5 text-sm cursor-pointer">✕</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-7 text-gray-400 italic">Žiadne vybavené zákazky.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
