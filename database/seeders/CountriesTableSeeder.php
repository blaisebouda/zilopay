<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Enums\Country as EnumsCountry;
use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Country::insert([
            ['short_name' => 'BF', 'name' => 'Burkina Faso', 'iso3' => 'BFA', 'number_code' => '854', 'phone_code' => '226', 'flag' => '🇧🇫'],
            ['short_name' => 'CI', 'name' => 'Cote D\'Ivoire', 'iso3' => 'CIV', 'number_code' => '384', 'phone_code' => '225', 'flag' => '🇨🇮'],
            ['short_name' => 'CM', 'name' => 'Cameroon', 'iso3' => 'CMR', 'number_code' => '120', 'phone_code' => '237', 'flag' => '🇨🇲'],
        ]);
    }
}
