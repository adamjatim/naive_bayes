<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportedDataHistory extends Model
{
    use HasFactory;

    protected $table = 'imported_data_history';

    protected $fillable = [
        'user_email',
        'dataset_id',
        'row_data',
        'modified_at',
    ];

    protected $casts = [
        'row_data' => 'array', // Agar otomatis dikonversi ke array saat diakses
    ];

    public $timestamps = false; // Karena kita pakai modified_at secara manual

    /**
     * Relasi ke model User (user yang melakukan perubahan).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
