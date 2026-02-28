<?php

use Illuminate\Database\Seeder;
use App\Models\VenueType;
use App\Models\ActivityType;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Settings
        Setting::set('event_name', 'Žižkovská noc 2026');
        Setting::set('ico', '12345678');

        // Venue Types
        $vTypes = ['Klub', 'Kavárna', 'Restaurace', 'Galerie', 'Venkovní prostor'];
        foreach ($vTypes as $type) {
            VenueType::create(['name' => $type, 'slug' => Str::slug($type)]);
        }

        // Activity Types
        $aTypes = ['Hudba', 'Divadlo', 'Workshop', 'Přednáška', 'Gastro', 'Swap'];
        foreach ($aTypes as $type) {
            ActivityType::create(['name' => $type, 'slug' => Str::slug($type)]);
        }

        // Pages
        Page::create([
            'title' => 'O nás',
            'slug' => 'o-nas',
            'content' => '<p>Žižkovská noc je multižánrový festival, který propojuje desítky klubů, kaváren a netradičních prostor.</p>',
            'is_active' => true
        ]);

        Page::create([
            'title' => 'Kontakt',
            'slug' => 'kontakt',
            'content' => '<p>Email: info@zizkovskanoc.cz<br>Telefon: +420 123 456 789</p>',
            'is_active' => true
        ]);
    }
}
