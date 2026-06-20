<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Progres - Unit {{ $unit->unit_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <a href="{{ route('production.show', $unit->id) }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Kembali ke Detail Unit
        </a>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Update Progres Unit {{ $unit->unit_number }}</h1>

            <form action="{{ route('production.update', $unit->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Tahap Pembangunan</label>
                    <select name="tahap" class="w-full border border-gray-300 rounded px-3 py-2" required>
                        <option value="">Pilih Tahap</option>
                        <option value="Persiapan Lahan">Persiapan Lahan</option>
                        <option value="Pondasi">Pondasi</option>
                        <option value="Struktur & Dinding">Struktur & Dinding</option>
                        <option value="Pengecatan">Pengecatan</option>
                        <option value="Finishing">Finishing</option>
                        <option value="Serah Terima">Serah Terima</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Persentase (%)</label>
                    <input type="number" name="persentase" min="0" max="100" 
                           class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Catatan</label>
                    <textarea name="catatan" rows="4" 
                              class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Foto Dokumentasi</label>
                    <input type="file" name="foto" accept="image/*" 
                           class="w-full border border-gray-300 rounded px-3 py-2">
                    <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG (Maks. 2MB)</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Simpan Progres
                    </button>
                    <a href="{{ route('production.show', $unit->id) }}" 
                       class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>