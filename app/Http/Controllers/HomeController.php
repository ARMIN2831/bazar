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
        $countries = Province::get();
        return response()->json([
            'status' => 'success',
            'data' => $countries,
        ], 200);
    }


    public function getCities(Request $request)
    {
        $cities = City::query();
        if ($request->province_id) $cities->where('province_id',$request->country_id);
        $cities = $cities->get();
        return response()->json([
            'status' => 'success',
            'data' => $cities,
        ], 200);
    }


    public function getVillages(Request $request)
    {
        $villages = Village::query();
        if ($request->city_id) $villages->where('city_id',$request->city_id);
        $villages = $villages->get();
        return response()->json([
            'status' => 'success',
            'data' => $villages,
        ], 200);
    }
}
