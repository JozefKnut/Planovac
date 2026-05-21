<div style="background: #f4f6fb; min-height: 100vh; padding: 32px;">

    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 4px;">Zákazky</h2>
        <p style="color: #6b7280; margin: 0;">Eviduj prijaté objednávky — jeden zákazník môže objednať viac výrobkov</p>
    </div>

    {{-- Nová zákazka --}}
    <div style="background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); padding: 24px; margin-bottom: 20px;">
        <div style="font-weight: 600; font-size: 1rem; margin-bottom: 16px; border-left: 3px solid #6d28d9; padding-left: 10px;">Nová zákazka</div>

        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Zákazník</label>
            <input wire:model="zakaznik" type="text" placeholder="Meno zákazníka"
                style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 14px; font-size: 0.95rem; outline: none; width: 340px; max-width: 100%;" />
            @error('zakaznik') <div style="color: #ef4444; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div style="font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
            Výrobky v zákazke
        </div>

        @foreach($polozky as $i => $polozka)
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; flex-wrap: wrap;">
                <div style="width: 28px; height: 28px; background: #6d28d9; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.82rem; font-weight: 700; flex-shrink: 0;">
                    {{ $i + 1 }}
                </div>
                <select wire:model.live="polozky.{{ $i }}.vyrobok_id"
                    style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 9px 12px; font-size: 0.9rem; outline: none; flex: 1; min-width: 180px; max-width: 340px;">
                    <option value="">— Vyber výrobok —</option>
                    @foreach($vyrobky as $v)
                        <option value="{{ $v->id }}">{{ $v->nazov }}</option>
                    @endforeach
                </select>
                <input wire:model.live="polozky.{{ $i }}.mnozstvo" type="number" placeholder="Množstvo"
                    min="0.5" step="0.5"
                    style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 9px 12px; font-size: 0.9rem; outline: none; width: 110px;" />
                <button wire:click="znizitMnozstvo({{ $i }})"
                    style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; width: 34px; height: 36px; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #374151;">—</button>
                <button wire:click="odstranitPolozku({{ $i }})"
                    style="background: #fee2e2; border: none; border-radius: 8px; width: 34px; height: 36px; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #dc2626;">✕</button>
            </div>
            @error("polozky.{$i}.vyrobok_id") <div style="color: #ef4444; font-size: 0.75rem; margin-bottom: 6px; padding-left: 38px;">{{ $message }}</div> @enderror
        @endforeach

        <button wire:click="pridatPolozku"
            style="background: white; color: #6d28d9; border: 1.5px solid #6d28d9; border-radius: 8px; padding: 8px 18px; font-size: 0.9rem; font-weight: 600; cursor: pointer; margin-bottom: 20px;">
            + Pridať výrobok
        </button>

        <div style="display: flex; justify-content: flex-end; align-items: center; gap: 20px; padding-top: 16px; border-top: 1px solid #f3f4f6;">
            <div style="text-align: right;">
                <div style="font-size: 0.72rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Celková cena zákazky</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #111827;">{{ number_format($celkovaCena, 2, '.', '') }} €</div>
            </div>
            <button wire:click="ulozitZakazku"
                style="background: #6d28d9; color: white; border: none; border-radius: 8px; padding: 12px 24px; font-size: 0.95rem; font-weight: 600; cursor: pointer;">
                ✓ Uložiť zákazku
            </button>
        </div>
    </div>

    {{-- Nevybavené zákazky --}}
    <div style="background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); padding: 24px; margin-bottom: 20px;">
        <div style="font-weight: 600; font-size: 1rem; margin-bottom: 16px; border-left: 3px solid #6d28d9; padding-left: 10px;">Nevybavené zákazky</div>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Dátum</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Zákazník</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Výrobky</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Celkom</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Stav</th>
                    <th style="padding: 8px 12px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($nevybaveneZakazky as $z)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 12px; white-space: nowrap;">{{ $z->created_at->format('j. n. Y') }}</td>
                        <td style="padding: 12px 12px; font-weight: 500;">{{ $z->zakaznik }}</td>
                        <td style="padding: 12px 12px;">
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                @foreach($z->polozky as $p)
                                    <span style="background: #ede9fe; color: #5b21b6; border-radius: 6px; padding: 3px 9px; font-size: 0.8rem; font-weight: 500; white-space: nowrap;">
                                        {{ $p->vyrobok->nazov }} × {{ (float)$p->mnozstvo }} {{ $p->vyrobok->jednotka }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td style="padding: 12px 12px; font-weight: 600;">{{ number_format($z->celkom, 2) }} €</td>
                        <td style="padding: 12px 12px;">
                            <span style="background: #fef3c7; color: #d97706; border-radius: 12px; padding: 3px 10px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px;">Nevybavené</span>
                        </td>
                        <td style="padding: 12px 12px;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <button wire:click="vybaviZakazku({{ $z->id }})"
                                    style="background: #059669; color: white; border: none; border-radius: 8px; padding: 6px 14px; font-size: 0.85rem; font-weight: 600; cursor: pointer; white-space: nowrap;">
                                    ✓ Vybaviť
                                </button>
                                <button wire:click="vymazatZakazku({{ $z->id }})"
                                    wire:confirm="Naozaj chceš vymazať túto zákazku?"
                                    style="background: #fee2e2; color: #dc2626; border: none; border-radius: 8px; padding: 6px 10px; font-size: 0.9rem; cursor: pointer;">✕</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 28px; color: #9ca3af; font-style: italic;">Žiadne nevybavené zákazky.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Vybavené zákazky --}}
    <div style="background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); padding: 24px;">
        <div style="font-weight: 600; font-size: 1rem; margin-bottom: 16px; border-left: 3px solid #6d28d9; padding-left: 10px;">Vybavené zákazky</div>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Dátum</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Zákazník</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Výrobky</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Celkom</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Stav</th>
                    <th style="padding: 8px 12px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($vybaveneZakazky as $z)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 12px; white-space: nowrap;">{{ $z->created_at->format('j. n. Y') }}</td>
                        <td style="padding: 12px 12px; font-weight: 500;">{{ $z->zakaznik }}</td>
                        <td style="padding: 12px 12px;">
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                @foreach($z->polozky as $p)
                                    <span style="background: #ede9fe; color: #5b21b6; border-radius: 6px; padding: 3px 9px; font-size: 0.8rem; font-weight: 500; white-space: nowrap;">
                                        {{ $p->vyrobok->nazov }} × {{ (float)$p->mnozstvo }} {{ $p->vyrobok->jednotka }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td style="padding: 12px 12px; font-weight: 600;">{{ number_format($z->celkom, 2) }} €</td>
                        <td style="padding: 12px 12px;">
                            <span style="background: #d1fae5; color: #059669; border-radius: 12px; padding: 3px 10px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px;">Vybavené</span>
                        </td>
                        <td style="padding: 12px 12px;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <button wire:click="vratZakazku({{ $z->id }})"
                                    style="background: white; color: #4f46e5; border: 1.5px solid #4f46e5; border-radius: 8px; padding: 6px 14px; font-size: 0.85rem; font-weight: 600; cursor: pointer; white-space: nowrap;">
                                    ← Späť
                                </button>
                                <button wire:click="vymazatZakazku({{ $z->id }})"
                                    wire:confirm="Naozaj chceš vymazať túto zákazku?"
                                    style="background: #fee2e2; color: #dc2626; border: none; border-radius: 8px; padding: 6px 10px; font-size: 0.9rem; cursor: pointer;">✕</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 28px; color: #9ca3af; font-style: italic;">Žiadne vybavené zákazky.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
