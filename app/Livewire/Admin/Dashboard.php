<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('System Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'users'           => User::count(),
            'blocks'          => Schema::hasTable('blocks') ? DB::table('blocks')->count() : null,
            'units'           => Schema::hasTable('units') ? DB::table('units')->count() : null,
            'progress_photos' => Schema::hasTable('progress_photos') ? DB::table('progress_photos')->count() : null,
            'legal_documents' => Schema::hasTable('legal_documents') ? DB::table('legal_documents')->count() : null,
        ];

        $recentUsers = User::latest()->take(5)->get();

        return view('livewire.admin.dashboard', compact('stats', 'recentUsers'));
    }
}
