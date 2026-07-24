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

class File extends Model
{
    use HasFactory;

    protected $table = 'files';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_pelanggan',
        'name',
        'file_path',
        'tipe_file',
    ];

    public function pelanggan(){
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function transaksi(){
        return $this->HasOne(Transaksi::class, 'id_file');
    }
}
