<?php

namespace App\Livewire\Production;

use App\Models\Block;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Production Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $blocks = Block::with([
            'units' => fn($q) => $q->select('id', 'block_id', 'unit_number', 'progres_pembangunan', 'status_penjualan'),
            'units.constructionProgress' => fn($q) => $q->select('id', 'unit_id', 'tahap', 'persentase', 'created_at')->orderBy('created_at', 'desc')
        ])->get();

        return view('livewire.production.dashboard', compact('blocks'));
    }
}
