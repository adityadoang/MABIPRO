<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('block')->latest()->get();
        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        $blocks = Block::all();
        return view('admin.units.create', compact('blocks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'block_id' => 'required|exists:blocks,id',
            'unit_number' => 'required|string|max:50',
            'harga_unit' => 'nullable|numeric|min:0',
        ]);

        Unit::create([
            'block_id' => $request->block_id,
            'unit_number' => $request->unit_number,
            'harga_unit' => $request->harga_unit,
            'status_penjualan' => 'Belum Terjual',
            'progres_pembangunan' => 0,
            'status_legalitas' => 'Belum Lengkap',
        ]);

        return redirect()->route('admin.units.index')
                        ->with('success', 'Unit berhasil ditambahkan!');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('admin.units.index')
                        ->with('success', 'Unit berhasil dihapus!');
    }

    public function edit(Unit $unit)
    {
        $blocks = Block::all();
        return view('admin.units.edit', compact('unit', 'blocks'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'block_id' => 'required|exists:blocks,id',
            'unit_number' => 'required|string|max:50',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'harga_unit' => 'nullable|numeric|min:0',
            'luas_tanah' => 'nullable|numeric|min:0',
            'luas_bangunan' => 'nullable|numeric|min:0',
            'status_penjualan' => 'required|in:Belum Terjual,Sudah DP,Terjual',
        ]);

        $unit->update([
            'block_id' => $request->block_id,
            'unit_number' => $request->unit_number,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'harga_unit' => $request->harga_unit,
            'luas_tanah' => $request->luas_tanah,
            'luas_bangunan' => $request->luas_bangunan,
            'status_penjualan' => $request->status_penjualan,
        ]);

        return redirect()->route('admin.units.index')
                        ->with('success', 'Unit berhasil diupdate!');
    }
}