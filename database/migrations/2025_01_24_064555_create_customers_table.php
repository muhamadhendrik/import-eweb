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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('nickname')->nullable();
            $table->string('email')->nullable();
            $table->string('alamat')->nullable();
            $table->string('kode_cusgrup')->nullable();
            $table->string('kota')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('update_by_nickname')->nullable();
            $table->string('id_customer')->nullable();
            $table->string('hp')->nullable();
            $table->string('telepon_1')->nullable();
            $table->string('id_branches')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
