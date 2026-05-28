<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Users extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'user';

    public ?int $editId = null;
    public string $editName = '';
    public string $editEmail = '';
    public string $editPassword = '';
    public string $editRole = 'user';

    public function pridatUzivatela(): void
    {
        $this->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,user',
        ], [
            'name.required'     => 'Zadaj meno.',
            'email.required'    => 'Zadaj e-mail.',
            'email.email'       => 'Neplatný formát e-mailu.',
            'email.unique'      => 'Tento e-mail je už obsadený.',
            'password.required' => 'Zadaj heslo.',
            'password.min'      => 'Heslo musí mať aspoň 8 znakov.',
        ]);

        User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'role'     => $this->role,
        ]);

        $this->reset(['name', 'email', 'password', 'role']);
        $this->role = 'user';
    }

    public function upravitUzivatela(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editId       = $id;
        $this->editName     = $user->name;
        $this->editEmail    = $user->email;
        $this->editPassword = '';
        $this->editRole     = $user->role;
    }

    public function ulozitUpravu(): void
    {
        $this->validate([
            'editName'     => 'required|string|max:255',
            'editEmail'    => "required|email|unique:users,email,{$this->editId}",
            'editPassword' => 'nullable|string|min:8',
            'editRole'     => 'required|in:admin,user',
        ], [
            'editName.required'  => 'Zadaj meno.',
            'editEmail.required' => 'Zadaj e-mail.',
            'editEmail.email'    => 'Neplatný formát e-mailu.',
            'editEmail.unique'   => 'Tento e-mail je už obsadený.',
            'editPassword.min'   => 'Heslo musí mať aspoň 8 znakov.',
        ]);

        $data = [
            'name'  => $this->editName,
            'email' => $this->editEmail,
            'role'  => $this->editRole,
        ];

        if (!empty($this->editPassword)) {
            $data['password'] = Hash::make($this->editPassword);
        }

        User::findOrFail($this->editId)->update($data);

        $this->reset(['editId', 'editName', 'editEmail', 'editPassword', 'editRole']);
        $this->editRole = 'user';
    }

    public function zrusitUpravu(): void
    {
        $this->reset(['editId', 'editName', 'editEmail', 'editPassword', 'editRole']);
        $this->editRole = 'user';
    }

    public function vymazatUzivatela(int $id): void
    {
        if ($id === auth()->id()) {
            return;
        }

        $user = User::findOrFail($id);

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return;
        }

        $user->delete();
    }

    public function render()
    {
        return view('livewire.users', [
            'uzivatelia' => User::orderBy('name')->get(),
        ]);
    }
}
