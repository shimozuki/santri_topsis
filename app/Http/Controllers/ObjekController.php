<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObjekRequest;
use App\Http\Services\ObjekService;
use App\Imports\ObjekImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ObjekController extends Controller
{
    protected $objekService;

    public function __construct(ObjekService $objekService)
    {
        $this->objekService = $objekService;
    }

    public function index()
    {
        $judul = "Objek";

        $data = $this->objekService->getAll();

        return view('dashboard.objek.index', [
            "judul" => $judul,
            "data" => $data,
        ]);
    }

    public function simpan(ObjekRequest $request)
    {
        // Simpan objek
        $data = $this->objekService->simpanPostData($request);

        // Buat user baru untuk santri
        $nama = $request->nama;
        $email = $request->email ?? strtolower(str_replace(' ', '_', $nama)) . '@santri.com';
        $password = $request->password ?? 'password123';

        if (!\App\Models\User::where('email', $email)->exists()) {
            $user = \App\Models\User::create([
                'name' => $nama,
                'email' => $email,
                'password' => bcrypt($password),
            ]);

            // Ambil role_id untuk role 'santri'
            $roleId = \DB::table('roles')->where('name', 'santri')->value('id');

            // Insert ke tabel role_user
            \DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $roleId,
            ]);
        }

        return redirect('dashboard/objek')->with('berhasil', "Data berhasil disimpan!");
    }

    public function ubah(Request $request)
    {
        $data = $this->objekService->ubahGetData($request);
        return $data;
    }

    public function perbarui(ObjekRequest $request)
    {
        $data = $this->objekService->perbaruiPostData($request);
        return redirect('dashboard/objek')->with('berhasil', "Data berhasil diperbarui!");
    }

    public function hapus(Request $request)
    {
        try {
            $objek = \App\Models\Objek::findOrFail($request->id);

            $email = strtolower(str_replace(' ', '_', $objek->nama)) . '@santri.com';

            $user = \App\Models\User::where('email', $email)->first();
            if ($user) {
                \DB::table('role_user')->where('user_id', $user->id)->delete();
                $user->delete();
            }

            $this->objekService->hapusPostData($request->id);

            return redirect('dashboard/objek')->with('berhasil', "Data berhasil dihapus!");
        } catch (\Throwable $th) {
            return abort(400, 'Gagal menghapus data');
        }
    }


    public function import(Request $request)
    {
        $request->validate([
            'import_data' => 'required|mimes:xls,xlsx'
        ]);

        $import = new ObjekImport;

        Excel::import($import, $request->file('import_data'));

        if ($import->failures()->isNotEmpty()) {
            $messages = [];

            foreach ($import->failures() as $failure) {
                $messages[] = "Baris " . $failure->row() . ": " . implode(', ', $failure->errors());
            }

            return redirect()->back()->with('import_errors', $messages);
        }

        return redirect()->back()->with('berhasil', 'Data berhasil di-import!');
    }
}
