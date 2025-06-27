<?php

namespace App\Http\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TopsisRepository
{
    // Matriks Keputusan
    public function getMatriksKeputusan()
    {
        $data = DB::table('matriks_keputusan as mp')
            ->join('kriteria as k', 'k.id', 'mp.kriteria_id')
            ->select('mp.*', 'k.nama as nama_kriteria')
            ->orderBy('mp.id', 'asc')->get();

        return $data;
    }
    public function getMatriksKeputusanKriteria($kriteria_id)
    {
        $data = DB::table('matriks_keputusan')->where('kriteria_id', $kriteria_id)->first();
        return $data;
    }
    public function addMatriksKeputusan($data)
    {
        DB::table('matriks_keputusan')->insert([
            'nilai' => $data['nilai'],
            'kriteria_id' => $data['kriteria_id'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
    public function updateMatriksKeputusan($data)
    {
        DB::table('matriks_keputusan')->where('kriteria_id', $data['kriteria_id'])->update([
            'nilai' => $data['nilai'],
            'updated_at' => Carbon::now(),
        ]);
    }

    // Matriks Normalisasi
    public function getMatriksNormalisasi()
    {
        return DB::table('matriks_normalisasi_keputusan as mnk')
            ->join('objek as o', 'o.id', '=', 'mnk.objek_id')
            ->join('kriteria as k', 'k.id', '=', 'mnk.kriteria_id')
            ->select('mnk.*', 'o.nama as nama_objek', 'k.nama as nama_kriteria')
            ->get();
    }
    public function getMatriksNormalisasiKriteriaAlternatif($kriteria_id, $objek_id)
    {
        $data = DB::table('matriks_normalisasi_keputusan')->where('kriteria_id', $kriteria_id)->where('objek_id', $objek_id)->first();
        return $data;
    }
    public function addMatriksNormalisasi($data)
    {
        DB::table('matriks_normalisasi_keputusan')->insert([
            'nilai' => $data['nilai'],
            'objek_id' => $data['objek_id'],
            'kriteria_id' => $data['kriteria_id'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
    public function updateMatriksNormalisasi($data)
    {
        DB::table('matriks_normalisasi_keputusan')->where('kriteria_id', $data['kriteria_id'])->where('objek_id', $data['objek_id'])->update([
            'nilai' => $data['nilai'],
            'updated_at' => Carbon::now(),
        ]);
    }

    // Matriks Y
    public function getMatriksY()
    {
        $data = DB::table('matriks_normalisasi_bobot_keputusan as mnbk')
            ->join('kriteria as k', 'k.id', '=', 'mnbk.kriteria_id')
            ->join('objek as o', 'o.id', '=', 'mnbk.objek_id')
            ->select('mnbk.*', 'k.nama as nama_kriteria', 'o.nama as nama_objek')
            ->orderBy('mnbk.id', 'asc')
            ->get();

        return $data;
    }
    public function getMatriksYKriteriaAlternatif($kriteria_id, $objek_id)
    {
        $data = DB::table('matriks_normalisasi_bobot_keputusan')->where('kriteria_id', $kriteria_id)->where('objek_id', $objek_id)->first();
        return $data;
    }
    public function getMatriksYKriteria($kriteria_id)
    {
        $data = DB::table('matriks_normalisasi_bobot_keputusan')->where('kriteria_id', $kriteria_id)->first();
        return $data;
    }
    public function addMatriksY($data)
    {
        DB::table('matriks_normalisasi_bobot_keputusan')->insert([
            'nilai' => $data['nilai'],
            'objek_id' => $data['objek_id'],
            'kriteria_id' => $data['kriteria_id'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
    public function updateMatriksY($data)
    {
        DB::table('matriks_normalisasi_bobot_keputusan')->where('kriteria_id', $data['kriteria_id'])->where('objek_id', $data['objek_id'])->update([
            'nilai' => $data['nilai'],
            'updated_at' => Carbon::now(),
        ]);
    }

    // Ideal
    public function getIdealPositif()
    {
        $data = DB::table('ideal_positif as ip')
            ->join('kriteria as k', 'k.id', 'ip.kriteria_id')
            ->join('objek as o', 'o.id', 'ip.objek_id')
            ->select('ip.*', 'k.nama as nama_kriteria', 'o.nama as nama_objek')
            ->orderBy('ip.id', 'asc')->get();

        return $data;
    }
    public function getIdealPositifKriteriaAlternatif($kriteria_id, $objek_id)
    {
        $data = DB::table('ideal_positif')->where('kriteria_id', $kriteria_id)->where('objek_id', $objek_id)->first();
        return $data;
    }
    public function addIdealPositif($data)
    {
        DB::table('ideal_positif')->insert([
            'nilai' => $data['nilai'],
            'objek_id' => $data['objek_id'],
            'kriteria_id' => $data['kriteria_id'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
    public function updateIdealPositif($data)
    {
        DB::table('ideal_positif')->where('kriteria_id', $data['kriteria_id'])->where('objek_id', $data['objek_id'])->update([
            'nilai' => $data['nilai'],
            'updated_at' => Carbon::now(),
        ]);
    }
    public function getIdealNegatif()
    {
        $data = DB::table('ideal_negatif as in')
            ->join('kriteria as k', 'k.id', 'in.kriteria_id')
            ->join('objek as o', 'o.id', 'in.objek_id')
            ->select('in.*', 'k.nama as nama_kriteria', 'o.nama as nama_objek')
            ->orderBy('in.id', 'asc')->get();

        return $data;
    }
    public function getIdealNegatifKriteriaAlternatif($kriteria_id, $objek_id)
    {
        $data = DB::table('ideal_negatif')->where('kriteria_id', $kriteria_id)->where('objek_id', $objek_id)->first();
        return $data;
    }
    public function addIdealNegatif($data)
    {
        DB::table('ideal_negatif')->insert([
            'nilai' => $data['nilai'],
            'objek_id' => $data['objek_id'],
            'kriteria_id' => $data['kriteria_id'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
    public function updateIdealNegatif($data)
    {
        DB::table('ideal_negatif')->where('kriteria_id', $data['kriteria_id'])->where('objek_id', $data['objek_id'])->update([
            'nilai' => $data['nilai'],
            'updated_at' => Carbon::now(),
        ]);
    }

    // Solusi Ideal
    public function getSolusiIdealPositif()
    {
        $data = DB::table('solusi_ideal_positif as sip')
            ->join('objek as o', 'o.id', '=', 'sip.objek_id')
            ->select('sip.*', 'o.nama as nama_objek')
            ->orderBy('sip.id', 'asc')
            ->get();

        return $data;
    }

    public function getSolusiIdealPositifKriteria($objek_id)
    {
        $data = DB::table('solusi_ideal_positif')->where('objek_id', $objek_id)->first();
        return $data;
    }
    public function addSolusiIdealPositif($data)
    {
        DB::table('solusi_ideal_positif')->insert([
            'nilai' => $data['nilai'],
            'objek_id' => $data['objek_id'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
    public function updateSolusiIdealPositif($data)
    {
        DB::table('solusi_ideal_positif')->where('objek_id', $data['objek_id'])->update([
            'nilai' => $data['nilai'],
            'updated_at' => Carbon::now(),
        ]);
    }
    public function getSolusiIdealNegatif()
    {
        $data = DB::table('solusi_ideal_negatif as sin')
            ->join('objek as o', 'o.id', 'sin.objek_id')
            ->select('sin.*', 'o.nama as nama_objek')
            ->orderBy('sin.id', 'asc')->get();

        return $data;
    }
    public function getSolusiIdealNegatifKriteria($objek_id)
    {
        $data = DB::table('solusi_ideal_negatif')->where('objek_id', $objek_id)->first();
        return $data;
    }
    public function addSolusiIdealNegatif($data)
    {
        DB::table('solusi_ideal_negatif')->insert([
            'nilai' => $data['nilai'],
            'objek_id' => $data['objek_id'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
    public function updateSolusiIdealNegatif($data)
    {
        DB::table('solusi_ideal_negatif')->where('objek_id', $data['objek_id'])->update([
            'nilai' => $data['nilai'],
            'updated_at' => Carbon::now(),
        ]);
    }

    // Hasil Topsis
    public function getHasilTopsis()
    {
        $data = DB::table('hasil_solusi_topsis as hst')
            ->join('objek as o', 'o.id', 'hst.objek_id')
            ->select('hst.*', 'o.nama as nama_objek')
            ->orderBy('hst.id', 'asc')->get();

        return $data;
    }
    public function getHasilTopsisAlternatif($objek_id)
    {
        $data = DB::table('hasil_solusi_topsis')->where('objek_id', $objek_id)->first();
        return $data;
    }
    public function addHasilTopsis($data)
    {
        DB::table('hasil_solusi_topsis')->insert([
            'nilai' => $data['nilai'],
            'objek_id' => $data['objek_id'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
    public function updateHasilTopsis($data)
    {
        DB::table('hasil_solusi_topsis')->where('objek_id', $data['objek_id'])->update([
            'nilai' => $data['nilai'],
            'updated_at' => Carbon::now(),
        ]);
    }
}
