<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Shop\Product\CreateProductRequest;
use App\Http\Requests\Shop\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;

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

    public function listProductByShopId($shopId)
    {
        try {
            $shop = Shop::query()->find($shopId);

            if (!$shop) return $this->sendError('Shop does not exist');

            $products = $this->product->with('category', 'imagePR')
                        ->whereRelation('category', 'shop_id', '=', $shopId)->get();

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

    public function listProductByCategoryId($categoryId)
    {
        try {
            $category = Category::query()->find($categoryId);

            if (!$category) return $this->sendError('Category does not exist');

            $products = $this->product->with('category', 'imagePR')->where('category_id', $categoryId)->get();
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

    public function show($productId)
    {
        try {
            $product = $this->product->with('category', 'imagePR')->find($productId);

            if (!$product) return $this->sendError('Product does not exist');
            $product->image_product = config('app.url') . '/storage/' . $product->image_product;

            foreach ($product->imagePR as $image) {
                $image['image'] = config('app.url') . '/storage/' . $image->image;
            }

            return $this->sendSuccessResponse($product);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function uploadImage($image)
    {
        $file = $image;

        $mineType = str_replace("image/","",$file->getClientMimeType());

        // UPLOAD IMAGE 
        $name = $file->getClientOriginalName();
        $fileName = time() . '_' . $name;
        $file->move(public_path('storage/shop/product/'), $fileName);
        $path = 'shop/product/' . $fileName;
        return $path;
    }

    public function create(CreateProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $imagePath = $this->uploadImage($request->image_product);

            if ($request->hasFile('image1')) {
                $path1 = $this->uploadImage($request->image1);
            }
            if ($request->hasFile('image2')) {
                $path2 = $this->uploadImage($request->image2);
            }
            if ($request->hasFile('image3')) {
                $path3 = $this->uploadImage($request->image3);
            }
            if ($request->hasFile('image4')) {
                $path4 = $this->uploadImage($request->image4);
            }
            if ($request->hasFile('image5')) {
                $path5 = $this->uploadImage($request->image5);
            }

            $product = $this->product->create(
                array_merge(
                        $request->all(), 
                        [
                            'image_product' => $imagePath,
                        ])
                    );
            $productID = $product->id;

            if ($request->hasFile('image1')) {
                $this->productImage->create([
                    'product_id' => $productID,
                    'image' => $path1,
                    'sort_no' => 1
                ]);
            }
            if ($request->hasFile('image2')) {
                $this->productImage->create([
                    'product_id' => $productID,
                    'image' => $path2,
                    'sort_no' => 2
                ]);
            }
            if ($request->hasFile('image3')) {
                $this->productImage->create([
                    'product_id' => $productID,
                    'image' => $path3,
                    'sort_no' => 3
                ]);
            }
            if ($request->hasFile('image4')) {
                $this->productImage->create([
                    'product_id' => $productID,
                    'image' => $path4,
                    'sort_no' => 4
                ]);
            }
            if ($request->hasFile('image5')) {
                $this->productImage->create([
                    'product_id' => $productID,
                    'image' => $path5,
                    'sort_no' => 5
                ]);
            }
            DB::commit();
            return $this->sendSuccessResponse(null, 'Create Product Succeed');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    public function update($productId, UpdateProductRequest $request)
    {
        try {
            $product = $this->product->find($productId);

            if (!$product) return $this->sendError('Product does not exist');

            DB::beginTransaction();

            for ($i = 1; $i <= 5; $i++)
            {
                $flag = 'flag_image' . $i;
                $image = 'image' . $i;

                $productImage = ProductImage::query()->where('product_id', $productId)
                                    ->where('sort_no', $i)->first();

                if ($request->hasFile('image' . $i) && $request[$flag] == 1) 
                {
                    $path = $this->uploadImage($request[$image]);

                    if (!$productImage) 
                    {
                        ProductImage::query()->create([
                            'product_id'    => $productId,
                            'image'         => $path,
                            'sort_no'       => $i
                        ]);
                    } else {
                        $productImage->update([
                            'image' => $path
                        ]);
                    }
                } elseif ($request[$image] == "null" && $request[$flag] == 1) {
                    if ($productImage)
                    {
                        $productImage->delete();
                    }
                }
            }
            
            if ($request->flag_image_product == 1) 
            {
                $path = $this->uploadImage($request->image_product);

                $product->update(array_merge(
                    $request->all(),
                    [
                        'image_product' => $path,
                    ]
                ));
            } else {
                $product->update($request->all());
            }
            DB::commit();

            return $this->sendSuccessResponse($product, 'Update Product Succeed');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    public function delete($productId)
    {
        try {
            $product = $this->product->find($productId);

            if (!$product) return $this->sendError('Product does not exist');

            $product->delete();

            return $this->sendSuccessResponse(null, 'Delete Product Succeed');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}
