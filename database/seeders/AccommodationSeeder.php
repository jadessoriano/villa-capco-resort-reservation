<?php

namespace Database\Seeders;

use App\Facades\Format;
use App\Models\Accommodation;
use Illuminate\Database\Seeder;

class AccommodationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $package = [
            1 => /* morning */ [
                'rate' => Format::moneyForDatabase(20_000),
                'max_people' => 60
            ],
            2 => /* evening */ [
                'rate' => Format::moneyForDatabase(20_000),
                'max_people' => 60
            ],
            3 => /* whole day */ [
                'rate' => Format::moneyForDatabase(35_000),
                'max_people' => 60
            ],
        ];
        Accommodation::create([
            'name' => "Function Hall",
            'details' => 'air conditioned room,available tables for the guests,2 bathrooms',
        ])->packages()->attach($package);

        Accommodation::create([
            'name' => "Cottage A",
            'details' => '2 tables (5.4mx5.4m),chairs',
        ])->packages()->attach($package);
    }
}
