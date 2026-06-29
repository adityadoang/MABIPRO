<?php

namespace App\Livewire\Admin;

use App\Models\Block;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Manajemen Blok')]
class BlockManager extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $isEditMode = false;
    
    public $blockId;
    public $nama_blok;
    public $deskripsi;

    protected function rules()
    {
        return [
            'nama_blok' => ['required', 'string', 'max:100', Rule::unique('blocks', 'nama_blok')->ignore($this->blockId)],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['blockId', 'nama_blok', 'deskripsi']);
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function edit(Block $block)
    {
        $this->resetValidation();
        $this->blockId = $block->id;
        $this->nama_blok = $block->nama_blok;
        $this->deskripsi = $block->deskripsi;
        
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'nama_blok' => $this->nama_blok,
            'deskripsi' => $this->deskripsi,
        ];

        if ($this->isEditMode) {
            Block::find($this->blockId)->update($data);
            session()->flash('success', "Blok {$this->nama_blok} berhasil diperbarui.");
        } else {
            $data['total_unit'] = 0;
            $data['status'] = 'active';
            Block::create($data);
            session()->flash('success', "Blok {$this->nama_blok} berhasil ditambahkan.");
        }

        $this->closeModal();
    }

    public function delete(Block $block)
    {
        if ($block->units()->count() > 0) {
            session()->flash('error', "Tidak dapat menghapus blok {$block->nama_blok} karena masih memiliki unit.");
            return;
        }

        $block->delete();
        session()->flash('success', "Blok {$block->nama_blok} berhasil dihapus.");
    }

    public function render()
    {
        $blocks = Block::withCount('units')->latest()->paginate(10);
        return view('livewire.admin.block-manager', compact('blocks'));
    }
}
