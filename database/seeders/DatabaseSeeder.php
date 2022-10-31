<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AddonSeeder::class,
            CateringSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            FaqSeeder::class,
            PackageSeeder::class,
            AccommodationSeeder::class,
            ImageSeeder::class,
            RatingSeeder::class,
            StatusSeeder::class,
            // ReservationSeeder::class,
        ]);
    }
}
