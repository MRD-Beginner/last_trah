<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota_Keluarga extends Model
{
    use HasFactory;

    // Nama tabel yang sesuai
    protected $table = 'anggota_keluarga';

    // Kolom yang bisa diisi (fillable)
    protected $fillable = [
        'nama', 'jenis_kelamin', 'tanggal_lahir', 
        'status_kehidupan', 'tanggal_kematian', 'alamat',
        'photo', 'urutan', 'tree_id', 'parent_id','parent_partner_id'
    ];

    // Relasi ke Tree (Satu anggota milik satu pohon keluarga)
    public function trah(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Trah::class);
    }

    // Relasi ke Parent (Orang tua)
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Anggota_Keluarga::class, 'parent_id');
    }

    // Relasi ke Children (Anak-anak)
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Anggota_Keluarga::class, 'parent_id');
    }

    // Relasi ke Partner (Satu-ke-Banyak)
    public function partners(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Partner::class, 'anggota_keluarga_id');
    }
}