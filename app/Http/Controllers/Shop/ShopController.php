<?php

namespace App\Http\Controllers\Shop;

use App\Constants\Constant;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CreateShopRequest;
use App\Http\Requests\Shop\UpdateShopRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
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
        $user = auth()->user();

        $shops = $this->shop->with('owner')->where('user_id', $user->id)->get();

        foreach ($shops as $shop) {
            $shop['image_shop'] = config('app.url') . '/storage/' . $shop->image_shop;
        }

        return $this->sendSuccessResponse($shops);
    }

    public function show($shopId) 
    {
        try {
            $shop = $this->shop->with('owner')->find($shopId);

            if (!$shop) return $this->sendError('Shop does not exist');

            $shop['image_shop'] = config('app.url') . '/storage/' . $shop->image_shop;
            
            return $this->sendSuccessResponse($shop);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function create(CreateShopRequest $request)
    {
        try {
            $user = auth()->user();

            $image = $request->image;
            $name = $image->getClientOriginalName();

            $fileName = time() . '_' . $name;
            $image->move(public_path('storage/shop/'), $fileName);
            $path = 'shop/' . $fileName;
            $request->request->add(['image_shop' => $path ]);

            $shop = $this->shop->create(
                array_merge($request->all(), [
                    'user_id' => $user->id
                ])
            );

            return $this->sendSuccessResponse($shop, 'Create Shop Succeed');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function update($shopId, UpdateShopRequest $request)
    {
        try {
            $shop = $this->shop->find($shopId);

            if (!$shop) return $this->sendError('Shop does not exist');

            if ($request->flag_image == 1)
            {
                $image = $request->image;
                $name = $image->getClientOriginalName();

                $fileName = time() . '_' . $name;
                $image->move(public_path('storage/shop/'), $fileName);
                $path = 'shop/' . $fileName;
                $request->request->add(['image_shop' => $path ]);
            }

            $shop->update($request->all());

            return $this->sendSuccessResponse($shop, 'Update Shop Succeed');
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
