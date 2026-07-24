<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\UserService;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use App\Models\Admin;
use App\Models\Transaksi;
use App\Models\File;
use App\Models\HargaCetak;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\TokenSaldo;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function welcomePage(){
        return view("welcome");
    }
    
    public function tes(){
        $role = Auth::user();
        $user = Auth::user()->pelanggan;
    
        if ($role->role === 'pelanggan'){
            $file = File::where('id_pelanggan', $user->id)->get();
            return view('tes', [
                'file' => $file,
                'user' => $user
            ]);
        }else{
            $user2 = User::all();
            $mahasiswa = Pelanggan::all();
            $karyawan = Karyawan::all();
            $admin = Admin::all();
            $transaksi = Transaksi::all();
            $harga_cetak = HargaCetak::all(); // Ambil data dari harga_cetak
    
            return view('tes', [
                'user' => $role,
                'pengguna' => $user2,
                'mahasiswa' => $mahasiswa,
                'karyawan' => $karyawan,
                'admin' => $admin,
                'transaksi' => $transaksi,
                'harga_cetak' => $harga_cetak // Kirim data harga_cetak ke view
            ]);
        }
    }

    public function tambahSaldoMahasiswa(Request $request, $id_user)
    {
        // Validasi input jumlah saldo
        $request->validate([
            'amount' => 'required|integer|min:1',
        ]);

        // Generate token acak
        $token = Str::random(10);

        // Buat entri baru di tabel token_saldo
        TokenSaldo::create([
            'amount' => $request->input('amount'),
            'token' => $token,
            'id_mahasiswa' => $id_user,
        ]);

        // Dapatkan ID karyawan yang sedang login
        $id_karyawan_login = Auth::user()->id;
        $karyawan = Karyawan::where('id_user', $id_karyawan_login)->firstOrFail();
        $id_get = $karyawan->id;

        // Tambah data ke tabel transaksi
        $transaksi = new Transaksi();
        $transaksi->tipe_transaksi = 'tambah_saldo';
        $transaksi->id_pelanggan = $id_user;
        $transaksi->id_karyawan = $id_get; // ID karyawan yang melakukan penambahan
        $transaksi->jumlah_saldo_yang_ditambahkan = $request->amount;
        $transaksi->token = $token;
        $transaksi->save();

        // Redirect kembali dengan notifikasi sukses
        return redirect()->back()->with([
            'notifikasi' => 'Berhasil Generate Code: ' . $token,
            'type' => 'success',
        ]);
    }

    public function redeemToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $token = $request->input('token');
        $user = auth()->user();
        $pelanggan = Pelanggan::where('id_user', $user->id)->first();

        // Cari token di tabel token_saldo
        $tokenSaldo = TokenSaldo::where('token', $token)
            ->where('id_mahasiswa', $pelanggan->id)
            ->first();

        if ($tokenSaldo) {
            // Tambahkan saldo ke mahasiswa
            $pelanggan = Pelanggan::find($pelanggan->id);
            $pelanggan->saldo += $tokenSaldo->amount;
            $pelanggan->save();

            // Hapus token dari tabel token_saldo
            $tokenSaldo->delete();

            return redirect()->back()->with([
                'notifikasi' => 'Berhasil redeem token.',
                'type' => 'success',
            ]);
        } else {
            return redirect()->back()->with([
                'notifikasi' => 'Sepertinya anda memasukkan token yang salah atau itu bukan token anda.',
                'type' => 'error',
            ]);
        }
    }

    public function uploadFile(Request $request, $id_user) {
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:pdf'
        ], [
            'file.mimes' => 'Format file yang diterima adalah PDF.',
        ]);        
        try {
            DB::beginTransaction();
        
            $dataFile = new File();
            $dataFile->id_pelanggan = $id_user;
            $dataFile->name = $request->nama;
            $dataFile->tipe_file = $request->tipe_file;
        
            if ($request->hasFile('file')) {
                // Menggunakan nama asli file dengan ekstensi
                $file = $request->file('file');
                $fileName = basename($file);
                $extension = $file->getClientOriginalExtension();
                $filenameSimpan = $fileName . '_' . time() . '.' . $extension;
            
                $filePath = $file->storeAs('public', $filenameSimpan); // Simpan di folder public
            
                $dataFile->file_path = $filenameSimpan; // Simpan nama file yang dimodifikasi dengan timestamp
            }
        
            $dataFile->save();
        
            DB::commit();
        
            return redirect()->back()->with([
                'notifikasi' => 'Berhasil mengupload file.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with([
                'notifikasi' => 'Gagal mengupload file: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function pay(Request $request) {
        // Validasi input
        $request->validate([
            'user_id' => 'required|numeric',
            'file_id' => 'required|numeric',
            'file_name' => 'required|string',
            'harga' => 'required|numeric'
        ]);

        $rfid = $request->user_id;

        // Temukan pengguna berdasarkan RFID
        $user = User::where('RFID', $rfid)->first();
        if (!$user) {
            return response()->json(['error' => 'Pengguna tidak ditemukan'], 404);
        }

        // Temukan pelanggan berdasarkan ID pengguna
        $pelanggan = Pelanggan::where('id_user', $user->id)->first();
        if (!$pelanggan) {
            return response()->json(['error' => 'Pelanggan tidak ditemukan'], 404);
        }

        // Pastikan saldo mencukupi
        if ($pelanggan->saldo < $request->harga) {
            return response()->json(['error' => 'Saldo tidak mencukupi'], 400);
        }

        // Kurangi saldo pelanggan
        $pelanggan->saldo -= $request->harga;
        $pelanggan->save();

        // Simpan data ke tabel transaksi
        $transaksi = new Transaksi();
        $transaksi->tipe_transaksi = 'mahasiswa_print';
        $transaksi->id_pelanggan = $pelanggan->id;
        $transaksi->id_file = $request->file_id;
        $transaksi->harga = $request->harga;
        $transaksi->save();

        return response()->json(['message' => 'Data transaksi berhasil ditambahkan']);
    }
    
    public function downloadFile(Request $request) {
        try {
            // Ambil file_id dari request
            $file_id = $request->file_id;

            // Cari file berdasarkan file_id
            $file_track = File::where('id', $file_id)->first();
            if (!$file_track) {
                return response()->json(['error' => 'File not found'], 404);
            }

            $file_name = $file_track->file_path;
            $full_file_path = storage_path('app/' . $file_name); // Pastikan menggunakan path yang benar
            
            // Log untuk debugging
            \Log::info('File path being checked: ' . $full_file_path);

            // Cek apakah file ada di storage
            if (Storage::disk('public')->exists($file_name)) {
                return response()->json(['https://serverprinter.site/storage/'. $file_name], 200);
            } else {
                return response()->json(['error' => 'File not found in storage', 'path' => $full_file_path], 404);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to download file: ' . $e->getMessage()], 500);
        }
    }

    public function getHarga($tipe_file)
    {
        $harga = HargaCetak::where('tipe_dokumen', $tipe_file)->first();

        if ($harga) {
            return response()->json(['harga' => $harga->harga], 200);
        } else {
            return response()->json(['error' => 'Harga tidak ditemukan'], 404);
        }
    }
}
