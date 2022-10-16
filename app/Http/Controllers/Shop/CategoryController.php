<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Category\CreateCategoryRequest;
use App\Http\Requests\Shop\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends BaseController
{
    protected $category;
    protected $shop;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Category $category, Shop $shop)
    {
        $this->category = $category;
        $this->shop = $shop;
    }

    public function list($shopId)
    {
        $shop = Shop::query()->find($shopId);

        if (!$shop) return $this->sendError('Shop does not exist');

        $shops = $this->shop->with('categories')->where('id', $shop->id)->get();

        foreach ($shops as $shop) {
            $shop->image_shop = config('app.url') . '/storage/' . $shop->image_shop;
        }

        return $this->sendSuccessResponse($shops);
    }

    public function create($shopId, CreateCategoryRequest $request)
    {
        try {
            $shop = Shop::query()->find($shopId);

            if (!$shop) return $this->sendError('Shop does not exist');

            $category = $this->category->create(
                array_merge($request->all(), [
                    'shop_id' => $shopId
                ])
            );

            return $this->sendSuccessResponse($category, 'Create Category Succeed');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function update($categoryId, UpdateCategoryRequest $request)
    {
        try {
            $category = $this->category->find($categoryId);

            if (!$category) return $this->sendError('Category does not exist');

            $category->update($request->all());

            return $this->sendSuccessResponse($category, 'Update Category Succeed');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function delete($categoryId)
    {
        try {
            $category = $this->category->find($categoryId);

            if (!$category) return $this->sendError('Category does not exist');

            DB::beginTransaction();

            Product::query()->where('category_id', $categoryId)->delete();
            $category->delete();

            DB::commit();

            return $this->sendSuccessResponse(null, 'Delete Category Succeed');
        } catch (\Throwable $th) {
            DB::rollBack();
            
            return $this->sendError($th->getMessage());
        }
    }
}
