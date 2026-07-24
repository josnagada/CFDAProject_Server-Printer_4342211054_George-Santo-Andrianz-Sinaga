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

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admins';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_user',
        'nama',
        'foto',
    ];

    public function user(){
        return $this->belongsTo(User::class,'id_user');
    }
}