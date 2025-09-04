<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Discount;
use App\Models\Language;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class StartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $country = Country::create([
            'fa_title' => 'ایران',
            'ar_title' => 'ایران',
            'en_title' => 'Iran',
            'timezone' => 'Asia/Tehran',
        ]);
        City::create([
            'country_id' => $country->id,
            'fa_title' => 'tehran',
            'ar_title' => 'تهران',
            'en_title' => 'تهران',
        ]);
    }
}
