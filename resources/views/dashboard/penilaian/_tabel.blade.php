@extends('dashboard.layouts.app')

@section('container')
<div class="flex flex-wrap -mx-3">
    <div class="w-full px-3">
        <div class="bg-white shadow-md rounded-xl overflow-hidden">
            <div class="p-4 border-b">
                <h4 class="text-lg font-semibold text-gray-800">
                    {{ $judul ?? 'Penilaian Santri' }}
                </h4>
                <form method="GET" action="{{ route('penilaian') }}" class="mt-2">
                    <div class="flex items-center space-x-2">
                        <label for="jenjang" class="text-sm font-medium text-gray-700">Filter Jenjang:</label>
                        <select name="jenjang" id="jenjang" class="form-select rounded border-gray-300 text-sm" onchange="this.form.submit()">
                            <option value="">-- Semua --</option>
                            <option value="SMP" {{ request('jenjang') == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMA" {{ request('jenjang') == 'SMA' ? 'selected' : '' }}>SMA</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="p-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
                    <thead>
                        <tr>
                            <th>Nama Santri</th>
                            @foreach ($subKriteria as $sub)
                            <th>{{ $sub->nama }}</th>
                            @endforeach
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($objek as $o)
                        <tr>
                            <td>{{ $o->nama }}</td>
                            @foreach ($subKriteria as $sub)
                            @php
                            $nilai = $o->penilaian->firstWhere('sub_kriteria_id', $sub->id);
                            @endphp
                            <td>{{ $nilai->nilai ?? '-' }}</td>
                            @endforeach
                            <td>
                                <button onclick="bukaModalNilai({{ $o->id }}, {{ $kriteriaAktif->id }}, {{ $sub->id }})"
                                    class="text-blue-500 hover:underline text-xs">
                                    Nilai
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
@include('dashboard.penilaian._modal_nilai')
@endsection

@section('js')
<script>
    function bukaModalNilai(objekId, kriteriaId) {
        const modal = document.getElementById("modalNilai");
        const inputObjek = document.getElementById("inputObjek");
        const inputKriteria = document.getElementById("inputKriteria");

        if (!modal || !inputObjek || !inputKriteria) {
            console.error("Elemen modal/input tidak ditemukan");
            return;
        }

        inputObjek.value = objekId;
        inputKriteria.value = kriteriaId;
        modal.classList.remove("hidden");
    }



    function tutupModalNilai() {
        const modal = document.getElementById('modalNilai');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection