<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Anggota_Keluarga;


class Trah extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model (opsional jika mengikuti konvensi Laravel)
     */
    protected $table = 'trah';

    /**
     * Kolom yang dapat diisi massal (mass assignable)
     */
    protected $fillable = [
        'id',
        'trah_name',
        'description',
        'created_by',
        'visibility',
        'password',
    ];

    /**
     * Relasi one-to-many dengan AnggotaKeluarga
     * Satu Trah memiliki banyak AnggotaKeluarga
     */
    public function anggotaKeluarga()
    {
        return $this->hasMany(Anggota_Keluarga::class, 'tree_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = bcrypt($value);
    // }

}
