@extends('dashboard.layouts.app')

@section('container')
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex flex-row items-center justify-between p-6 pb-0 mb-4 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6>Tabel {{ $judul }}</h6>
                <div>
                    <label for="add_button" class="cursor-pointer inline-block px-3 py-2 font-bold text-center text-white rounded-lg text-sm ease-soft-in shadow-soft-md bg-gradient-to-br from-greenPrimary to-greenPrimary/80 shadow-soft-md hover:shadow-soft-xs active:opacity-85 hover:scale-102 transition-all">
                        <i class="ri-add-fill"></i>
                        Tambah {{ $judul }}
                    </label>
                    <label for="import_button" class="cursor-pointer inline-block px-3 py-2 font-bold text-center text-white rounded-lg text-sm ease-soft-in shadow-soft-md bg-gradient-to-br from-greenPrimary to-greenPrimary/80 shadow-soft-md hover:shadow-soft-xs active:opacity-85 hover:scale-102 transition-all">
                        <i class="ri-file-excel-line"></i>
                        Import Data
                    </label>
                    <a href="{{ asset('templates/template_siswa.xlsx') }}" class="cursor-pointer inline-block px-3 py-2 font-bold text-center text-white rounded-lg text-sm ease-soft-in shadow-soft-md bg-gradient-to-br from-greenPrimary to-greenPrimary/80 shadow-soft-md hover:shadow-soft-xs active:opacity-85 hover:scale-102 transition-all" download>
                        <i class="ri-download-2-line"></i> Template Excel
                    </a>
                </div>
            </div>
            <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
                <table id="tabel_data" class="stripe hover" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                    <thead>
                        <tr>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Jenjang</th>
                            @if(Auth::user()->roles->pluck('name')->contains('admin'))
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->nisn }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jenjang }}</td>
                            @if(Auth::user()->roles->pluck('name')->contains('admin'))
                            <td class="flex gap-x-3">
                                <label for="edit_button" class="cursor-pointer" onclick="return edit_button('{{ $item->id }}')">
                                    <i class="ri-pencil-line text-xl"></i>
                                </label>
                                <button onclick="return delete_button('{{ $item->id }}', '{{ $item->nama }}');">
                                    <i class="ri-delete-bin-line text-xl"></i>
                                </button>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Tambah Data --}}
        <input type="checkbox" id="add_button" class="modal-toggle" />
        <div class="modal">
            <div class="modal-box">
                <form action="{{ route('objek.simpan') }}" method="post" enctype="multipart/form-data">
                    <h3 class="font-bold text-lg">Tambah {{ $judul }}</h3>
                    @csrf
                    <div class="form-control w-full max-w-xs">
                        <label class="label">
                            <span class="label-text">NISN</span>
                        </label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" placeholder="Masukkan NISN" class="input input-bordered w-full text-dark" required />
                        <label class="label">
                            @error('nisn')
                            <span class="label-text-alt text-error">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>
                    <div class="form-control w-full max-w-xs">
                        <label class="label">
                            <span class="label-text">Nama</span>
                        </label>
                        <input type="text" name="nama" placeholder="Type here" class="input input-bordered w-full max-w-xs text-dark" value="{{ old('nama') }}" required />
                        <label class="label">
                            @error('nama')
                            <span class="label-text-alt text-error">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>
                    <div class="form-control w-full max-w-xs">
                        <label class="label">
                            <span class="label-text">Jenjang</span>
                        </label>
                        <select name="jenjang" class="select select-bordered w-full max-w-xs text-dark" required>
                            <option value="">-- Pilih Jenjang --</option>
                            <option value="SMP" {{ old('jenjang') == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMA" {{ old('jenjang') == 'SMA' ? 'selected' : '' }}>SMA</option>
                        </select>
                        <label class="label">
                            @error('jenjang')
                            <span class="label-text-alt text-error">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>
                    <div class="modal-action">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <label for="add_button" class="btn">Batal</label>
                    </div>
                </form>
            </div>
            <label class="modal-backdrop" for="add_button">Close</label>
        </div>

        {{-- Form Ubah Data --}}
        <input type="checkbox" id="edit_button" class="modal-toggle" />
        <div class="modal">
            <div class="modal-box" id="edit_form">
                <form action="{{ route('objek.perbarui') }}" method="post" enctype="multipart/form-data">
                    <h3 class="font-bold text-lg">Ubah {{ $judul }}: <span class="text-greenPrimary" id="title_form"><span class="loading loading-dots loading-md"></span></span></h3>
                    @csrf
                    <input type="text" name="id" hidden />
                    <div class="form-control w-full max-w-xs">
                        <label class="label">
                            <span class="label-text">NISN</span>
                        </label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" placeholder="Masukkan NISN" class="input input-bordered w-full text-dark" required />
                        <label class="label">
                            @error('nisn')
                            <span class="label-text-alt text-error">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    <div class="form-control w-full max-w-xs">
                        <label class="label">
                            <span class="label-text">Nama</span>
                            <span class="label-text-alt" id="loading_edit1"></span>
                        </label>
                        <input type="text" name="nama" placeholder="Type here" class="input input-bordered w-full text-dark" required />
                        <label class="label">
                            @error('nama')
                            <span class="label-text-alt text-error">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>
                    <div class="form-control w-full max-w-xs">
                        <label class="label">
                            <span class="label-text">Jenjang</span>
                            <span class="label-text-alt" id="loading_edit2_jenjang"></span>
                        </label>
                        <select name="jenjang" class="select select-bordered w-full max-w-xs text-dark" required>
                            <option value="">Pilih Jenjang</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                        </select>
                        <label class="label">
                            @error('jenjang')
                            <span class="label-text-alt text-error">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>
                    <div class="modal-action">
                        <button type="submit" class="btn btn-success">Perbarui</button>
                        <label for="edit_button" class="btn">Batal</label>
                    </div>
                </form>
            </div>
            <label class="modal-backdrop" for="edit_button">Close</label>
        </div>

        {{-- Import Data --}}
        <input type="checkbox" id="import_button" class="modal-toggle" />
        <div class="modal">
            <div class="modal-box">
                <form action="{{ route('objek.import') }}" method="post" enctype="multipart/form-data">
                    <h3 class="font-bold text-lg">Import {{ $judul }}</h3>
                    @csrf
                    <div class="form-control w-full max-w-xs">
                        <label class="label">
                            <span class="label-text">Import File</span>
                        </label>
                        <input type="file" name="import_data" class="file-input file-input-bordered w-full max-w-xs" required />
                        <label class="label">
                            @error('import_data')
                            <span class="label-text-alt text-error">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    <div class="modal-action">
                        <button type="submit" class="btn btn-success">Import</button>
                        <label for="import_button" class="btn">Batal</label>
                    </div>
                </form>
            </div>
            <label class="modal-backdrop" for="import_button">Close</label>
        </div>
    </div>
</div>
@endsection

@section('js')
@if ($errors->any())
<script>
    Swal.fire({
        title: 'Gagal!',
        text: '{{ $errors->first() }}',
        icon: 'error',
        confirmButtonText: 'OK'
    });
</script>
@endif
@if (session('import_errors'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Import Gagal!',
        html: `{!! implode('<br>', session('import_errors')) !!}`,
    });
</script>
@endif


<script>
    // Tabel
    $(document).ready(function() {
        $('#tabel_data').DataTable({
                responsive: true,
                order: [],
            })
            .columns.adjust()
            .responsive.recalc();
    });



    function edit_button(id) {
        let loading = `<span class="loading loading-dots loading-md text-greenPrimary"></span>`;
        $("#title_form").html(loading);
        $("#loading_edit1").html(loading);

        $.ajax({
            type: "get",
            url: "{{ route('objek.ubah') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "id": id
            },
            success: function(data) {
                $("#title_form").html(data.nama);
                $("input[name='id']").val(data.id);
                $("input[name='nama']").val(data.nama);
                $("select[name='jenjang']").val(data.jenjang.trim()); // ini yang penting
                $("input[name='nisn']").val(data.nisn);

                $("#loading_edit1").html("");
                $("#loading_edit2_jenjang").html("");
            }
        });
    }


    function delete_button(id, nama) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            html: "<p>Data tidak dapat dipulihkan kembali!</p>" +
                "<div class='divider'></div>" +
                "<b>Data: " + nama + "</b>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6419E6',
            cancelButtonColor: '#F87272',
            confirmButtonText: 'Hapus Data!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "{{ route('objek.hapus') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id
                    },
                    success: function(response) {
                        console.log(response);
                        Swal.fire({
                            title: 'Data berhasil dihapus!',
                            icon: 'success',
                            confirmButtonColor: '#6419E6',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Data gagal dihapus!',
                        })
                    }
                });
            }
        })
    }
</script>
@endsection