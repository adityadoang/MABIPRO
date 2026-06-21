<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard admin.
     */
    public function index()
    {
        $stats = [
            'users'           => \App\Models\User::count(),
            'blocks'          => Schema::hasTable('blocks') ? DB::table('blocks')->count() : null,
            'units'           => Schema::hasTable('units') ? DB::table('units')->count() : null,
            'progress_photos' => Schema::hasTable('progress_photos') ? DB::table('progress_photos')->count() : null,
            'legal_documents' => Schema::hasTable('legal_documents') ? DB::table('legal_documents')->count() : null,
        ];

        $recentUsers = \App\Models\User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }
}
