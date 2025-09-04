<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use App\Models\Village;
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
        $province = Province::create([
            'fa_title' => 'ایران',
            'ar_title' => 'ایران',
            'en_title' => 'Iran',
            'timezone' => 'Asia/Tehran',
        ]);
        City::create([
            'province_id' => $province->id,
            'fa_title' => 'tehran',
            'ar_title' => 'تهران',
            'en_title' => 'تهران',
        ]);
        Village::create([
            'fa_title' => 'tehran',
            'ar_title' => 'تهران',
            'en_title' => 'تهران',
        ]);
    }
}
