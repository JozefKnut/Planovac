<div style="background: #f4f6fb; min-height: 100vh; padding: 32px;">

    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 4px;">Prehľad</h2>
        <p style="color: #6b7280; margin: 0;">Štatistiky predaja, tržieb a zisku za zvolené obdobie</p>
    </div>

    {{-- Filter tlačidlá --}}
    <div style="display: flex; gap: 4px; margin-bottom: 20px; background: white; border-radius: 10px; padding: 4px; width: fit-content; box-shadow: 0 1px 4px rgba(0,0,0,0.07);">
        @foreach(['tyden' => 'Týždeň', 'mesiac' => 'Mesiac', 'rok' => 'Rok', 'vsetko' => 'Všetko'] as $typ => $label)
            <button wire:click="$set('filterTyp', '{{ $typ }}')"
                style="{{ $filterTyp === $typ ? 'background: #6d28d9; color: white;' : 'background: transparent; color: #374151;' }} border: none; border-radius: 7px; padding: 7px 18px; font-size: 0.9rem; font-weight: {{ $filterTyp === $typ ? '600' : '500' }}; cursor: pointer;">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Výber obdobia --}}
    <div style="background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); padding: 18px 24px; margin-bottom: 20px; display: flex; align-items: center; gap: 14px;">
        @if($filterTyp === 'tyden')
            <label style="font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Vyber týždeň:</label>
            <input type="week" wire:model.live="selTyden"
                style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px 12px; font-size: 0.9rem; outline: none;" />
        @elseif($filterTyp === 'mesiac')
            <label style="font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Vyber mesiac:</label>
            <input type="month" wire:model.live="selMesiac"
                style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px 12px; font-size: 0.9rem; outline: none;" />
        @elseif($filterTyp === 'rok')
            <label style="font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Vyber rok:</label>
            <select wire:model.live="selRok"
                style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px 12px; font-size: 0.9rem; outline: none;">
                @foreach($roky as $rok)
                    <option value="{{ $rok }}">{{ $rok }}</option>
                @endforeach
            </select>
        @else
            <span style="color: #6d28d9; font-size: 0.9rem; font-weight: 600;">Zobrazené všetky zákazky</span>
        @endif
    </div>

    {{-- Stat karty --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px;">
        <div style="background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); padding: 24px; border-top: 3px solid #6d28d9;">
            <div style="font-size: 1.5rem; margin-bottom: 10px;">🧾</div>
            <div style="font-size: 1.9rem; font-weight: 700; color: #111827; margin-bottom: 6px;">{{ $pocetZakaziek }}</div>
            <div style="font-size: 0.72rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Vybavené zákazky</div>
        </div>
        <div style="background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); padding: 24px; border-top: 3px solid #0891b2;">
            <div style="font-size: 1.5rem; margin-bottom: 10px;">💰</div>
            <div style="font-size: 1.9rem; font-weight: 700; color: #111827; margin-bottom: 6px;">{{ number_format($trzbyTotal, 2) }} €</div>
            <div style="font-size: 0.72rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Tržby celkom</div>
        </div>
        <div style="background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); padding: 24px; border-top: 3px solid #d97706;">
            <div style="font-size: 1.5rem; margin-bottom: 10px;">🏭</div>
            <div style="font-size: 1.9rem; font-weight: 700; color: #111827; margin-bottom: 6px;">{{ number_format($nakladyTotal, 2) }} €</div>
            <div style="font-size: 0.72rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Náklady celkom</div>
        </div>
        <div style="background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); padding: 24px; border-top: 3px solid #059669;">
            <div style="font-size: 1.5rem; margin-bottom: 10px;">📈</div>
            <div style="font-size: 1.9rem; font-weight: 700; color: #111827; margin-bottom: 6px;">{{ number_format($ziskTotal, 2) }} €</div>
            <div style="font-size: 0.72rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Zisk (tržby − náklady)</div>
        </div>
    </div>

    {{-- Tabuľka predaja --}}
    <div style="background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); padding: 24px;">
        <div style="font-weight: 600; font-size: 1rem; margin-bottom: 16px; border-left: 3px solid #6d28d9; padding-left: 10px;">Predaj podľa výrobkov</div>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Výrobok</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Predané množstvo</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Tržby</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Náklady</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Zisk</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productStats as $stat)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 12px; font-weight: 500;">{{ $stat['nazov'] }}</td>
                        <td style="padding: 12px 12px;">{{ (float) $stat['mnozstvo'] }} {{ $stat['jednotka'] }}</td>
                        <td style="padding: 12px 12px;">{{ number_format($stat['trzby'], 2) }} €</td>
                        <td style="padding: 12px 12px;">{{ number_format($stat['naklady'], 2) }} €</td>
                        <td style="padding: 12px 12px; font-weight: 600; color: #059669;">{{ number_format($stat['trzby'] - $stat['naklady'], 2) }} €</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 28px; color: #9ca3af; font-style: italic;">Žiadne dáta.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
