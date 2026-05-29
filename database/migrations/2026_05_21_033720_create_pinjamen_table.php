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
        Schema::create('pinjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_cair')->nullable();
            $table->decimal('nominal_pinjam', 15, 2);
            $table->integer('tenor');
            $table->decimal('bunga_nominal', 15, 2)->default(0);
            $table->decimal('biaya_admin', 15, 2)->default(0);
            $table->enum('metode_potongan', ['potong_cair', 'masuk_cicilan'])->nullable();
            $table->decimal('nominal_cair_bersih', 15, 2)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid_off'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjamans');
    }
};
