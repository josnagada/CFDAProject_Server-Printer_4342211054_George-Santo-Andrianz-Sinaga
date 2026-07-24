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

class TokenSaldo extends Model
{
    use HasFactory;

    protected $table = 'token_saldos';

    protected $fillable = [
        'id_mahasiswa',
        'amount',
        'token',
    ];

    public function pelanggan(){
        return $this->belongsTo(pelanggan::class,'id_mahasiswa');
    }
}
