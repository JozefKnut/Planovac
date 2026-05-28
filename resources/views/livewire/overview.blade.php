<div class="bg-[#f4f6fb] min-h-screen p-4 sm:p-8">

    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-1">Prehľad</h2>
        <p class="text-gray-500 m-0">Štatistiky predaja, tržieb a zisku za zvolené obdobie</p>
    </div>

    {{-- Filter tlačidlá --}}
    <div class="flex flex-wrap gap-1 mb-5 bg-white rounded-[10px] p-1 w-fit shadow-[0_1px_4px_rgba(0,0,0,0.07)]">
        @foreach(['tyden' => 'Týždeň', 'mesiac' => 'Mesiac', 'rok' => 'Rok', 'vsetko' => 'Všetko'] as $typ => $label)
            <button wire:click="$set('filterTyp', '{{ $typ }}')"
                class="border-0 rounded-[7px] px-3 sm:px-4 py-1.5 text-sm cursor-pointer
                    {{ $filterTyp === $typ ? 'bg-violet-700 text-white font-semibold' : 'bg-transparent text-gray-700 font-medium' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Výber obdobia --}}
    <div class="bg-white rounded-xl shadow-[0_1px_4px_rgba(0,0,0,0.07)] px-4 sm:px-6 py-4 mb-5 flex flex-wrap items-center gap-3">
        @if($filterTyp === 'tyden')
            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Vyber týždeň:</label>
            <input type="week" wire:model.live="selTyden"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none w-full sm:w-auto" />
        @elseif($filterTyp === 'mesiac')
            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Vyber mesiac:</label>
            <input type="month" wire:model.live="selMesiac"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none w-full sm:w-auto" />
        @elseif($filterTyp === 'rok')
            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Vyber rok:</label>
            <select wire:model.live="selRok"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none w-full sm:w-auto">
                @foreach($roky as $rok)
                    <option value="{{ $rok }}">{{ $rok }}</option>
                @endforeach
            </select>
        @else
            <span class="text-violet-700 text-sm font-semibold">Zobrazené všetky zákazky</span>
        @endif
    </div>

    {{-- Stat karty --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
        <div class="bg-white rounded-xl shadow-[0_1px_4px_rgba(0,0,0,0.07)] p-6 border-t-[3px] border-t-violet-700">
            <div class="text-2xl mb-2.5">🧾</div>
            <div class="text-3xl font-bold text-gray-900 mb-1.5">{{ $pocetZakaziek }}</div>
            <div class="text-[0.72rem] font-semibold text-gray-500 uppercase tracking-wide">Vybavené zákazky</div>
        </div>
        <div class="bg-white rounded-xl shadow-[0_1px_4px_rgba(0,0,0,0.07)] p-6 border-t-[3px] border-t-cyan-600">
            <div class="text-2xl mb-2.5">💰</div>
            <div class="text-3xl font-bold text-gray-900 mb-1.5">{{ number_format($trzbyTotal, 2) }} €</div>
            <div class="text-[0.72rem] font-semibold text-gray-500 uppercase tracking-wide">Tržby celkom</div>
        </div>
        <div class="bg-white rounded-xl shadow-[0_1px_4px_rgba(0,0,0,0.07)] p-6 border-t-[3px] border-t-amber-600">
            <div class="text-2xl mb-2.5">🏭</div>
            <div class="text-3xl font-bold text-gray-900 mb-1.5">{{ number_format($nakladyTotal, 2) }} €</div>
            <div class="text-[0.72rem] font-semibold text-gray-500 uppercase tracking-wide">Náklady celkom</div>
        </div>
        <div class="bg-white rounded-xl shadow-[0_1px_4px_rgba(0,0,0,0.07)] p-6 border-t-[3px] border-t-emerald-600">
            <div class="text-2xl mb-2.5">📈</div>
            <div class="text-3xl font-bold text-gray-900 mb-1.5">{{ number_format($ziskTotal, 2) }} €</div>
            <div class="text-[0.72rem] font-semibold text-gray-500 uppercase tracking-wide">Zisk (tržby − náklady)</div>
        </div>
    </div>

    {{-- Tabuľka predaja --}}
    <div class="bg-white rounded-xl shadow-[0_1px_4px_rgba(0,0,0,0.07)] p-4 sm:p-6">
        <div class="font-semibold text-base mb-4 border-l-[3px] border-l-violet-700 pl-2.5">Predaj podľa výrobkov</div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse min-w-[500px]">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Výrobok</th>
                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Predané množstvo</th>
                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Tržby</th>
                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Náklady</th>
                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Zisk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productStats as $stat)
                        <tr class="border-b border-gray-100">
                            <td class="px-3 py-3 font-medium">{{ $stat['nazov'] }}</td>
                            <td class="px-3 py-3">{{ (float) $stat['mnozstvo'] }} {{ $stat['jednotka'] }}</td>
                            <td class="px-3 py-3">{{ number_format($stat['trzby'], 2) }} €</td>
                            <td class="px-3 py-3">{{ number_format($stat['naklady'], 2) }} €</td>
                            <td class="px-3 py-3 font-semibold text-emerald-600">{{ number_format($stat['trzby'] - $stat['naklady'], 2) }} €</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-7 text-gray-400 italic">Žiadne dáta.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
