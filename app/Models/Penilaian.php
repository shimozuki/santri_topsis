<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = "penilaian";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'objek_id',
        'kriteria_id',
        'sub_kriteria_id',
        'user_id',
        'nilai',
    ];

    public function objek()
    {
        return $this->belongsTo(Objek::class, 'objek_id');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function subKriteria()
    {
        return $this->belongsTo(SubKriteria::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
