<?php

namespace App\Http\Controllers\Api\v1\Seller;

use App\Helpers\CResponse;
use App\Helpers\CValidator;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShopResource;
use App\Models\Seller;
use App\Models\Shop;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class ShopController extends Controller
{


    public function store(Request $request): Response|Application|ResponseFactory
    {

        $owner_data = $request->get('seller');
        $shop_data = $request->get('shop');

        $owner_validated_data = CValidator::validate($owner_data, Seller::rules(), errorPrefix: 'seller.');
        $shop_validated_data = CValidator::validate($shop_data, Shop::rules(), errorPrefix: 'shop.');


        $shop = new Shop($shop_validated_data);
        $shop->save();
        $shop->saveLogo($request);
        $shop->saveCoverImage($request);
        $shop->approved = false;
        $shop->active = false;

        DB::transaction(function () use ($shop, $owner_validated_data) {
            $shop->save();
            if ($owner_validated_data) {
                $seller = new Seller($owner_validated_data);
                $seller->save();
                $shop->attachOwner($seller->id);
            }
        });

        return CResponse::success('Shop is created');
    }


    public function show(Request $request): ShopResource
    {
        $shop_id = $request->user()->shop_id;
        return new ShopResource(Shop::withAll()->findOrFail($shop_id));
    }

    public function update(Request $request): Application|ResponseFactory|Response|ShopResource
    {

        $shop_id = $request->shop_id;

        if (env('DEMO', false) && env('DEMO_MAX_SHOP_ID', 0) >= $shop_id && !$request->get('active', true)) {
            return CResponse::demoError('You can\'t deactive shop for demo. Please create one and try on them');
        }
        $shop = Shop::findOrFail($shop_id);
        $data = [...$shop->toArray(), ...$request->all(),];
        $validated_data = CValidator::validate($data, Shop::rules($shop_id));
        $shop->update($validated_data);

        $shop->saveLogo($request);
        $shop->saveCoverImage($request);
        $shop->save();
        $shop->loadAll();

        return new ShopResource($shop);
    }


    public function removeLogo(Request $request): Response|Application|ResponseFactory
    {
        $shop = Shop::findOrFail($request->shop_id);
        $shop->removeLogo();
        $shop->save();
        return CResponse::success('Logo is deleted');
    }

    public function removeCoverImage(Request $request): Response|Application|ResponseFactory
    {
        $shop = Shop::findOrFail($request->shop_id);
        $shop->removeCoverImage();
        $shop->save();
        return CResponse::success('Cover image is deleted');
    }


}

