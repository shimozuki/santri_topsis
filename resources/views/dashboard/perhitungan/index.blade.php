@extends('dashboard.layouts.app')

@section('container')
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        @if(Auth::user()->roles->pluck('name')->contains('admin'))
        <div class="mb-5 flex gap-x-1">
            <form action="{{ 'hitung_topsis' }}" method="post" enctype="multipart/form-data">
                @csrf
                <button type="submit" class="btn btn-active btn-accent text-white hover:bg-accent/95 hover:border-accent/95">Hitung TOPSIS</button>
            </form>
        </div>
        @endif

        {{-- Tabel Bobot Kriteria --}}
        <div class="relative flex flex-col min-w-0 mb-5 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex flex-row items-center justify-between p-6 pb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6>Bobot Kriteria <span class="text-greenPrimary">(W)</span></h6>
            </div>
            <div id='recipients' class="p-8 rounded shadow bg-white">
                <table id="tabel_data_bobot" class="stripe hover" style="width:100%; padding-bottom: 1em;">
                    <thead>
                        <tr>
                            @foreach ($kriteria as $item)
                            <th>{{ $item->nama }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($kriteria as $item)
                            <td>{{ $item->bobot }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Penilaian --}}
        <div class="relative flex flex-col min-w-0 mb-5 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex flex-row items-center justify-between p-6 pb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6>Penilaian</h6>
            </div>
            <div id='recipients' class="p-8 rounded shadow bg-white">
                <table id="tabel_data_penilaian" class="stripe hover" style="width:100%; padding-bottom: 1em;">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            @foreach ($penilaian->pluck('kriteria_id')->unique() as $kriteriaId)
                            <th>{{ $penilaian->firstWhere('kriteria_id', $kriteriaId)->kriteria->nama }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penilaian->groupBy('objek_id') as $objekId => $penilaianSantri)
                        <tr>
                            <td>{{ $penilaianSantri->first()->objek->nama }}</td>
                            @foreach ($penilaian->pluck('kriteria_id')->unique() as $kriteriaId)
                            @php
                            $nilaiRata2 = $penilaianSantri
                            ->where('kriteria_id', $kriteriaId)
                            ->avg('nilai');
                            @endphp
                            <td>{{ round($nilaiRata2, 2) }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Matriks Keputusan --}}
        <div class="relative flex flex-col min-w-0 mb-5 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex flex-row items-center justify-between p-6 pb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6>Matriks Keputusan <span class="text-greenPrimary">(X)</span></h6>
            </div>
            <div id='recipients' class="p-8 rounded shadow bg-white">
                <table id="tabel_data_matriks_keputusan" class="stripe hover" style="width:100%; padding-bottom: 1em;">
                    <thead>
                        <tr>
                            <th>Nama Santri</th>
                            @foreach ($kriteria as $k)
                            <th>{{ $k->nama }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarSantri as $santri)
                        <tr>
                            <td>{{ $santri->nama }}</td>
                            @foreach ($kriteria as $k)
                            @php
                            // cari nilai rata-rata untuk objek_id & kriteria_id ini
                            $nilai = $matriksKeputusan
                            ->where('kriteria_id', $k->id)
                            ->first()?->nilai ?? 0;
                            @endphp
                            <td>{{ number_format($nilai, 2) }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Matriks Normalisasi --}}
        <div class="relative flex flex-col min-w-0 mb-5 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex flex-row items-center justify-between p-6 pb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6>Matriks Normalisasi <span class="text-greenPrimary">(R)</span></h6>
            </div>
            <div id='recipients' class="p-8 rounded shadow bg-white">
                <table id="tabel_data_matriks_normalisasi" class="stripe hover" style="width:100%; padding-bottom: 1em;">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            @foreach ($matriksNormalisasi->unique('kriteria_id') as $item)
                            <th>{{ $item->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($matriksNormalisasi->unique('objek_id') as $item)
                        <tr>
                            <td>{{ $item->nama_objek ?? 'Objek #' . $item->objek_id }}</td>
                            @foreach ($matriksNormalisasi->where('objek_id', $item->objek_id) as $value)
                            <td>
                                {{ round($value->nilai, 2) }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Matriks Y --}}
        <div class="relative flex flex-col min-w-0 mb-5 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex flex-row items-center justify-between p-6 pb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6>Matriks Y</h6>
            </div>
            <div id='recipients' class="p-8 rounded shadow bg-white">
                <table id="tabel_data_matriks_y" class="stripe hover" style="width:100%; padding-bottom: 1em;">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            @foreach ($matriksY->unique('kriteria_id') as $item)
                            <th>{{ $item->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($matriksY->unique('objek_id') as $item)
                        <tr>
                            <td>{{ $item->nama_objek ?? 'Objek #' . $item->objek_id }}</td>
                            @foreach ($matriksY->where('objek_id', $item->objek_id) as $value)
                            <td>
                                {{ round($value->nilai, 3) }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Ideal Positif --}}
        <div class="relative flex flex-col min-w-0 mb-5 break-words bg-white border border-gray-200 shadow-md rounded-2xl">
            <div class="flex items-center justify-between px-6 py-4 border-b bg-gradient-to-r from-green-500 to-green-400 rounded-t-2xl">
                <h6 class="text-white text-lg font-semibold">Ideal Positif <span class="text-white">(A<sup>+</sup>)</span></h6>
            </div>
            <div class="p-6 overflow-x-auto bg-white rounded-b-2xl">
                <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Nama</th>
                            @foreach ($idealPositif->unique('kriteria_id') as $item)
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">{{ $item->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($idealPositif->unique('objek_id') as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2 font-medium">{{ $item->nama_objek }}</td>
                            @foreach ($idealPositif->where('objek_id', $item->objek_id) as $value)
                            <td class="px-4 py-2">{{ number_format($value->nilai, 6) }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        {{-- Tabel Ideal Negatif --}}
        <div class="relative flex flex-col min-w-0 mb-5 break-words bg-white border border-gray-200 shadow-md rounded-2xl">
            <div class="flex items-center justify-between px-6 py-4 border-b bg-gradient-to-r from-red-500 to-red-400 rounded-t-2xl">
                <h6 class="text-white text-lg font-semibold">Ideal Negatif <span class="text-white">(A<sup>-</sup>)</span></h6>
            </div>
            <div class="p-6 overflow-x-auto bg-white rounded-b-2xl">
                <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Nama</th>
                            @foreach ($idealNegatif->unique('kriteria_id') as $item)
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">{{ $item->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($idealNegatif->unique('objek_id') as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2 font-medium">{{ $item->nama_objek }}</td>
                            @foreach ($idealNegatif->where('objek_id', $item->objek_id) as $value)
                            <td class="px-4 py-2">{{ number_format($value->nilai, 6) }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        {{-- Tabel Solusi Ideal Positif --}}
        <div class="relative flex flex-col min-w-0 mb-5 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex flex-row items-center justify-between p-6 pb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6>Solusi Ideal Positif <span class="text-greenPrimary">(Si<sup>+</sup>)</span></h6>
            </div>
            <div id='recipients' class="p-8 rounded shadow bg-white">
                <table id="tabel_data_solusi_ideal_positif" class="stripe hover" style="width:100%; padding-bottom: 1em;">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solusiIdealPositif as $item)
                        <tr>
                            <td>{{ $item->nama_objek }}</td>
                            <td>{{ round($item->nilai, 3) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Solusi Ideal Negatif --}}
        <div class="relative flex flex-col min-w-0 mb-5 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex flex-row items-center justify-between p-6 pb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6>Solusi Ideal Negatif <span class="text-greenPrimary">(Si<sup>-</sup>)</span></h6>
            </div>
            <div id='recipients' class="p-8 rounded shadow bg-white">
                <table id="tabel_data_solusi_ideal_negatif" class="stripe hover" style="width:100%; padding-bottom: 1em;">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solusiIdealNegatif as $item)
                        <tr>
                            <td>{{ $item->nama_objek }}</td>
                            <td>{{ round($item->nilai, 3) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Kedekatan Relatif terhadap Solusi Ideal --}}
        <div class="relative flex flex-col min-w-0 mb-5 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex flex-row items-center justify-between p-6 pb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6>Kedekatan Relatif terhadap Solusi Ideal <span class="text-greenPrimary">(Ci)</span></h6>
            </div>
            <div id='recipients' class="p-8 rounded shadow bg-white">
                <table id="tabel_data_hasil" class="stripe hover" style="width:100%; padding-bottom: 1em;">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hasilTopsis as $item)
                        <tr>
                            <td>{{ $item->nama_objek }}</td>
                            <td>{{ round($item->nilai, 3) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@section('js')
<script>
    // Tabel
    $(document).ready(function() {
        $('#tabel_data_bobot').DataTable({
                responsive: true,
                order: [],
                paging: false,
                ordering: false,
                info: false,
                searching: false,
            })
            .columns.adjust()
            .responsive.recalc();

        $('#tabel_data_penilaian').DataTable({
                responsive: true,
                order: [],
            })
            .columns.adjust()
            .responsive.recalc();

        $('#tabel_data_matriks_keputusan').DataTable({
                responsive: true,
                order: [],
            })
            .columns.adjust()
            .responsive.recalc();

        $('#tabel_data_matriks_y').DataTable({
                responsive: true,
                order: [],
            })
            .columns.adjust()
            .responsive.recalc();

        $('#tabel_data_matriks_normalisasi').DataTable({
                responsive: true,
                order: [],
            })
            .columns.adjust()
            .responsive.recalc();

        $('#tabel_data_ideal_positif').DataTable({
                responsive: true,
                order: [],
            })
            .columns.adjust()
            .responsive.recalc();

        $('#tabel_data_ideal_negatif').DataTable({
                responsive: true,
                order: [],
            })
            .columns.adjust()
            .responsive.recalc();

        $('#tabel_data_solusi_ideal_positif').DataTable({
                responsive: true,
                order: [],
            })
            .columns.adjust()
            .responsive.recalc();

        $('#tabel_data_solusi_ideal_negatif').DataTable({
                responsive: true,
                order: [],
            })
            .columns.adjust()
            .responsive.recalc();

        $('#tabel_data_hasil').DataTable({
                responsive: true,
                order: [],
            })
            .columns.adjust()
            .responsive.recalc();
    });
</script>
@endsection