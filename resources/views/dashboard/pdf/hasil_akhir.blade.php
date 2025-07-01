@extends("dashboard.pdf.layouts.app")

@section("container")
<div class="-mx-3 flex flex-wrap">
    <div class="w-full max-w-full flex-none px-3 table-pdf">
        <div class="mb-5 judul-laporan">
            <h1>{{ $judul }}</h1>
        </div>

        <div class="shadow-soft-xl relative mb-5 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border">
            <div class="border-b-solid flex flex-row items-center justify-between rounded-t-2xl border-b-0 border-b-transparent bg-white p-6 pb-0">
                <h2>Hasil Perhitungan TOPSIS</h2>
            </div>
            <div id='recipients' class="rounded bg-white p-8 shadow">
                <table border="0" cellpadding="0" cellspacing="0" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Peringkat</th>
                            <th class="px-4 py-2 text-left font-semibold">Nama</th>
                            <th class="px-4 py-2 text-left font-semibold">Nilai</th>
                            <th class="px-4 py-2 text-left font-semibold">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $rank = 1; @endphp
                        @foreach ($hasilTopsis->sortByDesc('nilai') as $item)
                        <tr>
                            <td class="px-4 py-2 text-center font-bold">{{ $rank }}</td>
                            <td class="px-4 py-2">{{ $item->nama_objek }}</td>
                            <td class="px-4 py-2">{{ round($item->nilai, 3) }}</td>
                            <td class="px-4 py-2">
                                @if ($rank <= ($kuota ?? 0)) <!-- Default kuota to 0 if not set -->
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