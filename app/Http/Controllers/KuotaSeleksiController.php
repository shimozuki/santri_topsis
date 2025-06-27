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
}
