<?php

namespace App\Http\Controllers;

use App\Helper\UserService;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginPage(){
        return view('Auth/login');
    }

    public function showLoginRFIDPage(){
        return view('Auth/loginRFID');
    }
    
    public function loginProcess(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
    
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $userId = $user->id;
            
            // Periksa apakah status akun aktif
            if ($user->status !== 'aktif') {
                return redirect()->route('accountNotActive')->with([
                    'notifikasi' => 'Akun Anda tidak aktif.',
                    'type' => 'error'
                ]);
            }
    
            $request->session()->regenerate();
            
            //jika yang login pelanggan
            if ($user->role === 'pelanggan'){
                // Cari data profil berdasarkan ID user
                $profil = Pelanggan::where('id_user', $userId)->first();
            
                if (!$profil) {
                    return response()->json(['message' => 'Profile not found'], 404);
                }
            
                // Ambil ID Pelanggan dari profil
                $pelangganId = $profil->id;
            
                // Cari file berdasarkan ID Pelanggan
                $file = File::where('id_pelanggan', $pelangganId)->get();

                return redirect()->route('tes')->with(['notifikasi' => 'Selamat Datang ' . $user->role, 'type' => 'success', 'user' => $user, 'profil' => $profil, 'file' => $file]);
            }else{
                return redirect()->route('tes')->with(['notifikasi' => 'Selamat Datang ' . $user->role, 'type' => 'success', 'user' => $user]);
            }
        }
    
        return redirect()->back()->withInput()->with([
            'notifikasi' => 'Login Gagal !',
            'type' => 'error'
        ])->withErrors(['email' => 'Kombinasi email dan password tidak valid.']);
    }
    

    public function logout(Request $request): RedirectResponse{
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcomePage')->with([
            'notifikasi' => 'Anda berhasil logout !',
            'type' => 'success'
        ]);
    }

    public function loginRFID(Request $request)
    {
        $rfid = $request->RFID;

        // Cari data user berdasarkan RFID
        $user = User::where('RFID', $rfid)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Lakukan proses login
        Auth::login($user);

        // Ambil ID user setelah login
        $userId = $user->id;
        
        //jika yang login pelanggan
        if ($user->role === 'pelanggan'){
            // Cari data profil berdasarkan ID user
            $profil = Pelanggan::where('id_user', $userId)->first();
        
            if (!$profil) {
                return response()->json(['message' => 'Profile not found'], 404);
            }
        
            // Ambil ID Pelanggan dari profil
            $pelangganId = $profil->id;
        
            // Cari file berdasarkan ID Pelanggan
            $file = File::where('id_pelanggan', $pelangganId)->get();

            return redirect()->route('tes')->with(['notifikasi' => 'Selamat Datang ' . $user->role, 'type' => 'success', 'user' => $user, 'profil' => $profil, 'file' => $file]);
        }else{
            return redirect()->route('tes')->with(['notifikasi' => 'Selamat Datang ' . $user->role, 'type' => 'success', 'user' => $user]);
        }
    }

    public function resetPassword(Request $request) {
        try {
            $userId = $request->input('user_id');
    
            if (!$userId) {
                return redirect()->back()->with([
                    'notifikasi' => 'Gagal mereset password. ID pengguna tidak valid.',
                    'type' => 'error',
                ]);
            }
    
            DB::beginTransaction();
    
            $user = User::find($userId);
    
            if (!$user) {
                return redirect()->back()->with([
                    'notifikasi' => 'Gagal mereset password. Pengguna tidak ditemukan.',
                    'type' => 'error',
                ]);
            }
    
            // Reset password pengguna
            $user->password = Hash::make('12345678');
            $user->save();
    
            DB::commit();
    
            return redirect()->back()->with([
                'notifikasi' => 'Berhasil mereset password.',
                'type' => 'success',
            ]);
    
        } catch (\Exception $e) {
            DB::rollback();
    
            return redirect()->back()->with([
                'notifikasi' => 'Gagal mereset password.',
                'type' => 'error',
            ]);
        }
    }      

    public function updateStatus(Request $request,$id_user){
        $validatedData = $request->validate([
            'status' => 'required',
        ], [
            'status.required' => 'Status harus diisi.',
        ]);

        $user = User::where('id',$id_user)->firstOrFail();
        $user->status = $request->status;
        if($user->isDirty()){
            if ($user->save()) {
                return redirect()->back()->with([
                    'notifikasi' => 'status berhasil diperbarui!',
                    'type' => 'success'
                ]);
            } else {
                return redirect()->back()->with([
                    'notifikasi' => 'status gagal diperbarui!',
                    'type' => 'error'
                ]);
            }
        }else{
            return redirect()->back()->with([
                'notifikasi' => 'Tidak ada perubahan!',
                'type' => 'info'
            ]);
        }
    }

    public function loginHardware(Request $request)
    {
        $rfid = $request->id;
    
        // Cari data user berdasarkan RFID
        $user = User::where('RFID', $rfid)->first();
    
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        // Lakukan proses login
        Auth::login($user);
    
        // Ambil ID user setelah login
        $userId = $user->id;
    
        // Cari data profil berdasarkan ID user
        $profil = Pelanggan::where('id_user', $userId)->first();
    
        if (!$profil) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
    
        // Ambil ID Pelanggan dari profil
        $pelangganId = $profil->id;
    
        // Cari file berdasarkan ID Pelanggan
        $files = File::where('id_pelanggan', $pelangganId)->get();
    
        // Download dan simpan file ke folder download di Raspberry Pi
        foreach ($files as $file) {
            $fileUrl = public_path($file->file_path);
            $fileName = basename($file->file_path);
            // Download file menggunakan wget
            shell_exec("wget -O /home/pi/Downloads/$fileName $fileUrl");
        }
    
        return response()->json(['message' => 'Login successful', 'user' => $user, 'profil' => $profil, 'file' => $files]);
    }
}
