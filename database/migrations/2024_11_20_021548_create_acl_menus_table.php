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
        Schema::create('acl_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('link')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('parent_type')->nullable();
            $table->string('parent_icon')->nullable();
            $table->string('permission_key')->nullable();
            $table->string('permission_option')->nullable();
            $table->unsignedInteger('ordering');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acl_menus');
    }
};
