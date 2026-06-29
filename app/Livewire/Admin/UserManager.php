<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('User Management')]
class UserManager extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $isEditMode = false;
    
    public $userId;
    public $name;
    public $email;
    public $role;
    public $password;
    public $password_confirmation;

    protected function rules()
    {
        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'role'  => ['required', Rule::in(['Admin', 'Marketing', 'Produksi', 'Legalitas'])],
        ];

        if (!$this->isEditMode || !empty($this->password)) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        return $rules;
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['userId', 'name', 'email', 'role', 'password', 'password_confirmation']);
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function edit(User $user)
    {
        $this->resetValidation();
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';
        $this->password_confirmation = '';
        
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditMode && $this->userId === auth()->id() && $this->role !== 'Admin' && User::where('role', 'Admin')->count() <= 1) {
            $this->addError('role', 'Tidak dapat mengubah role Anda karena Anda adalah satu-satunya Admin.');
            return;
        }

        $data = [
            'name'  => $this->name,
            'email' => $this->email,
            'role'  => $this->role,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->isEditMode) {
            User::find($this->userId)->update($data);
            session()->flash('success', "User {$this->name} berhasil diperbarui.");
        } else {
            User::create($data);
            session()->flash('success', "User {$this->name} berhasil ditambahkan.");
        }

        $this->closeModal();
    }

    public function delete(User $user)
    {
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            return;
        }

        if ($user->role === 'Admin' && User::where('role', 'Admin')->count() <= 1) {
            session()->flash('error', 'Tidak dapat menghapus satu-satunya akun Admin.');
            return;
        }

        $user->delete();
        session()->flash('success', "User {$user->name} berhasil dihapus.");
    }

    public function render()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('livewire.admin.user-manager', compact('users'));
    }
}
