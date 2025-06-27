<?php

namespace App\Http\Controllers;

use App\Models\KuotaSeleksi;
use Illuminate\Http\Request;

class KuotaSeleksiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenjang' => 'required|string',
            'tahun' => 'required|integer',
            'jumlah_kuota' => 'required|integer|min:1',
        ]);

        KuotaSeleksi::updateOrCreate(
            ['jenjang' => $validated['jenjang'], 'tahun' => $validated['tahun']],
            ['jumlah_kuota' => $validated['jumlah_kuota']]
        );

        return redirect()->back()->with('success', 'Kuota berhasil disimpan.');
    }

    public function edit(Request $request)
    {
        $kuota = KuotaSeleksi::findOrFail($request->id);
        return response()->json([
            $kuota->id,
            $kuota->jenjang,
            $kuota->tahun,
            $kuota->jumlah_kuota
        ]);
    }

    public function updateKuota(Request $request)
    {
        $kuota = KuotaSeleksi::findOrFail($request->id);
        $kuota->jumlah_kuota = $request->jumlah_kuota;
        $kuota->save();

        return response()->json(['message' => 'Kuota berhasil diperbarui.']);
    }
    public function destroy($id)
    {
        KuotaSeleksi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Kuota berhasil dihapus.');
    }
}
