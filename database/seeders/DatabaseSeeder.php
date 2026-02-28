<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VenueType;
use App\Models\ActivityType;
use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use App\Models\Venue;
use App\Models\Stage;
use App\Models\ProgramSlot;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin
        User::updateOrCreate([
            'email' => 'super@test.cz',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('heslo'),
            'role' => 'superadmin',
        ]);

        // 2. Settings
        Setting::set('event_name', 'Žižkovská noc 2026');
        Setting::set('ico', '12345678');

        // 3. Venue Types
        $venueTypes = [];
        $vTypes = ['Klub', 'Kavárna', 'Restaurace', 'Galerie', 'Venkovní prostor'];
        foreach ($vTypes as $type) {
            $venueTypes[] = VenueType::create(['name' => $type, 'slug' => Str::slug($type)]);
        }

        // 4. Activity Types
        $activityTypes = [];
        $aTypes = ['Hudba', 'Divadlo', 'Workshop', 'Přednáška', 'Gastro', 'Swap'];
        foreach ($aTypes as $type) {
            $activityTypes[] = ActivityType::create(['name' => $type, 'slug' => Str::slug($type)]);
        }

        // 5. Pages
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

        // 6. Pokusný obsah (Místa a Program)
        $adminUser = User::create([
            'name' => 'Majitel Paláce Akropolis',
            'email' => 'majitel@akropolis.cz',
            'password' => Hash::make('heslo'),
            'role' => 'admin',
        ]);

        $akropolis = Venue::create([
            'name' => 'Palác Akropolis',
            'venue_type_id' => $venueTypes[0]->id, // Klub
            'description' => 'Kulturní centrum a legendární klub na Žižkově.',
            'address_street' => 'Kubelíkova',
            'address_number' => '1548/27',
            'address_city' => 'Praha 3',
            'lat' => 50.0823611,
            'lng' => 14.4487222,
            'status' => 'public',
            'owner_id' => $adminUser->id,
        ]);

        $mainStage = Stage::create([
            'venue_id' => $akropolis->id,
            'name' => 'Velký sál',
            'description' => 'Hlavní stage v Paláci Akropolis.'
        ]);

        $smallStage = Stage::create([
            'venue_id' => $akropolis->id,
            'name' => 'Malá scéna',
            'description' => 'Komorní stage pro alternativní umění.'
        ]);

        // Program Slots
        $now = Carbon::parse('2026-03-20 18:00:00');

        ProgramSlot::create([
            'stage_id' => $mainStage->id,
            'activity_type_id' => $activityTypes[0]->id, // Hudba
            'name' => 'Hlavní hvězda večera',
            'description' => 'Úžasný koncert populární kapely.',
            'start_time' => $now,
            'end_time' => $now->copy()->addHours(2),
            'accessibility' => 'all',
            'status' => 'approved',
        ]);

        ProgramSlot::create([
            'stage_id' => $smallStage->id,
            'activity_type_id' => $activityTypes[1]->id, // Divadlo
            'name' => 'Žižkovské impro-divadlo',
            'description' => 'Představení plné humoru a improvizace.',
            'start_time' => $now->copy()->addHours(1),
            'end_time' => $now->copy()->addHours(2),
            'accessibility' => 'all',
            'status' => 'approved',
        ]);

        // Another venue
        $uVystrelenyhoOka = Venue::create([
            'name' => 'U Vystřelenýho oka',
            'venue_type_id' => $venueTypes[2]->id, // Restaurace/Hospoda
            'description' => 'Kultovní žižkovská hospoda.',
            'address_street' => 'U Božích bojovníků',
            'address_number' => '606/3',
            'address_city' => 'Praha 3',
            'lat' => 50.0879167,
            'lng' => 14.4468611,
            'status' => 'public',
        ]);

        $gardenStage = Stage::create([
            'venue_id' => $uVystrelenyhoOka->id,
            'name' => 'Zahrádka',
        ]);

        ProgramSlot::create([
            'stage_id' => $gardenStage->id,
            'activity_type_id' => $activityTypes[3]->id, // Přednáška
            'name' => 'Historie Žižkova',
            'description' => 'Zajímavá přednáška o historii naší čtvrti.',
            'start_time' => Carbon::parse('2026-03-21 14:00:00'),
            'end_time' => Carbon::parse('2026-03-21 15:30:00'),
            'accessibility' => 'all',
            'status' => 'approved',
        ]);
    }
}
