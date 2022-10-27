<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
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
            
            $products = Product::query()->whereRelation('category', 'shop_id', '=', $shopId)->get();

            foreach ($products as $product) 
            {
                foreach ($product->order as $order) {
                    if ($order->status == Constant::ORDER_STATUS['draft'])
                    {
                        $order->delete();
                    } elseif ($order->status == Constant::ORDER_STATUS['bought']) {
                        $order->update([
                            'product_id'    => null,
                            'status'        => Constant::ORDER_STATUS['stop_selling'],
                        ]);
                    }
                }
            }
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
