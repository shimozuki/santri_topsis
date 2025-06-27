<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuotaSeleksi extends Model
{
    use HasFactory;

    protected $table = 'kuota_seleksi';

    protected $fillable = [
        'jenjang',
        'tahun',
        'jumlah_kuota',
    ];

    /**
     * Ambil kuota berdasarkan jenjang dan tahun
     */
    public static function getKuota($jenjang, $tahun)
    {
        return self::where('jenjang', $jenjang)
            ->where('tahun', $tahun)
            ->value('jumlah_kuota');
    }
}
