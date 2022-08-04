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
        $packages = [
            1 => /* morning */ [
                'rate' => Format::moneyForDatabase(10_000),
                'max_people' => 15
            ],
            2 => /* evening */ [
                'rate' => Format::moneyForDatabase(13_000),
                'max_people' => 15
            ],
            3 => /* whole day */ [
                'rate' => Format::moneyForDatabase(25_000),
                'max_people' => 20
            ],
        ];
        Accommodation::create([
            'name' => "Pool 1",
            'details' => '1 pool,2 air conditioned rooms with 2 single beds,1 bathroom,free wifi,TV with netflix,refrigerator',
        ])->packages()->attach($packages);

        $packages = [
            1 => /* morning */ [
                'rate' => Format::moneyForDatabase(18_000),
                'max_people' => 20
            ],
            2 => /* evening */ [
                'rate' => Format::moneyForDatabase(22_000),
                'max_people' => 20
            ],
            3 => /* whole day */ [
                'rate' => Format::moneyForDatabase(30_000),
                'max_people' => 25
            ],
        ];
        Accommodation::create([
            'name' => "Pool 2 ",
            'details' => '1 pool,2 air conditioned rooms with 1 single bed, 1 double deck bed,1 bathroom,free wifi,TV with netflix,refrigerator',
        ])->packages()->attach($packages);

        $packages = [
            1 => /* morning */ [
                'rate' => Format::moneyForDatabase(22_000),
                'max_people' => 25
            ],
            2 => /* evening */ [
                'rate' => Format::moneyForDatabase(25_000),
                'max_people' => 25
            ],
            3 => /* whole day */ [
                'rate' => Format::moneyForDatabase(35_000),
                'max_people' => 30
            ],
        ];
        Accommodation::create([
            'name' => "Pool 3",
            'details' => '1 pool,3 rooms with 3 double deck beds each,3 bathrooms,free wifi,TV with netflix,refrigerator',
        ])->packages()->attach($packages);

        $packages = [
            1 => /* morning */ [
                'rate' => Format::moneyForDatabase(25_000),
                'max_people' => 25
            ],
            2 => /* evening */ [
                'rate' => Format::moneyForDatabase(28_000),
                'max_people' => 25
            ],
            3 => /* whole day */ [
                'rate' => Format::moneyForDatabase(35_000),
                'max_people' => 35
            ],
        ];
        Accommodation::create([
            'name' => "Pool 4",
            'details' => '1 pool,3 rooms with queen size bed each,4 bathrooms,free wifi,TV with netflix,refrigerator',
        ])->packages()->attach($packages);

        $package = [
            1 => /* morning */ [
                'rate' => Format::moneyForDatabase(20_000),
                'max_people' => 60
            ],
        ];
        Accommodation::create([
            'name' => "Function Hall",
            'details' => 'air conditioned room,available tables for the guests,2 bathrooms',
        ])->packages()->attach($package);
    }
}
