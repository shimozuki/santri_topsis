<!-- Modal Nilai -->
<div id="modalNilai" class="fixed inset-0 bg-gray-800 bg-opacity-30 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
        <h2 class="text-lg font-semibold mb-4">Input Nilai</h2>

        <form action="{{ route('penilaian.simpan') }}" method="POST">
            @csrf
            <input type="hidden" name="objek_id" id="inputObjek">
            <input type="hidden" name="kriteria_id" id="inputKriteria">
            <input type="hidden" id="inputSubKriteria" name="sub_kriteria_id">

            <label for="inputNilai" class="block text-sm mb-1">Nilai</label>
            <input type="number" name="nilai" id="inputNilai" required class="w-full border rounded p-2 mb-4">

            <div class="flex justify-end gap-2">
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="tutupModalNilai()"
                        class="btn">Tutup</button>

                    <button type="submit"
                        class="btn btn-success">Simpan</button>

                </div>
            </div>
        </form>

    </div>
</div>