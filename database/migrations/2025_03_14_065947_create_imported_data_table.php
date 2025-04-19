<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('imported_data', function (Blueprint $table) {
            $table->id();

            // Data Demografi
            $table->string('rw', 10);
            $table->enum('kriteria', ['lansia', 'anak_sekolah', 'usia_produktif']);
            $table->integer('usia');
            $table->string('nama', 100);

            // Atribut Pendukung
            $table->string('tanggungan_kepala_keluarga', 20); // Sedikit/Sedang/Banyak
            $table->enum('lansia', ['ada', 'tidak_ada']);
            $table->enum('anak_wajib_sekolah', ['ada', 'tidak_ada']);
            $table->string('penghasilan_kepala_keluarga', 20); // Rendah/Sedang/Tinggi
            $table->string('status_bpjs', 50);
            $table->string('tipe_kendaraan', 30);
            $table->string('sumber_air', 30);
            $table->string('tipe_jamban', 30);
            $table->string('status_kepemilikan_bangunan', 30);
            $table->string('bahan_lantai', 30);
            $table->string('bahan_dinding', 30);
            $table->string('kategori_luas_bangunan', 20); // Kecil/Sedang/Besar

            // Label Target
            $table->enum('keterangan', ['penerima', 'bukan_penerima']);

            $table->string('user_id'); // User yang terakhir mengupdate
            $table->string('file_name')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imported_data');
    }
};
