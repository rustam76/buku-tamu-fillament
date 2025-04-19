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
        Schema::table('tamu', function (Blueprint $table) {
            $table->string('kategori_tamu_lainnya')->nullable()->change();
            $table->string('jurusan_lainnya')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            $table->string('kategori_tamu_lainnya')->nullable(false)->change();
            $table->string('jurusan_lainnya')->nullable(false)->change();
        });
    }
};
