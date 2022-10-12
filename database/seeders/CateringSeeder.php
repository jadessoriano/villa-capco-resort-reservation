<?php

namespace Database\Seeders;

use App\Facades\Format;
use App\Models\Catering;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CateringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Catering::create([
            'name' => 'SET A',
            'description' => '<ul> <li> 1 </li> <li> 2 </li> <li> 3 </li> </ul>',
            'rate' => Format::moneyForDatabase(10_000),
            'image_path' => 'images/addons/function_hall.jpg',
        ]);

        Catering::create([
            'name' => 'SET B',
            'description' => '<ul> <li> 1 </li> <li> 2 </li> <li> 3 </li> </ul>',
            'rate' => Format::moneyForDatabase(200),
            'image_path' => 'images/addons/bbq_grill.jpg',
        ]);

        Catering::create([
            'name' => 'SET C',
            'description' => '<ul> <li> 1 </li> <li> 2 </li> <li> 3 </li> </ul>',
            'rate' => Format::moneyForDatabase(250),
            'image_path' => 'images/addons/karaoke.jpg',
        ]);

        Catering::create([
            'name' => 'SET D',
            'description' => '<ul> <li> 1 </li> <li> 2 </li> <li> 3 </li> </ul>',
            'rate' => Format::moneyForDatabase(100),
            'image_path' => 'images/addons/additional_person.png',
        ]);

        Catering::create([
            'name' => 'SET E',
            'description' => '<ul> <li> 1 </li> <li> 2 </li> <li> 3 </li> </ul>',
            'rate' => Format::moneyForDatabase(5_000),
            'image_path' => 'images/addons/balloons_and_party.jpg',
        ]);
    }
}
