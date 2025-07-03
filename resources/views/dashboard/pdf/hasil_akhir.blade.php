@extends("dashboard.pdf.layouts.app")

@section("container")
<div class="-mx-3 flex flex-wrap">
    <div class="w-full max-w-full flex-none px-3 table-pdf">

        {{-- KOP SURAT --}}
        <table style="width: 100%; border-bottom: 5px solid black; margin-bottom: 20px;">
            <tr>
                {{-- Logo kiri --}}
                <td style="width: 100px; padding-right: 10px; vertical-align: top;">
                    <img src="{{ $logoBase64 }}" alt="Logo" style="width: 90px; height: auto;">
                </td>

                {{-- Teks kop --}}
                <td style="vertical-align: top;" colspan="6">
                    <div style="font-size: 20px; font-weight: bold; text-transform: uppercase;">
                        YAYASAN NURUL ISLAM SUMBAWA
                    </div>
                    <div style="font-size: 18px; font-weight: bold; text-transform: uppercase;">
                        PENERIMAAN PESERTA DIDIK BARU TAHUN 2024
                    </div>
                    <div style="font-size: 16px; font-weight: bold; text-transform: uppercase;">
                        Pondok Pesantren Aisyah Samawa • Pondok Pesantren Wahyul Qur'an
                    </div>
                    <div style="font-size: 14px;">
                        Jln. Pramuka, RT.003/RW.001, Kel. Brang Biji, Kec. Sumbawa, Kab. Sumbawa, NTB, Indonesia, 84312
                    </div>
                </td>
            </tr>
        </table>



        {{-- JUDUL LAPORAN --}}
        <div class="mb-5 judul-laporan" style="text-align: center;">
            <h1 style="margin-bottom: 1rem;">{{ $judul }}</h1>
        </div>

        {{-- TABEL HASIL --}}
        <div class="shadow-soft-xl relative mb-5 flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border">
            <div style="padding: 1rem 1rem 0;">
                <h2>Hasil Perhitungan TOPSIS Jenjang {{ $jenjang }}</h2>
            </div>
            <div id='recipients' style="padding: 1rem;">
                <table border="1" cellpadding="6" cellspacing="0" style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                    <thead style="background-color: #e5e5e5;">
                        <tr>
                            <th style="width: 10%; text-align: center;">Peringkat</th>
                            <th style="width: 40%; text-align: left;">Nama</th>
                            <th style="width: 25%; text-align: center;">Nilai</th>
                            <th style="width: 25%; text-align: center;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $rank = 1; @endphp
                        @foreach ($hasilTopsis->sortByDesc('nilai') as $item)
                        <tr @if($rank % 2==0) style="background-color: #f9f9f9;" @endif>
                            <td style="text-align: center;">{{ $rank }}</td>
                            <td>{{ $item->nama_objek }}</td>
                            <td style="text-align: center;">{{ round($item->nilai, 3) }}</td>
                            <td style="text-align: center;">
                                @if ($rank <= ($kuota ?? 0))
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
            </div>
        </div>
    </div>
</div>
@endsection