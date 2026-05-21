<div style="background: #f4f6fb; min-height: 100vh; padding: 32px;">

    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 4px;">Správa výrobkov</h2>
        <p style="color: #6b7280; margin: 0;">Pridaj výrobky, nastav cenu a výrobné náklady</p>
    </div>

    <div style="background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); padding: 24px; margin-bottom: 20px;">
        <div style="font-weight: 600; font-size: 1rem; margin-bottom: 16px; border-left: 3px solid #6d28d9; padding-left: 10px;">Pridať nový výrobok</div>
        <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
            <div style="display: flex; flex-direction: column; flex: 2; min-width: 180px;">
                <label style="font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 6px;">Názov výrobku</label>
                <input wire:model="nazov" type="text" placeholder="napr. Chlieb, Stôl, Svíčka…" style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 14px; font-size: 0.95rem; outline: none;" />
                @error('nazov') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</span> @enderror
            </div>
            <div style="display: flex; flex-direction: column; min-width: 140px;">
                <label style="font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 6px;">Jednotka</label>
                <select wire:model="jednotka" style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 14px; font-size: 0.95rem; outline: none;">
                    <option value="ks">ks (kus)</option>
                    <option value="kg">kg (kilogram)</option>
                    <option value="m">m (meter)</option>
                    <option value="l">l (liter)</option>
                </select>
            </div>
            <div style="display: flex; flex-direction: column; min-width: 160px;">
                <label style="font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 6px;">Cena za jednotku (€)</label>
                <input wire:model="cena" type="number" placeholder="0.00" min="0" step="0.01" style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 14px; font-size: 0.95rem; outline: none;" />
                @error('cena') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</span> @enderror
            </div>
            <div style="display: flex; flex-direction: column; min-width: 160px;">
                <label style="font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 6px;">Výrobné náklady (€/j.)</label>
                <input wire:model="naklady" type="number" placeholder="0.00" min="0" step="0.01" style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 14px; font-size: 0.95rem; outline: none;" />
                @error('naklady') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</span> @enderror
            </div>
            <button wire:click="pridatVyrobok" style="background: #6d28d9; color: white; border: none; border-radius: 8px; padding: 10px 22px; font-size: 0.95rem; font-weight: 600; cursor: pointer;">
                + Pridať
            </button>
        </div>
    </div>

    <div style="background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); padding: 24px;">
        <div style="font-weight: 600; font-size: 1rem; margin-bottom: 16px; border-left: 3px solid #6d28d9; padding-left: 10px;">Zoznam výrobkov</div>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Názov</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Jednotka</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Cena</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Náklady</th>
                    <th style="text-align: left; padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Zisk / j.</th>
                    <th style="padding: 8px 12px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($vyrobky as $vyrobok)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 12px;">{{ $vyrobok->nazov }}</td>
                        <td style="padding: 12px 12px;">{{ $vyrobok->jednotka }}</td>
                        <td style="padding: 12px 12px;">{{ number_format($vyrobok->cena, 2) }} €</td>
                        <td style="padding: 12px 12px;">{{ number_format($vyrobok->naklady, 2) }} €</td>
                        <td style="padding: 12px 12px;">{{ number_format($vyrobok->cena - $vyrobok->naklady, 2) }} €</td>
                        <td style="padding: 12px 12px;">
                            <button wire:click="vymazatVyrobok({{ $vyrobok->id }})" style="color: #ef4444; background: none; border: none; cursor: pointer; font-size: 0.9rem;">Vymazať</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 24px; color: #9ca3af; font-style: italic;">Zatiaľ žiadne výrobky.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
