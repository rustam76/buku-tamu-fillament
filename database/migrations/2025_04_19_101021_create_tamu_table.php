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
        Schema::create('tamu', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();
            $table->string('address');
            $table->foreignId('kategori_tamu_id')->constrained('kategori_tamu')->onDelete('cascade');
            $table->string('kategori_tamu_lainnya');
            $table->boolean('is_internal')->default(true);
            $table->foreignId('jurusan_id')->constrained('jurusan')->onDelete('cascade');
            $table->string('jurusan_lainnya');
            $table->string('description')->nullable();
            $table->string('slug');
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamu');
    }
};
