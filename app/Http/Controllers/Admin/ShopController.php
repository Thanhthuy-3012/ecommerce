<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;

class ShopController extends BaseController
{
    protected $shop;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Shop $shop)
    {
        $this->shop = $shop;
    }

    public function list()
    {
        $shops = $this->shop->with('owner')->get();

        foreach ($shops as $shop) {
            $shop['image_shop'] = config('app.url') . '/storage/' . $shop->image_shop;
        }
        return $this->sendSuccessResponse($shops);
    }

    public function show($shopId) 
    {
        try {
            $shop = $this->shop->with('owner')->find($shopId);

            if (!$shop) return $this->sendError('Shop not exist');

            $shop['image_shop'] = config('app.url') . '/storage/' . $shop->image_shop;

            return $this->sendSuccessResponse($shop);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function delete($shopId)
    {
        try {
            $shop = $this->shop->find($shopId);

            if (!$shop) return $this->sendError('Shop does not exist');
            
            DB::beginTransaction();
            
            Product::query()->whereRelation('category', 'shop_id', '=', $shopId)->delete();
            Category::query()->where('shop_id', $shopId)->delete();
            $shop->delete();

            DB::commit();

            return $this->sendSuccessResponse(null, 'Delete Shop Succeed');
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->sendError($th->getMessage());
        }
    }
}
