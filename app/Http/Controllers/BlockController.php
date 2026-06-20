<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function index()
    {
        $blocks = Block::withCount('units')->latest()->get();
        return view('admin.blocks.index', compact('blocks'));
    }

    public function create()
    {
        return view('admin.blocks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_blok' => 'required|string|max:100|unique:blocks,nama_blok',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        Block::create([
            'nama_blok' => $request->nama_blok,
            'deskripsi' => $request->deskripsi,
            'total_unit' => 0,
            'status' => 'active',
        ]);

        return redirect()->route('admin.blocks.index')
                        ->with('success', 'Blok berhasil ditambahkan!');
    }

    public function destroy(Block $block)
    {
        $block->delete();
        return redirect()->route('admin.blocks.index')
                        ->with('success', 'Blok berhasil dihapus!');
    }

    public function edit(Block $block)
    {
        return view('admin.blocks.edit', compact('block'));
    }

    public function update(Request $request, Block $block)
    {
        $request->validate([
            'nama_blok' => 'required|string|max:100|unique:blocks,nama_blok,' . $block->id,
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $block->update([
            'nama_blok' => $request->nama_blok,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.blocks.index')
                        ->with('success', 'Blok berhasil diupdate!');
    }
}