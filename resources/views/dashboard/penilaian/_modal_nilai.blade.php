<!-- Modal Nilai -->
<div id="modalNilai" class="fixed inset-0 bg-gray-800 bg-opacity-30 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
        <h2 class="text-lg font-semibold mb-4">Input Nilai</h2>

        <form action="{{ route('penilaian.simpan') }}" method="POST">
            @csrf
            <input type="hidden" name="objek_id" id="inputObjek">
            <input type="hidden" name="kriteria_id" id="inputKriteria">

            {{-- Loop sub kriteria --}}
            @foreach($subKriteria as $sub)
            <input type="hidden" name="sub_kriteria_id[]" value="{{ $sub->id }}">
            <label class="block text-sm mb-1">{{ $sub->nama }}</label>
            <input type="number" name="nilai[]" required class="w-full border rounded p-2 mb-4">
            @endforeach

            <div class="flex justify-end gap-2">
                <button type="button" onclick="tutupModalNilai()" class="btn">Tutup</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>