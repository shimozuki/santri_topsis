<?php

namespace App\Imports;

use App\Models\Objek;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ObjekImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected $nisnList = [];

    public function model(array $row)
    {
        return new Objek([
            'nisn'    => $row['nisn'],
            'nama'    => $row['nama_siswa'], // pastikan header Excel-nya "nama_siswa"
            'jenjang' => strtoupper($row['jenjang']), // handle kapitalisasi
        ]);
    }

    public function rules(): array
    {
        return [
            'nisn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('objek', 'nisn'),
                function ($attribute, $value, $fail) {
                    if (in_array($value, $this->nisnList)) {
                        $fail('NISN duplikat ditemukan dalam file Excel.');
                    }
                    $this->nisnList[] = $value;
                },
            ],
            'nama_siswa' => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s\.\']+$/u'
            ],
            'jenjang' => [
                'required',
                Rule::in(['SMP', 'SMA'])
            ],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nisn.required'     => 'NISN wajib diisi.',
            'nisn.string'       => 'NISN harus berupa teks.',
            'nisn.max'          => 'NISN maksimal 20 karakter.',
            'nisn.unique'       => 'NISN sudah terdaftar di database.',
            'nama_siswa.required' => 'Nama siswa wajib diisi.',
            'nama_siswa.string'   => 'Nama siswa harus berupa teks.',
            'nama_siswa.regex'    => 'Nama siswa hanya boleh huruf dan spasi.',
            'jenjang.required'  => 'Jenjang wajib dipilih.',
            'jenjang.in'        => 'Jenjang hanya boleh SMP atau SMA.',
        ];
    }
}
