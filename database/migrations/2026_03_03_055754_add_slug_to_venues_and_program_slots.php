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
        Schema::table('venues', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        Schema::table('program_slots', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        // Generate slugs for existing venues
        DB::table('venues')->get()->each(function ($venue) {
            DB::table('venues')->where('id', $venue->id)->update([
                'slug' => Str::slug($venue->name) . '-' . $venue->id
            ]);
        });

        // Generate slugs for existing program slots
        DB::table('program_slots')->get()->each(function ($slot) {
            DB::table('program_slots')->where('id', $slot->id)->update([
                'slug' => Str::slug($slot->name) . '-' . $slot->id
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('program_slots', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
