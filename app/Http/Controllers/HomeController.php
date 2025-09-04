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
        if ($request->country_id) $cities->where('country_id',$request->country_id);
        $cities = $cities->get();
        return response()->json([
            'status' => 'success',
            'data' => $cities,
        ], 200);
    }


    public function getVillages()
    {
        $villages = Village::get();
        return response()->json([
            'status' => 'success',
            'data' => $villages,
        ], 200);
    }
}
