<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="font-headline-md text-headline-md font-bold text-primary">Manajemen Blok</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">Daftar blok properti ({{ $blocks->total() }})</p>
        </div>
        <button wire:click="openModal" class="bg-primary text-on-primary hover:bg-primary/90 font-label-md font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined">add</span>
            Tambah Blok
        </button>
    </div>

    <div class="bg-surface-container-low rounded-2xl border border-outline-variant overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left font-body-sm text-on-surface">
                <thead class="bg-surface-container border-b border-outline-variant text-on-surface-variant font-label-md">
                    <tr>
                        <th class="py-3 px-4 font-bold">#</th>
                        <th class="py-3 px-4 font-bold">NAMA BLOK</th>
                        <th class="py-3 px-4 font-bold text-center">TOTAL UNIT</th>
                        <th class="py-3 px-4 font-bold">DESKRIPSI</th>
                        <th class="py-3 px-4 font-bold text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($blocks as $index => $block)
                    <tr class="hover:bg-surface-container-high transition-colors">
                        <td class="py-3 px-4 text-on-surface-variant text-xs">{{ $blocks->firstItem() + $loop->index }}</td>
                        <td class="py-3 px-4 font-bold text-primary">{{ $block->nama_blok }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="bg-secondary-container text-on-secondary-container px-2 py-1 rounded-full text-xs font-bold">
                                {{ $block->units_count }} Unit
                            </span>
                        </td>
                        <td class="py-3 px-4 text-on-surface-variant max-w-xs truncate" title="{{ $block->deskripsi }}">
                            {{ $block->deskripsi ?: '-' }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $block->id }})" class="text-secondary hover:text-primary transition-colors p-1" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button wire:click="delete({{ $block->id }})" wire:confirm="Hapus blok {{ addslashes($block->nama_blok) }}? Semua data yang terkait mungkin terpengaruh." class="text-error hover:text-error/80 transition-colors p-1" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 px-4 text-center text-on-surface-variant flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-outline mb-2">domain_disabled</span>
                            <span>Belum ada blok yang ditambahkan</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($blocks->hasPages())
            <div class="p-4 border-t border-outline-variant bg-surface-container-lowest">
                {{ $blocks->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-data @keydown.escape.window="$wire.closeModal()">
        <div class="bg-surface rounded-2xl border border-outline-variant shadow-lg w-full max-w-md overflow-hidden flex flex-col max-h-[90vh]" @click.outside="$wire.closeModal()">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                <h3 class="font-headline-sm text-headline-sm font-bold text-primary">
                    {{ $isEditMode ? 'Edit Blok' : 'Tambah Blok Baru' }}
                </h3>
                <button wire:click="closeModal" class="text-on-surface-variant hover:text-error transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="p-6 overflow-y-auto">
                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block font-label-md text-on-surface mb-1">Nama Blok <span class="text-error">*</span></label>
                        <input type="text" wire:model="nama_blok" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface px-4 py-2 focus:ring-primary focus:border-primary transition-colors" placeholder="Misal: Blok A, Flamboyan, dll" required>
                        @error('nama_blok') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-label-md text-on-surface mb-1">Deskripsi</label>
                        <textarea wire:model="deskripsi" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface px-4 py-2 focus:ring-primary focus:border-primary transition-colors" rows="3" placeholder="Opsional..."></textarea>
                        @error('deskripsi') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
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
