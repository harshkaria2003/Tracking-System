<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB; 

class CountrySeeder extends Seeder
{
    public function run()
    {
        $countries = ['USA', 'India', 'Canada', 'UK', 'Australia'];

        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'name' => $country,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
