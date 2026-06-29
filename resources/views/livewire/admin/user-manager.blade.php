<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="font-headline-md text-headline-md font-bold text-primary">Kelola Pengguna</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">Daftar pengguna ({{ $users->total() }})</p>
        </div>
        <button wire:click="openModal" class="bg-primary text-on-primary hover:bg-primary/90 font-label-md font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined">person_add</span>
            Tambah Pengguna
        </button>
    </div>

    <div class="bg-surface-container-low rounded-2xl border border-outline-variant overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left font-body-sm text-on-surface">
                <thead class="bg-surface-container border-b border-outline-variant text-on-surface-variant font-label-md">
                    <tr>
                        <th class="py-3 px-4 font-bold">#</th>
                        <th class="py-3 px-4 font-bold">NAMA PENGGUNA</th>
                        <th class="py-3 px-4 font-bold">EMAIL</th>
                        <th class="py-3 px-4 font-bold text-center">ROLE</th>
                        <th class="py-3 px-4 font-bold">BERGABUNG</th>
                        <th class="py-3 px-4 font-bold text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($users as $index => $user)
                    <tr class="hover:bg-surface-container-high transition-colors">
                        <td class="py-3 px-4 text-on-surface-variant text-xs">{{ $users->firstItem() + $loop->index }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-tertiary flex items-center justify-center font-bold text-on-primary text-xs flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-primary">{{ $user->name }}</div>
                                    @if ($user->id === Auth::id())
                                        <div class="text-[10px] text-on-surface-variant">(Anda)</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">{{ $user->email }}</td>
                        <td class="py-3 px-4 text-center">
                            @php
                                $roleClass = match($user->role) {
                                    'Admin'     => 'bg-primary text-on-primary',
                                    'Marketing' => 'bg-secondary-container text-on-secondary-container',
                                    'Produksi'  => 'bg-tertiary-container text-on-tertiary-container',
                                    'Legalitas' => 'bg-error-container text-on-error-container',
                                    default     => 'bg-surface-container-highest text-on-surface-variant',
                                };
                            @endphp
                            <span class="py-1 px-2 rounded-md font-bold text-[10px] uppercase tracking-wider {{ $roleClass }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-xs text-on-surface-variant">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $user->id }})" class="text-secondary hover:text-primary transition-colors p-1" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                @if ($user->id !== Auth::id())
                                <button wire:click="delete({{ $user->id }})" wire:confirm="Hapus pengguna {{ addslashes($user->name) }}? Tindakan ini tidak dapat dibatalkan." class="text-error hover:text-error/80 transition-colors p-1" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 px-4 text-center text-on-surface-variant flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-outline mb-2">group_off</span>
                            <span>Belum ada pengguna terdaftar</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="p-4 border-t border-outline-variant bg-surface-container-lowest">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-data @keydown.escape.window="$wire.closeModal()">
        <div class="bg-surface rounded-2xl border border-outline-variant shadow-lg w-full max-w-md overflow-hidden flex flex-col max-h-[90vh]" @click.outside="$wire.closeModal()">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                <h3 class="font-headline-sm text-headline-sm font-bold text-primary">
                    {{ $isEditMode ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
                </h3>
                <button wire:click="closeModal" class="text-on-surface-variant hover:text-error transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="p-6 overflow-y-auto">
                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block font-label-md text-on-surface mb-1">Nama Lengkap <span class="text-error">*</span></label>
                        <input type="text" wire:model="name" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface px-4 py-2 focus:ring-primary focus:border-primary transition-colors" placeholder="Masukkan nama" required>
                        @error('name') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-label-md text-on-surface mb-1">Alamat Email <span class="text-error">*</span></label>
                        <input type="email" wire:model="email" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface px-4 py-2 focus:ring-primary focus:border-primary transition-colors" placeholder="email@contoh.com" required>
                        @error('email') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-label-md text-on-surface mb-1">Role <span class="text-error">*</span></label>
                        <select wire:model="role" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface px-4 py-2 focus:ring-primary focus:border-primary transition-colors" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="Admin">Admin</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Produksi">Produksi</option>
                            <option value="Legalitas">Legalitas</option>
                        </select>
                        @error('role') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-label-md text-on-surface mb-1">
                            Password {!! $isEditMode ? '<span class="text-on-surface-variant text-xs font-normal">(Kosongkan jika tidak ingin diubah)</span>' : '<span class="text-error">*</span>' !!}
                        </label>
                        <input type="password" wire:model="password" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface px-4 py-2 focus:ring-primary focus:border-primary transition-colors" placeholder="Minimal 8 karakter" {{ !$isEditMode ? 'required' : '' }}>
                        @error('password') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-label-md text-on-surface mb-1">
                            Konfirmasi Password {!! !$isEditMode ? '<span class="text-error">*</span>' : '' !!}
                        </label>
                        <input type="password" wire:model="password_confirmation" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface px-4 py-2 focus:ring-primary focus:border-primary transition-colors" placeholder="Ulangi password" {{ !$isEditMode ? 'required' : '' }}>
                    </div>

                    <div class="pt-4 border-t border-outline-variant flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 rounded-lg font-label-md font-bold text-on-surface-variant bg-surface-container hover:bg-surface-container-high transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-lg font-label-md font-bold text-on-primary bg-primary hover:bg-primary/90 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">save</span>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
