<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Vyčištění slugů u venues - odstranění přípony -ID
        DB::table('venues')->get()->each(function ($venue) {
            DB::table('venues')->where('id', $venue->id)->update([
                'slug' => Str::slug($venue->name)
            ]);
        });

        // Vyčištění slugů u program_slots - odstranění přípony -ID
        DB::table('program_slots')->get()->each(function ($slot) {
            DB::table('program_slots')->where('id', $slot->id)->update([
                'slug' => Str::slug($slot->name)
            ]);
        });
    }

    public function down(): void
    {
    }
};
