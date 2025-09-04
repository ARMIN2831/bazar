<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class HomeController extends Controller
{



    public function getCountries(Request $request)
    {
        $countries = Country::get();
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
}
