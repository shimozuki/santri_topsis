@extends("dashboard.pdf.layouts.app")

@section("container")
<div class="-mx-3 flex flex-wrap">
    <div class="w-full max-w-full flex-none px-3 table-pdf">

        {{-- Kop Surat --}}
        <table style="width: 100%; border-bottom: 5px solid black; margin-bottom: 20px;">
            <tr>
                <td style="width: 100px;">
                    <img src="{{ $logoBase64 }}" alt="Logo" style="width: 90px;">
                </td>
                <td colspan="6" style="text-align: center;">
                    <div style="font-size: 20px; font-weight: bold;">YAYASAN NURUL ISLAM SUMBAWA</div>
                    <div style="font-size: 18px; font-weight: bold;">PENERIMAAN PESERTA DIDIK BARU TAHUN 2024</div>
                    <div style="font-size: 16px; font-weight: bold;">Pondok Pesantren Aisyah Samawa</div>
                    <div style="font-size: 14px;">Jl. Pramuka, Brang Biji, Sumbawa, NTB</div>
                </td>
            </tr>
        </table>

        <h1 style="text-align: center;">{{ $judul }}</h1>

        @foreach ($hasilPerJenjang as $jenjang => $data)
        @php
        $rank = 1;
        @endphp

        <h3 style="margin-top: 2rem;">Jenjang: {{ $jenjang }}</h3>
        <p>Tahun: {{ now()->year }}</p>

        @if (!$data['kuota'])
        <p style="color: red;">Kuota belum ditentukan untuk jenjang {{ $jenjang }}</p>
        @endif

        <table border="1" cellpadding="6" cellspacing="0" style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 1rem;">
            <thead style="background-color: #e5e5e5;">
                <tr>
                    <th style="width: 10%; text-align: center;">Peringkat</th>
                    <th style="width: 40%;">Nama</th>
                    <th style="width: 25%; text-align: center;">Nilai</th>
                    <th style="width: 25%; text-align: center;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['hasil']->sortByDesc('nilai') as $item)
                <tr @if($rank % 2==0) style="background-color: #f9f9f9;" @endif>
                    <td style="text-align: center;">{{ $rank }}</td>
                    <td>{{ $item->nama_objek }}</td>
                    <td style="text-align: center;">{{ round($item->nilai, 3) }}</td>
                    <td style="text-align: center;">
                        @if ($data['kuota'] && $rank <= $data['kuota'])
                            Diterima
                            @else
                            Tidak Diterima
                            @endif
                            </td>
                </tr>
                @php $rank++; @endphp
                @endforeach
            </tbody>
        </table>
        @endforeach
    </div>
</div>
@endsection