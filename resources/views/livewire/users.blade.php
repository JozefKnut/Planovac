<div class="bg-[#f4f6fb] min-h-screen p-8">

    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-1">Správa používateľov</h2>
        <p class="text-gray-500 m-0">Pridaj, uprav alebo vymaž používateľov systému</p>
    </div>

    {{-- Formulár na pridanie --}}
    <div class="bg-white rounded-xl shadow-[0_1px_4px_rgba(0,0,0,0.07)] p-6 mb-5">
        <div class="font-semibold text-base mb-4 border-l-[3px] border-l-violet-700 pl-2.5">Pridať nového používateľa</div>
        <div class="flex gap-3 flex-wrap items-end">
            <div class="flex flex-col flex-[2] min-w-[180px]">
                <label class="text-xs font-semibold text-gray-500 uppercase mb-1.5">Meno</label>
                <input wire:model="name" type="text" placeholder="Meno a priezvisko"
                    class="border border-gray-200 rounded-lg px-3.5 py-2.5 text-[0.95rem] outline-none" />
                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="flex flex-col flex-[2] min-w-[180px]">
                <label class="text-xs font-semibold text-gray-500 uppercase mb-1.5">E-mail</label>
                <input wire:model="email" type="email" placeholder="email@priklad.sk"
                    class="border border-gray-200 rounded-lg px-3.5 py-2.5 text-[0.95rem] outline-none" />
                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="flex flex-col min-w-[160px]">
                <label class="text-xs font-semibold text-gray-500 uppercase mb-1.5">Heslo</label>
                <input wire:model="password" type="password" placeholder="Min. 8 znakov"
                    class="border border-gray-200 rounded-lg px-3.5 py-2.5 text-[0.95rem] outline-none" />
                @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="flex flex-col min-w-[140px]">
                <label class="text-xs font-semibold text-gray-500 uppercase mb-1.5">Rola</label>
                <select wire:model="role"
                    class="border border-gray-200 rounded-lg px-3.5 py-2.5 text-[0.95rem] outline-none">
                    <option value="user">Používateľ</option>
                    <option value="admin">Administrátor</option>
                </select>
                @error('role') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <button wire:click="pridatUzivatela"
                class="bg-violet-700 text-white border-0 rounded-lg px-5 py-2.5 text-[0.95rem] font-semibold cursor-pointer whitespace-nowrap">
                + Pridať
            </button>
        </div>
    </div>

    {{-- Zoznam používateľov --}}
    <div class="bg-white rounded-xl shadow-[0_1px_4px_rgba(0,0,0,0.07)] p-6">
        <div class="font-semibold text-base mb-4 border-l-[3px] border-l-violet-700 pl-2.5">Zoznam používateľov</div>
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Meno</th>
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">E-mail</th>
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Rola</th>
                    <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Registrovaný</th>
                    <th class="px-3 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($uzivatelia as $uzivatel)
                    @if($editId === $uzivatel->id)
                        {{-- Riadok s editáciou --}}
                        <tr class="border-b border-violet-100 bg-violet-50">
                            <td class="px-3 py-2">
                                <input wire:model="editName" type="text"
                                    class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm w-full outline-none" />
                                @error('editName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </td>
                            <td class="px-3 py-2">
                                <input wire:model="editEmail" type="email"
                                    class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm w-full outline-none" />
                                @error('editEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </td>
                            <td class="px-3 py-2">
                                <select wire:model="editRole"
                                    class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none">
                                    <option value="user">Používateľ</option>
                                    <option value="admin">Administrátor</option>
                                </select>
                            </td>
                            <td class="px-3 py-2">
                                <input wire:model="editPassword" type="password" placeholder="Nové heslo (nepovinné)"
                                    class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm w-full outline-none" />
                                @error('editPassword') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </td>
                            <td class="px-3 py-2 flex gap-2 items-center">
                                <button wire:click="ulozitUpravu"
                                    class="text-white bg-violet-700 rounded-lg px-3 py-1.5 text-sm border-0 cursor-pointer">Uložiť</button>
                                <button wire:click="zrusitUpravu"
                                    class="text-gray-600 bg-gray-100 rounded-lg px-3 py-1.5 text-sm border-0 cursor-pointer">Zrušiť</button>
                            </td>
                        </tr>
                    @else
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-3 py-3 font-medium">
                                {{ $uzivatel->name }}
                                @if($uzivatel->id === auth()->id())
                                    <span class="text-xs text-violet-600 font-normal ml-1">(ty)</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-gray-600">{{ $uzivatel->email }}</td>
                            <td class="px-3 py-3">
                                @if($uzivatel->role === 'admin')
                                    <span class="inline-block bg-violet-100 text-violet-700 text-xs font-semibold px-2.5 py-1 rounded-full">Administrátor</span>
                                @else
                                    <span class="inline-block bg-gray-100 text-gray-600 text-xs font-semibold px-2.5 py-1 rounded-full">Používateľ</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-gray-500 text-sm">{{ $uzivatel->created_at->format('d.m.Y') }}</td>
                            <td class="px-3 py-3 flex gap-2 items-center">
                                <button wire:click="upravitUzivatela({{ $uzivatel->id }})"
                                    class="text-violet-700 bg-transparent border-0 cursor-pointer text-sm hover:underline">Upraviť</button>
                                @if($uzivatel->id !== auth()->id())
                                    <button wire:click="vymazatUzivatela({{ $uzivatel->id }})"
                                        wire:confirm="Naozaj chceš vymazať tohto používateľa?"
                                        class="text-red-500 bg-transparent border-0 cursor-pointer text-sm hover:underline">Vymazať</button>
                                @endif
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-400 italic">Žiadni používatelia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
