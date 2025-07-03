@extends('dashboard.layouts.app')

@section('container')
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-5 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex flex-row items-center justify-between p-6 pb-0 border-b-0 border-b-solid rounded-t-2xl">
                <h6>Hasil Perhitungan TOPSIS</h6>

                @php
                $status = isset($hasilTopsis) ? $hasilTopsis->first()?->status : null;
                $isApproved = $status === 'disetujui';
                @endphp

                <div class="flex items-center gap-2">
                    @if($jenjang && Auth::user()->roles->pluck('name')->contains('kepala_sekolah') && !$isApproved)
                    <form action="{{ route('hasil-topsis.approve') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-active btn-accent text-white">
                            <i class="ri-check-line"></i> Setujui Hasil
                        </button>
                    </form>
                    @endif

                    @if((!$jenjang && $semuaApproved) || $isApproved)
                    <form action="{{ 'pdf_hasil' }}" method="post" enctype="multipart/form-data" target="_blank">
                        @csrf
                        <input type="hidden" name="jenjang" value="{{ $jenjang }}">
                        <button type="submit" class="btn btn-sm btn-active btn-error text-white">
                            <i class="ri-file-pdf-line"></i> Export PDF
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <div class="p-8">
                <form method="GET" action="{{ route('hasil_akhir') }}" class="mb-4">
                    <select name="jenjang" onchange="this.form.submit()" class="form-select w-1/3">
                        <option value="">-- Semua Jenjang --</option>
                        <option value="SMP" {{ request('jenjang') == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ request('jenjang') == 'SMA' ? 'selected' : '' }}>SMA</option>
                    </select>
                </form>

                @if (!$jenjang)
                @foreach ($hasilPerJenjang as $j => $hasilTopsis)
                @php
                $tahun = now()->year;
                $kuota = \App\Models\KuotaSeleksi::getKuota($j, $tahun);
                $rank = 1;
                @endphp

                <h5 class="text-lg font-bold mt-8 mb-2">Jenjang: {{ $j }}</h5>

                @if (!$kuota)
                <div class="text-sm text-red-500 mb-3">
                    Kuota belum ditentukan untuk jenjang <strong>{{ $j }}</strong> tahun <strong>{{ $tahun }}</strong>.
                </div>
                @endif

                <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700 mb-6">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Peringkat</th>
                            <th class="px-4 py-2 text-left font-semibold">Nama</th>
                            <th class="px-4 py-2 text-left font-semibold">Nilai</th>
                            <th class="px-4 py-2 text-left font-semibold">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($hasilTopsis->sortByDesc('nilai') as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-center font-bold">{{ $rank }}</td>
                            <td class="px-4 py-2">{{ $item->nama_objek }}</td>
                            <td class="px-4 py-2">{{ number_format($item->nilai, 3) }}</td>
                            <td class="px-4 py-2">
                                @if ($kuota && $rank <= $kuota)
                                    <span class="text-green-600 font-semibold">Diterima</span>
                                    @else
                                    <span class="text-red-500">Tidak Diterima</span>
                                    @endif
                            </td>
                        </tr>
                        @php $rank++; @endphp
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500">Belum ada data hasil.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @endforeach
                @else
                @php
                $tahun = now()->year;
                $kuota = \App\Models\KuotaSeleksi::getKuota($jenjang, $tahun);
                $rank = 1;
                @endphp

                @if (!$kuota)
                <div class="text-sm text-red-500 mb-3">
                    Kuota belum ditentukan untuk jenjang <strong>{{ $jenjang }}</strong> tahun <strong>{{ $tahun }}</strong>.
                </div>
                @endif

                <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Peringkat</th>
                            <th class="px-4 py-2 text-left font-semibold">Nama</th>
                            <th class="px-4 py-2 text-left font-semibold">Nilai</th>
                            <th class="px-4 py-2 text-left font-semibold">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($hasilTopsis->sortByDesc('nilai') as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-center font-bold">{{ $rank }}</td>
                            <td class="px-4 py-2">{{ $item->nama_objek }}</td>
                            <td class="px-4 py-2">{{ number_format($item->nilai, 3) }}</td>
                            <td class="px-4 py-2">
                                @if ($kuota && $rank <= $kuota)
                                    <span class="text-green-600 font-semibold">Diterima</span>
                                    @else
                                    <span class="text-red-500">Tidak Diterima</span>
                                    @endif
                            </td>
                        </tr>
                        @php $rank++; @endphp
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500">Belum ada data hasil.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
<script>
    // Tabel
    $(document).ready(function() {
        $('#tabel_data_hasil').DataTable({
                responsive: true,
                order: [
                    [1, 'desc']
                ],
            })
            .columns.adjust()
            .responsive.recalc();
    });
</script>
@endsection