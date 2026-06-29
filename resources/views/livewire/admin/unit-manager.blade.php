<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="font-headline-md text-headline-md font-bold text-primary">Manajemen Unit</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">Daftar unit properti ({{ $units->total() }})</p>
        </div>
        <button wire:click="openModal" class="bg-primary text-on-primary hover:bg-primary/90 font-label-md font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined">add</span>
            Tambah Unit
        </button>
    </div>

    <div class="bg-surface-container-low rounded-2xl border border-outline-variant overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left font-body-sm text-on-surface">
                <thead class="bg-surface-container border-b border-outline-variant text-on-surface-variant font-label-md">
                    <tr>
                        <th class="py-3 px-4 font-bold">#</th>
                        <th class="py-3 px-4 font-bold">BLOK</th>
                        <th class="py-3 px-4 font-bold">NO. UNIT</th>
                        <th class="py-3 px-4 font-bold">HARGA (Rp)</th>
                        <th class="py-3 px-4 font-bold text-center">STATUS</th>
                        <th class="py-3 px-4 font-bold text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($units as $index => $unit)
                    <tr class="hover:bg-surface-container-high transition-colors">
                        <td class="py-3 px-4 text-on-surface-variant text-xs">{{ $units->firstItem() + $loop->index }}</td>
                        <td class="py-3 px-4 font-bold">{{ $unit->block->nama_blok ?? '-' }}</td>
                        <td class="py-3 px-4 font-bold text-primary">{{ $unit->unit_number }}</td>
                        <td class="py-3 px-4">{{ number_format($unit->harga_unit, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-center">
                            @php
                                $statusClass = match($unit->status_penjualan) {
                                    'Terjual' => 'bg-primary text-on-primary',
                                    'Sudah DP' => 'bg-secondary-container text-on-secondary-container',
                                    default => 'bg-surface-container-highest text-on-surface-variant',
                                };
                            @endphp
                            <span class="py-1 px-2 rounded-md font-bold text-[10px] uppercase tracking-wider {{ $statusClass }}">
                                {{ $unit->status_penjualan }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $unit->id }})" class="text-secondary hover:text-primary transition-colors p-1" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button wire:click="delete({{ $unit->id }})" wire:confirm="Hapus unit {{ addslashes($unit->unit_number) }}? Tindakan ini tidak dapat dibatalkan." class="text-error hover:text-error/80 transition-colors p-1" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 px-4 text-center text-on-surface-variant flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-outline mb-2">home</span>
                            <span>Belum ada unit yang ditambahkan</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($units->hasPages())
            <div class="p-4 border-t border-outline-variant bg-surface-container-lowest">
                {{ $units->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-data @keydown.escape.window="$wire.closeModal()">
        <div class="bg-surface rounded-2xl border border-outline-variant shadow-lg w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]" @click.outside="$wire.closeModal()">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                <h3 class="font-headline-sm text-headline-sm font-bold text-primary">
                    {{ $isEditMode ? 'Edit Unit' : 'Tambah Unit Baru' }}
                </h3>
                <button wire:click="closeModal" class="text-on-surface-variant hover:text-error transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="p-6 overflow-y-auto">
                <form wire:submit="save" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-label-md text-on-surface mb-1">Blok <span class="text-error">*</span></label>
                            <select wire:model="block_id" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface px-4 py-2 focus:ring-primary focus:border-primary transition-colors" required>
                                <option value="">-- Pilih Blok --</option>
                                @foreach($blocks as $block)
                                    <option value="{{ $block->id }}">{{ $block->nama_blok }}</option>
                                @endforeach
                            </select>
                            @error('block_id') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-label-md text-on-surface mb-1">Nomor Unit <span class="text-error">*</span></label>
                            <input type="text" wire:model="unit_number" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface px-4 py-2 focus:ring-primary focus:border-primary transition-colors" required>
                            @error('unit_number') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block font-label-md text-on-surface mb-1">Harga Unit (Rp)</label>
                        <input type="number" wire:model="harga_unit" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface px-4 py-2 focus:ring-primary focus:border-primary transition-colors" min="0">
                        @error('harga_unit') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    @if($isEditMode)
                    <hr class="border-outline-variant my-4">
                    <h4 class="font-label-lg font-bold text-primary mb-2">Informasi Tambahan (Edit Mode)</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-label-md text-on-surface mb-1">Status Penjualan <span class="text-error">*</span></label>
                            <select wire:model="status_penjualan" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface px-4 py-2 focus:ring-primary focus:border-primary transition-colors" required>
                                <option value="Belum Terjual">Belum Terjual</option>
                                <option value="Sudah DP">Sudah DP</option>
                                <option value="Terjual">Terjual</option>
                            </select>
                            @error('status_penjualan') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div></div> <!-- empty col -->

                        <div>
                            <label class="block font-label-md text-on-surface mb-1">Nama Konsumen</label>
                            <input type="text" wire:model="customer_name" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface px-4 py-2 focus:ring-primary focus:border-primary transition-colors">
                            @error('customer_name') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-label-md text-on-surface mb-1">No. HP Konsumen</label>
                            <input type="text" wire:model="customer_phone" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface px-4 py-2 focus:ring-primary focus:border-primary transition-colors">
                            @error('customer_phone') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-label-md text-on-surface mb-1">Luas Tanah (m&sup2;)</label>
                            <input type="number" wire:model="luas_tanah" step="0.01" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface px-4 py-2 focus:ring-primary focus:border-primary transition-colors">
                            @error('luas_tanah') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-label-md text-on-surface mb-1">Luas Bangunan (m&sup2;)</label>
                            <input type="number" wire:model="luas_bangunan" step="0.01" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface px-4 py-2 focus:ring-primary focus:border-primary transition-colors">
                            @error('luas_bangunan') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @endif

                    <div class="pt-4 border-t border-outline-variant flex justify-end gap-3 mt-6">
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
