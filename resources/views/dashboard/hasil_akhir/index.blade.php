@extends('dashboard.layouts.app')

@section('container')
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-5 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex flex-row items-center justify-between p-6 pb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6>Hasil Perhitungan TOPSIS</h6>

                @php
                $status = $hasilTopsis->first()?->status;
                $isApproved = $status === 'disetujui';
                @endphp

                <div class="flex items-center gap-2">
                    {{-- Tampilkan tombol Approve jika user adalah kepala_sekolah dan hasil belum disetujui --}}
                    @if(Auth::user()->roles->pluck('name')->contains('kepala_sekolah') && !$isApproved)
                    <form action="{{ route('hasil-topsis.approve') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-active btn-accent text-white hover:bg-accent/95 hover:border-accent/95">
                            <i class="ri-check-line"></i> Setujui Hasil
                        </button>
                    </form>
                    @endif
                    {{-- Tombol Export PDF --}}
                    @if($isApproved)
                    <form action="{{ 'pdf_hasil' }}" method="post" enctype="multipart/form-data" target="_blank">
                        @csrf
                        <input type="hidden" name="jenjang" value="{{ $jenjang }}">
                        <button type="submit" class="btn btn-sm btn-active btn-error text-white hover:bg-error/95 hover:border-error/95">
                            <i class="ri-file-pdf-line"></i>
                            Export PDF
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            <div id='recipients' class="p-8 rounded shadow bg-white">
                @php
                use App\Models\KuotaSeleksi;

                $jenjang = request('jenjang');
                $tahun = now()->year;

                // Ambil kuota berdasarkan jenjang dan tahun aktif
                $kuota = $jenjang ? KuotaSeleksi::getKuota($jenjang, $tahun) : null;
                $rank = 1;
                @endphp

                {{-- Filter Jenjang --}}
                <form method="GET" action="{{ route('hasil_akhir') }}" class="mb-4">
                    <select name="jenjang" onchange="this.form.submit()" class="form-select w-1/3">
                        <option value="">-- Semua Jenjang --</option>
                        <option value="SMP" {{ request('jenjang') == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ request('jenjang') == 'SMA' ? 'selected' : '' }}>SMA</option>
                    </select>
                </form>

                {{-- Info jika kuota belum tersedia --}}
                @if ($jenjang && !$kuota)
                <div class="text-sm text-red-500 mb-3">
                    Kuota belum ditentukan untuk jenjang <strong>{{ $jenjang }}</strong> tahun <strong>{{ $tahun }}</strong>.
                </div>
                @endif

                {{-- Tabel Hasil --}}
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
                            <td colspan="4" class="text-center text-gray-500">Belum ada data hasil untuk jenjang ini.</td>
                        </tr>
                        @endforelse
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