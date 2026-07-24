<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use App\Models\Admin;
use App\Models\Transaksi;
use App\Models\File;
use App\Models\HargaCetak;
use App\Models\TokenSaldo;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_user',
        'nama',
        'foto',
    ];

    public function user(){
        return $this->belongsTo(User::class,'id_user');
    }

    public function files(){
        return $this->hasOne(File::class, 'id_pelanggan');
    }

    public function transaksi(){
        return $this->hasOne(Transaksi::class, 'id_pelanggan');
    }

    public function token_saldo(){
        return $this->hasOne(TokenSaldo::class, 'id_mahasiswa');
    }
}
