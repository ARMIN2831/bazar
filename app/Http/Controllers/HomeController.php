<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\City;
use App\Models\Province;
use App\Models\Village;
use App\Models\Work;
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



    public function getWorks(Request $request)
    {
        $works = Work::get();

        return response()->json([
            'status' => 'success',
            'works' => $works,
        ], 200);
    }



    public function getAdvertisements(Request $request)
    {
        $advertisements = Advertisement::query();

        if ($request->work_id) $advertisements->where('work_id',$request->work_id);

        $advertisements = $advertisements->get();

        return response()->json([
            'status' => 'success',
            'advertisements' => $advertisements,
        ], 200);
    }
}
