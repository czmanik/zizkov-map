<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('media')
            ->where('model_type', 'App\Models\ProgramSlot')
            ->where('collection_name', 'image')
            ->update(['collection_name' => 'gallery']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('media')
            ->where('model_type', 'App\Models\ProgramSlot')
            ->where('collection_name', 'gallery')
            ->update(['collection_name' => 'image']);
    }
};
