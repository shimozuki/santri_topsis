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
                $batasMinimal = 0.5; // batas minimum nilai untuk diterima
                @endphp
                <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Nama</th>
                            <th class="px-4 py-2 text-left font-semibold">Nilai</th>
                            <th class="px-4 py-2 text-left font-semibold">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($hasilTopsis->sortByDesc('nilai') as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $item->nama_objek }}</td>
                            <td class="px-4 py-2">{{ number_format($item->nilai, 3) }}</td>
                            <td class="px-4 py-2">
                                @if ($item->nilai >= $batasMinimal)
                                <span class="text-green-600 font-semibold">Diterima</span>
                                @else
                                <span class="text-red-500">Tidak Diterima</span>
                                @endif
                            </td>
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