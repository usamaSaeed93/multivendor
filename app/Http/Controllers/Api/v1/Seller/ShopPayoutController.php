<?php

namespace App\Http\Controllers\Api\v1\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Strict\StrictShopPayoutResource;
use App\Models\ShopPayout;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class ShopPayoutController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $shop_id = $request->shop_id;
        return StrictShopPayoutResource::collection(ShopPayout::where('shop_id', $shop_id)->get());
    }

}

