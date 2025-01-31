<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // DB::statement('ALTER TABLE orders ALTER COLUMN total TYPE double precision USING total::double precision');
        // DB::statement('ALTER TABLE orders ALTER COLUMN total DROP NOT NULL');

        // DB::statement('ALTER TABLE order_details ALTER COLUMN harga TYPE double precision USING harga::double precision');
        // DB::statement('ALTER TABLE order_details ALTER COLUMN harga DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // DB::statement('ALTER TABLE orders ALTER COLUMN total TYPE integer USING total::integer'); // Ganti dengan tipe sebelumnya
        // DB::statement('ALTER TABLE orders ALTER COLUMN total SET NOT NULL');

        // DB::statement('ALTER TABLE order_details ALTER COLUMN harga TYPE integer USING harga::integer'); // Ganti dengan tipe sebelumnya
        // DB::statement('ALTER TABLE order_details ALTER COLUMN harga SET NOT NULL');
    }
};
