<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenilaianRequest;
use App\Http\Services\PenilaianService;
use App\Http\Services\SubKriteriaService;
use App\Models\Kriteria;
use App\Models\Objek;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    protected $penilaianService, $subKriteriaService;

    public function __construct(PenilaianService $penilaianService, SubKriteriaService $subKriteriaService)
    {
        $this->penilaianService = $penilaianService;
        $this->subKriteriaService = $subKriteriaService;
    }

    public function index()
    {
        $judul = "Penilaian Santri";
        $user = auth()->user();

        // Map nama kriteria ke penguji ke-
        $mapKriteria = [
            'Tes Wawancara' => ['id' => null, 'penguji_ke' => 1],
            'Tes Tulis' => ['id' => null, 'penguji_ke' => 2],
            'Tes Hafalan Qur\'an' => ['id' => null, 'penguji_ke' => 3],
        ];

        // Map role user ke nama kriteria
        $mapRoleNama = [
            'penguji_1' => 'Tes Wawancara',
            'penguji_2' => 'Tes Tulis',
            'penguji_3' => 'Tes Hafalan Qur\'an',
        ];

        // Ambil role aktif user
        $userRoles = $user->roles->pluck('name');
        $userRole = null;

        foreach ($mapRoleNama as $role => $namaKriteria) {
            if ($userRoles->contains($role)) {
                $userRole = $role;
                break;
            }
        }

        if (!$userRole) {
            abort(403, 'Role penguji tidak dikenali.');
        }

        $namaKriteria = $mapRoleNama[$userRole];
        $kriteriaAktif = Kriteria::where('nama', $namaKriteria)->firstOrFail();

        // Lengkapi ID kriteria untuk mapKriteria (opsional untuk tampilan)
        foreach ($mapKriteria as $nama => &$item) {
            $kriteria = Kriteria::where('nama', $nama)->first();
            if ($kriteria) {
                $item['id'] = $kriteria->id;
            }
        }
        unset($item); // hapus reference agar aman

        // Ambil semua sub_kriteria untuk kriteria aktif
        $subKriteria = SubKriteria::where('kriteria_id', $kriteriaAktif->id)->get();

        // Ambil semua objek santri dan penilaiannya dari user ini
        $objek = Objek::with([
            'penilaian' => function ($q) use ($kriteriaAktif, $user) {
                $q->where('kriteria_id', $kriteriaAktif->id)
                    ->where('user_id', $user->id);
            },
            'penilaian.subKriteria'
        ])->get();

        return view('dashboard.penilaian.index', compact(
            'judul',
            'objek',
            'mapKriteria',
            'subKriteria',
            'kriteriaAktif'
        ));
    }




    // app/Http/Controllers/PenilaianController.php

    public function simpan(Request $request)
    {
        $request->validate([
            'objek_id' => 'required|exists:objek,id',
            'kriteria_id' => 'required|exists:kriteria,id',
            'sub_kriteria_id' => 'required|exists:sub_kriteria,id',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);
        Penilaian::updateOrCreate(
            [
                'objek_id' => $request->objek_id,
                'kriteria_id' => $request->kriteria_id,
                'sub_kriteria_id' => $request->sub_kriteria_id,
                'user_id' => auth()->id(),
            ],
            [
                'nilai' => $request->nilai,
            ]
        );

        return redirect()->back()->with('success', 'Penilaian berhasil disimpan.');
    }


    public function ubah(Request $request)
    {
        $judul = "Penilaian";

        $data = $this->penilaianService->ubahGetData($request)->first();
        $data2 = $this->penilaianService->ubahGetData($request);
        $subKriteria = $this->subKriteriaService->getAll();

        return view('dashboard.penilaian.edit', [
            "judul" => $judul,
            "data" => $data,
            "data2" => $data2,
            "subKriteria" => $subKriteria,
        ]);
    }

    public function perbarui(Request $request)
    {
        $data = $this->penilaianService->perbaruiPostData($request);
        return redirect('dashboard/penilaian')->with('berhasil', "Data berhasil diperbarui!");
    }
}
