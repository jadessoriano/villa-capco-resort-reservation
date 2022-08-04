<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Faq::create([
            'question' => "What is the contact number?",
            'answer' => "0917 546 7499",
        ]);

        Faq::create([
            'question' => "Where is Villa Capco?",
            'answer' => "55 Axis Road, Kalawaan, Pasig, Philippines",
        ]);

        Faq::create([
            'question' => "Does Villa Capco has Facebook Page?",
            'answer' => "Yes, you can visit our Facebook Page at <a href=\"https://www.facebook.com/villacapco\">https://www.facebook.com/villacapco</a>",
        ]);

        Faq::create([
            'question' => "How many pools do you have?",
            'answer' => "Villa Capco has a total of 4 pools each have its own set of rooms.",
        ]);

        Faq::create([
            'question' => "What's your cheapest accommodation?",
            'answer' => "Villa Capco's Pool 1 which has 2 rooms with 2 beds and other amenities is only 8,000 pesos in morning package.",
        ]);

        Faq::create([
            'question' => "Can I rent the function hall only?",
            'answer' => "Yes, function hall can be reserved without the other facilities such as the pools.",
        ]);

        Faq::create([
            'question' => "Can I cancel my reservation?",
            'answer' => "Yes, as long as the cancellation date is 2 weeks ahead of reservation date.",
        ]);
    }
}
