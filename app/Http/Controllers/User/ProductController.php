<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;
use App\Models\Product;
use App\Models\ProductImage;

class ProductController extends BaseController
{
    protected $product;
    protected $productImage;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Product $product, ProductImage $productImage)
    {
        $this->product = $product;
        $this->productImage = $productImage;
    }

    public function list()
    {
        try {
            $products = $this->product->with('category', 'category.shop', 'imagePR')->get();

            foreach ($products as $product) {
                $product->image_product = config('app.url') . '/storage/' . $product->image_product;

                foreach ($product->imagePR as $image) {
                    $image['image'] = config('app.url') . '/storage/' . $image->image;
                }
            }

            return $this->sendSuccessResponse($products);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
        
    }

    public function listNew()
    {
        try {
            $products = $this->product->with('category', 'category.shop', 'imagePR')->orderBy('created_at', 'desc')->take(10)->get();

            foreach ($products as $product) {
                $product->image_product = config('app.url') . '/storage/' . $product->image_product;

                foreach ($product->imagePR as $image) {
                    $image['image'] = config('app.url') . '/storage/' . $image->image;
                }
            }

            return $this->sendSuccessResponse($products);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}
