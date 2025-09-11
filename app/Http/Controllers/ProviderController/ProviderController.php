<?php

namespace App\Http\Controllers\ProviderController;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProviderRequests\StoreAdvertisementRequest;
use App\Models\Advertisement;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function storeAdvertisement(StoreAdvertisementRequest $request)
    {
        $request->validated();
        $user = $request->user();
        $user->advertisement()->create([
            'user_id' => $request->user_id,
            'work_id' => $request->work_id,
            'price' => $request->price,
            'sitePercent' => $request->sitePercent,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => trans('messages.advertisement_uploaded'),
        ],200);
    }
}
