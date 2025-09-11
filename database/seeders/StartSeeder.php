<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use App\Models\Village;
use App\Models\Work;
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
            'fa_title' => 'تهران',
            'ar_title' => 'تهران',
            'en_title' => 'Tehran',
        ]);
        City::create([
            'province_id' => $province->id,
            'fa_title' => 'ورامین',
            'ar_title' => 'ورامین',
            'en_title' => 'Varamin',
        ]);
        Village::create([
            'fa_title' => 'ورامین',
            'ar_title' => 'ورامین',
            'en_title' => 'Valmazo',
        ]);
        Work::create([
            'fa_title' => 'کارگر',
            'ar_title' => 'کارگر',
            'en_title' => 'worker',
        ]);
    }
}
