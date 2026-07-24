<?php

namespace App\Http\Controllers;


use App\Models\Karyawan;
use App\Models\Pelanggan;
use App\Models\User;
use App\Models\HargaCetak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CRUDController extends Controller
{
    public function tambahUser(Request $request){
        $validatedData = $request->validate([
            'email' => 'required|unique:users,email|email:dns',
            'password' => 'required|min:8',
            'nama' => 'required',
            'role' => 'required',
            'RFID' => 'required|numeric',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'email.email' => 'Email tidak valid.',
            'email.email:dns' => 'Email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'nama.required' => 'Nama wajib diisi.',
            'RFID.required' => 'Role wajib dipilih..',
            'RFID.required' => 'RFID wajib diisi.',
            'RFID.numeric' => 'RFID harus berupa angka.',
        ]);

        try {
            DB::beginTransaction();

            $akun = new User();
            $akun->email = $request->email;
            $akun->password = Hash::make($request->password);
            $akun->role = $request->role;
            $akun->RFID = $request->RFID;
            $akun->status = 'belum aktif';
            $akun->save();

            if ($request->role === 'karyawan') {
                $karyawan = new Karyawan();
                $karyawan->id_user = $akun->id;
                $karyawan->nama = $request->nama;
                $karyawan->save();
            }elseif ($request->role === 'pelanggan') {
                $mahasiswa = new Pelanggan();
                $mahasiswa->id_user = $akun->id;
                $mahasiswa->nama = $request->nama;
                $mahasiswa->save();
            }
            DB::commit();

            return redirect()->back()->with([
                'notifikasi' => 'Berhasil menambahkan user',
                'type' => 'success',
            ]);

        } catch (\Exception $e) {

            DB::rollback();

            return redirect()->back()->with([
                'notifikasi' => 'Gagal menambahkan user',
                'type' => 'error',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'harga' => 'required|numeric',
        ]);

        $hargaCetak = HargaCetak::findOrFail($id);
        $hargaCetak->harga = $request->harga;
        $hargaCetak->save();

        return redirect()->back()->with('success', 'Harga cetak berhasil diperbarui.');
    }

    
}
