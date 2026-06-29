<?php

namespace App\Livewire\Admin;

use App\Models\Block;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Manajemen Unit')]
class UnitManager extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $isEditMode = false;
    
    public $unitId;
    public $block_id;
    public $unit_number;
    public $harga_unit;
    
    // Fields for Edit Mode
    public $customer_name;
    public $customer_phone;
    public $luas_tanah;
    public $luas_bangunan;
    public $status_penjualan;

    protected function rules()
    {
        $rules = [
            'block_id' => ['required', 'exists:blocks,id'],
            'unit_number' => ['required', 'string', 'max:50'],
            'harga_unit' => ['nullable', 'numeric', 'min:0'],
        ];

        if ($this->isEditMode) {
            $rules = array_merge($rules, [
                'customer_name' => ['nullable', 'string', 'max:255'],
                'customer_phone' => ['nullable', 'string', 'max:20'],
                'luas_tanah' => ['nullable', 'numeric', 'min:0'],
                'luas_bangunan' => ['nullable', 'numeric', 'min:0'],
                'status_penjualan' => ['required', 'in:Belum Terjual,Sudah DP,Terjual'],
            ]);
        }

        return $rules;
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset([
            'unitId', 'block_id', 'unit_number', 'harga_unit', 
            'customer_name', 'customer_phone', 'luas_tanah', 
            'luas_bangunan', 'status_penjualan'
        ]);
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function edit(Unit $unit)
    {
        $this->resetValidation();
        $this->unitId = $unit->id;
        $this->block_id = $unit->block_id;
        $this->unit_number = $unit->unit_number;
        $this->harga_unit = $unit->harga_unit;
        
        $this->customer_name = $unit->customer_name;
        $this->customer_phone = $unit->customer_phone;
        $this->luas_tanah = $unit->luas_tanah;
        $this->luas_bangunan = $unit->luas_bangunan;
        $this->status_penjualan = $unit->status_penjualan;
        
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'block_id' => $this->block_id,
            'unit_number' => $this->unit_number,
            'harga_unit' => $this->harga_unit,
        ];

        if ($this->isEditMode) {
            $data = array_merge($data, [
                'customer_name' => $this->customer_name,
                'customer_phone' => $this->customer_phone,
                'luas_tanah' => $this->luas_tanah,
                'luas_bangunan' => $this->luas_bangunan,
                'status_penjualan' => $this->status_penjualan,
            ]);
            Unit::find($this->unitId)->update($data);
            session()->flash('success', "Unit {$this->unit_number} berhasil diperbarui.");
        } else {
            $data['status_penjualan'] = 'Belum Terjual';
            $data['progres_pembangunan'] = 0;
            $data['status_legalitas'] = 'Belum Lengkap';
            Unit::create($data);
            session()->flash('success', "Unit {$this->unit_number} berhasil ditambahkan.");
        }

        $this->closeModal();
    }

    public function delete(Unit $unit)
    {
        if ($unit->status_penjualan !== 'Belum Terjual' || $unit->progres_pembangunan > 0) {
            session()->flash('error', "Tidak dapat menghapus unit yang sudah terjual atau memiliki progres pembangunan.");
            return;
        }

        $unit->delete();
        session()->flash('success', "Unit {$unit->unit_number} berhasil dihapus.");
    }

    public function render()
    {
        $units = Unit::with('block')->latest()->paginate(15);
        $blocks = Block::orderBy('nama_blok')->get();
        return view('livewire.admin.unit-manager', compact('units', 'blocks'));
    }
}
