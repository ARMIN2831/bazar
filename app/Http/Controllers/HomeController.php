<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Province;
use App\Models\Village;
use Illuminate\Http\Request;

class HomeController extends Controller
{



    public function getProvinces(Request $request)
    {
        $provinces = Province::get();
        $cities = [];
        $villages = [];
        if ($request->province_id) $cities = City::where('province_id',$request->province_id)->get();
        if ($request->city_id) $villages = Village::where('city_id',$request->city_id)->get();

        return response()->json([
            'status' => 'success',
            'provinces' => $provinces,
            'cities' => $cities,
            'villages' => $villages,
        ], 200);
    }
}
