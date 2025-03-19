<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportedData extends Model
{
    use HasFactory;

    protected $table = 'imported_data';

    protected $fillable = [
        'user_email',
        'dataset_id',
        'row_data',
    ];

    protected $casts = [
        'row_data' => 'array', // Agar otomatis dikonversi ke array saat diakses
    ];

    /**
     * Relasi ke model User (user yang melakukan perubahan terakhir).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
