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

    public function index(Request $request)
    {
        $judul = "Penilaian Santri";
        $user = auth()->user();

        // Map nama kriteria ke penguji ke-
        $mapKriteria = [
            'Tes Wawancara' => ['id' => null, 'penguji_ke' => 1],
            'Tes Tulis' => ['id' => null, 'penguji_ke' => 2],
            'Tes Hafalan Qur\'an' => ['id' => null, 'penguji_ke' => 3],
        ];

        // Map kode kriteria ke role
        $mapKodeKriteriaToRole = [
            'C001' => 'penguji_1',
            'C002' => 'penguji_2',
            'C003' => 'penguji_3',
            'C004' => 'admin',
        ];

        $mapRoleNama = array_flip($mapKodeKriteriaToRole);
        $userRoles = $user->roles->pluck('name');
        $userRole = null;

        foreach ($mapRoleNama as $role => $kodeKriteria) {
            if ($userRoles->contains($role)) {
                $userRole = $role;
                break;
            }
        }

        if (!$userRole) {
            abort(403, 'Role penguji tidak dikenali.');
        }

        $kodeKriteria = $mapRoleNama[$userRole];
        $kriteriaAktif = Kriteria::where('kode', $kodeKriteria)->firstOrFail();

        foreach ($mapKriteria as $nama => &$item) {
            $kriteria = Kriteria::where('nama', $nama)->first();
            if ($kriteria) {
                $item['id'] = $kriteria->id;
            }
        }
        unset($item);

        $subKriteria = SubKriteria::where('kriteria_id', $kriteriaAktif->id)->get();

        // Ambil filter jenjang dari URL (ex: ?jenjang=SMA)
        $jenjang = $request->jenjang;

        $objekQuery = Objek::with([
            'penilaian' => function ($q) use ($kriteriaAktif, $user) {
                $q->where('kriteria_id', $kriteriaAktif->id)
                    ->where('user_id', $user->id);
            },
            'penilaian.subKriteria'
        ]);

        if ($jenjang) {
            $objekQuery->where('jenjang', $jenjang);
        }

        $objek = $objekQuery->get();

        return view('dashboard.penilaian.index', compact(
            'judul',
            'objek',
            'mapKriteria',
            'subKriteria',
            'kriteriaAktif',
            'jenjang' // untuk tetap menandai dropdown terpilih
        ));
    }


    // app/Http/Controllers/PenilaianController.php

    public function simpan(Request $request)
    {
        $subIds = $request->sub_kriteria_id;
        $nilaiList = $request->nilai;

        foreach ($subIds as $i => $subId) {
            Penilaian::updateOrCreate(
                [
                    'objek_id' => $request->objek_id,
                    'kriteria_id' => $request->kriteria_id,
                    'sub_kriteria_id' => $subId,
                    'user_id' => auth()->id(),
                ],
                [
                    'nilai' => $nilaiList[$i],
                ]
            );
        }

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
