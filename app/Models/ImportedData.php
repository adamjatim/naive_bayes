<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportedData extends Model
{
    use HasFactory;

    protected $table = 'imported_data';

    protected $fillable = [
        'no',
        'rw',
        'kriteria',
        'usia',
        'nama',
        'tanggungan_kepala_keluarga',
        'lansia',
        'anak_wajib_sekolah',
        'penghasilan_kepala_keluarga',
        'status_bpjs',
        'tipe_kendaraan',
        'sumber_air',
        'tipe_jamban',
        'status_kepemilikan_bangunan',
        'bahan_lantai',
        'bahan_dinding',
        'kategori_luas_bangunan',
        'keterangan',
        'user_id',
        'file_name',
        'file_size'
    ];

    protected $casts = [
        'kriteria' => 'string',
        'lansia' => 'string',
        'anak_wajib_sekolah' => 'string',
        'keterangan' => 'string'
    ];

    /**
     * Relasi ke model User (user yang melakukan perubahan terakhir).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
