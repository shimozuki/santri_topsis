<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\PDF;
use App\Http\Services\TopsisService;
use App\Http\Services\KriteriaService;
use App\Http\Services\PenilaianService;
use App\Models\Objek;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TopsisController extends Controller
{
    protected $topsisServices, $penilaianService, $kriteriaService;

    public function __construct(TopsisService $topsisServices, PenilaianService $penilaianService, KriteriaService $kriteriaService)
    {
        $this->topsisServices = $topsisServices;
        $this->penilaianService = $penilaianService;
        $this->kriteriaService = $kriteriaService;
    }

    public function hasilAkhir(Request $request)
    {
        $judul = "Hasil Akhir";
        $jenjang = $request->get('jenjang');


        $query = DB::table('hasil_solusi_topsis as hst')
            ->join('objek as o', 'o.id', 'hst.objek_id')
            ->select('hst.*', 'o.nama as nama_objek', 'o.jenjang');

        if ($jenjang) {
            $query->whereIn('hst.objek_id', function ($q) use ($jenjang) {
                $q->select('id')->from('objek')->where('jenjang', $jenjang);
            });
        }

        $hasilTopsis = $query->orderBy('hst.id', 'asc')->get();

        return view('dashboard.hasil_akhir.index', compact('judul', 'hasilTopsis', 'jenjang'))
            ->with('batasMinimal', 0.5);
    }

    public function index()
    {
        $judul = "Perhitungan";

        $kriteria = $this->kriteriaService->getAll();
        $penilaian = $this->penilaianService->getAll();
        $matriksKeputusan = $this->topsisServices->getMatriksKeputusan();
        $matriksNormalisasi = $this->topsisServices->getMatriksNormalisasi();
        $matriksY = $this->topsisServices->getMatriksY();
        $solusiIdealPositif = $this->topsisServices->getSolusiIdealPositif();
        $solusiIdealNegatif = $this->topsisServices->getSolusiIdealNegatif();
        $idealPositif = $this->topsisServices->getIdealPositif();
        $idealNegatif = $this->topsisServices->getIdealNegatif();
        $hasilTopsis = $this->topsisServices->getHasilTopsis();

        return view('dashboard.perhitungan.index', [
            'judul' => $judul,
            'kriteria' => $kriteria,
            'penilaian' => $penilaian,
            'matriksKeputusan' => $matriksKeputusan,
            'matriksNormalisasi' => $matriksNormalisasi,
            'matriksY' => $matriksY,
            'idealPositif' => $idealPositif,
            'idealNegatif' => $idealNegatif,
            'solusiIdealPositif' => $solusiIdealPositif,
            'solusiIdealNegatif' => $solusiIdealNegatif,
            'hasilTopsis' => $hasilTopsis,
            'daftarSantri' => Objek::all(),
        ]);
    }

    public function pdf_topsis()
    {
        $judul = 'Laporan Hasil TOPSIS';

        $kriteria = $this->kriteriaService->getAll();
        $penilaian = $this->penilaianService->getAll();
        $matriksKeputusan = $this->topsisServices->getMatriksKeputusan();
        $matriksNormalisasi = $this->topsisServices->getMatriksNormalisasi();
        $matriksY = $this->topsisServices->getMatriksY();
        $solusiIdealPositif = $this->topsisServices->getSolusiIdealPositif();
        $solusiIdealNegatif = $this->topsisServices->getSolusiIdealNegatif();
        $idealPositif = $this->topsisServices->getIdealPositif();
        $idealNegatif = $this->topsisServices->getIdealNegatif();
        $hasilTopsis = $this->topsisServices->getHasilTopsis();

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadview('dashboard.pdf.perhitungan', [
            'judul' => $judul,
            'kriteria' => $kriteria,
            'penilaian' => $penilaian,
            'matriksKeputusan' => $matriksKeputusan,
            'matriksNormalisasi' => $matriksNormalisasi,
            'matriksY' => $matriksY,
            'idealPositif' => $idealPositif,
            'idealNegatif' => $idealNegatif,
            'solusiIdealPositif' => $solusiIdealPositif,
            'solusiIdealNegatif' => $solusiIdealNegatif,
            'hasilTopsis' => $hasilTopsis,
        ]);

        // return $pdf->download('laporan-penilaian.pdf');
        return $pdf->stream();
    }

    public function pdf_hasil()
    {
        $judul = "Laporan Hasil Akhir";
        $hasilTopsis = $this->topsisServices->getHasilTopsis();

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadview('dashboard.pdf.hasil_akhir', [
            'judul' => $judul,
            'hasilTopsis' => $hasilTopsis,
        ]);

        // return $pdf->download('laporan-penilaian.pdf');
        return $pdf->stream();
    }

    public function hitungTopsis()
    {
        $this->hitungMatriksKeputusan();
        $this->hitungMatriksNormalisasi();
        $this->hitungMatriksY();
        $this->hitungIdeal();
        $this->hitungSolusiIdeal();
        $this->hitungHasil();
        return redirect('dashboard/perhitungan')->with('berhasil', "Perhitungan TOPSIS Selesai!");
    }

    public function hitungTopsisSetelahHapus()
    {
        $this->hitungMatriksKeputusan();
        $this->hitungMatriksNormalisasi();
        $this->hitungMatriksY();
        $this->hitungIdeal();
        $this->hitungSolusiIdeal();
        $this->hitungHasil();
    }

    public function hitungMatriksKeputusan()
    {
        $penilaian = $this->penilaianService->getAll();
        $kriteriaList = $penilaian->pluck('kriteria_id')->unique();

        foreach ($kriteriaList as $kriteriaId) {
            $penilaianKriteria = $penilaian->where('kriteria_id', $kriteriaId);
            $objekIds = $penilaianKriteria->pluck('objek_id')->unique();

            $hitungMatriks = 0;

            foreach ($objekIds as $objekId) {
                $rata2 = $penilaianKriteria->where('objek_id', $objekId)->avg('nilai');
                $hitungMatriks += pow($rata2, 2);
            }

            $data = [
                'kriteria_id' => $kriteriaId,
                'nilai' => sqrt($hitungMatriks),
            ];

            $this->topsisServices->simpanMatriksKeputusan($data);
        }
    }

    public function hitungMatriksNormalisasi()
    {
        $penilaian = $this->penilaianService->getAll();

        foreach ($penilaian->unique('kriteria_id') as $item) {
            $penilaianKriteria = $penilaian->where('kriteria_id', $item->kriteria_id);
            $matriksKeputusan = $this->topsisServices->getMatriksKeputusanKriteria($item->kriteria_id);

            // ambil semua objek (santri)
            $objekIds = $penilaianKriteria->pluck('objek_id')->unique();

            foreach ($objekIds as $objekId) {
                $nilaiRata2 = $penilaianKriteria
                    ->where('objek_id', $objekId)
                    ->avg('nilai');

                $matriksNormalisasi = $nilaiRata2 / $matriksKeputusan->nilai;

                $data = [
                    'nilai' => $matriksNormalisasi,
                    'kriteria_id' => $item->kriteria_id,
                    'objek_id' => $objekId,
                ];

                $this->topsisServices->simpanMatriksNormalisasi($data);
            }
        }
    }


    public function hitungMatriksY()
    {
        $matriksNormalisasi = $this->topsisServices->getMatriksNormalisasi();
        foreach ($matriksNormalisasi->unique('kriteria_id') as $item) {
            $matriksNormalisasiKriteria = $matriksNormalisasi->where('kriteria_id', $item->kriteria_id);
            $bobotKriteria = $this->kriteriaService->getDataById($item->kriteria_id);

            foreach ($matriksNormalisasiKriteria as $value) {
                $matriksY = $value->nilai * $bobotKriteria->bobot;
                $data = [
                    'nilai' => $matriksY,
                    'kriteria_id' => $value->kriteria_id,
                    'objek_id' => $value->objek_id,
                ];
                $this->topsisServices->simpanMatriksY($data);
            }
        }
    }

    public function hitungIdeal()
    {
        $solusiIdeal = $this->topsisServices->getMatriksY();
        foreach ($solusiIdeal->unique('kriteria_id') as $item) {
            $solusiIdealKriteria = $solusiIdeal->where('kriteria_id', $item->kriteria_id);

            $solusiIdealA = [];
            foreach ($solusiIdealKriteria as $value) {
                $solusiIdealA[] = $value->nilai;
            }
            $solusiIdealPositif = ['nilai' => max($solusiIdealA), 'kriteria_id' => $item->kriteria_id];
            $solusiIdealNegatif = ['nilai' => min($solusiIdealA), 'kriteria_id' => $item->kriteria_id];

            foreach ($solusiIdealKriteria as $value) {
                $idealPositif = pow($value->nilai - $solusiIdealPositif['nilai'], 2);
                $dataPositif = [
                    'nilai' => $idealPositif,
                    'kriteria_id' => $value->kriteria_id,
                    'objek_id' => $value->objek_id,
                ];
                $this->topsisServices->simpanIdealPositif($dataPositif);

                $idealNegatif = pow($value->nilai - $solusiIdealNegatif['nilai'], 2);
                $dataNegatif = [
                    'nilai' => $idealNegatif,
                    'kriteria_id' => $value->kriteria_id,
                    'objek_id' => $value->objek_id,
                ];
                $this->topsisServices->simpanIdealNegatif($dataNegatif);
            }
        }
    }

    public function hitungSolusiIdeal()
    {
        $jarakIdealPositif = $this->topsisServices->getIdealPositif();
        $jarakIdealNegatif = $this->topsisServices->getIdealNegatif();

        foreach ($jarakIdealPositif as $item) {
            $jarakIdealPositifSi = $jarakIdealPositif->where('objek_id', $item->objek_id);
            $nilaiPositifSi = 0;

            foreach ($jarakIdealPositifSi as $value) {
                $nilaiPositifSi += $value->nilai;
            }
            $data = [
                'nilai' => sqrt($nilaiPositifSi),
                'objek_id' => $item->objek_id,
            ];
            $this->topsisServices->simpanSolusiIdealPositif($data);
        }

        foreach ($jarakIdealNegatif as $item) {
            $jarakIdealNegatifSi = $jarakIdealNegatif->where('objek_id', $item->objek_id);
            $nilaiNegatifSi = 0;

            foreach ($jarakIdealNegatifSi as $value) {
                $nilaiNegatifSi += $value->nilai;
            }
            $data = [
                'nilai' => sqrt($nilaiNegatifSi),
                'objek_id' => $item->objek_id,
            ];
            $this->topsisServices->simpanSolusiIdealNegatif($data);
        }
    }

    public function hitungHasil()
    {
        $solusiIdealPositif = $this->topsisServices->getSolusiIdealPositif();
        $solusiIdealNegatif = $this->topsisServices->getSolusiIdealNegatif();

        $dataPositif = [];
        $dataNegatif = [];
        $hitung = [];

        foreach ($solusiIdealPositif as $item) {
            $dataPositif[] = [
                'objek_id' => $item->objek_id,
                'nilai' => $item->nilai,
            ];
        }

        foreach ($solusiIdealNegatif as $item) {
            $dataNegatif[] = [
                'objek_id' => $item->objek_id,
                'nilai' => $item->nilai,
            ];
        }

        foreach ($dataPositif as $item) {
            foreach ($dataNegatif as $value) {
                if ($value['objek_id'] == $item['objek_id']) {
                    $hitung = [
                        'objek_id' => $item['objek_id'],
                        'nilai' => $value['nilai'] / ($item['nilai'] + $value['nilai']),
                    ];
                }
            }
            $status = $hitung['nilai'] >= 0.75 ? 'DITERIMA' : 'TIDAK DITERIMA';
            $hitung['status'] = $status;
            $this->topsisServices->simpanHasilTopsis($hitung);
            $hitung = [];
        }
    }

    public function approve()
    {
        // Validasi role jika perlu
        if (!auth()->user()->roles->pluck('name')->contains('kepala_sekolah')) {
            abort(403);
        }

        // Update status hasil topsis menjadi "disetujui"
        \DB::table('hasil_solusi_topsis')->update([
            'status' => 'disetujui',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Hasil perhitungan disetujui.');
    }
}
