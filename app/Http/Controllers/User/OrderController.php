<?php

namespace App\Http\Controllers\User;

use App\Constants\Constant;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AddToCartRequest;
use App\Http\Requests\User\CreateTransactionRequest;
use App\Http\Requests\User\UpdateToCartRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{
    protected $order;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function listCart()
    {
        try {
            $user = auth()->user();

            $orders = [];
            $orderList = $this->order->with('product')->where([
                ['user_id', '=', $user->id],
                ['status', '=', Constant::ORDER_STATUS['draft']]
                ])->get();

            foreach ($orderList as $order)
            {
                $shop = $order->product->category->shop;
                $orders[$shop->id]['name_shop'] = $shop->name_shop;

                $order['image_product'] = $order->product ? config('app.url') . '/storage/' . $order->product->image_product : null;

                $orders[$shop->id]['carts'][] = $order;
            }    

            return $this->sendSuccessResponse($orders);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function addToCart($productId, AddToCartRequest $request)
    {
        try {
            $user = auth()->user();

            $product = Product::find($productId);

            if (!$product) return $this->sendError('Product does not exist');

            $order = $this->order->where([
                ['product_id', '=', $productId],
                ['status', '=', Constant::ORDER_STATUS['draft']]
                ])->first();

            if (!$order) 
            {
                $order = $this->order->create([
                    'user_id'       => $user->id,
                    'product_id'    => $product->id,
                    'product_name'  => $product->name,
                    'quantity'      => $request->quantity,
                    'price'         => $product->price,
                    'status'        => Constant::ORDER_STATUS['draft'],
                ]);
            } else {
                $order->update([
                    'quantity' => $order->quantity + $request->quantity
                ]);
            }
            return $this->sendSuccessResponse($order, 'Add to cart succesfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function updateToCart($orderId, UpdateToCartRequest $request)
    {
        try {
            $order = $this->order->find($orderId);

            if (!$order) return $this->sendError('Product not found in your cart');

            if ($request->quantity != 0)
            {
                $order->update([
                    'quantity'      => $request->quantity,
                ]);
            } else {
                $order->delete();
            }

            return $this->sendSuccessResponse('Update to cart succesfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function deleteToCart($orderId)
    {
        try {
            $order = $this->order->find($orderId);

            if (!$order) return $this->sendError('Product not found in your cart');

            $order->delete();

            return $this->sendSuccessResponse('Delete to cart succesfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function createTransaction(CreateTransactionRequest $request)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::query()->create([
                'user_name'     => $request->user_name,
                'user_phone'    => $request->user_phone,
                'address'       => $request->address,
                'amount'        => $request->amount,
                'payment'       => $request->payment,
                'payment_info'  => $request->payment_info,
                'security'      => $request->security,
                'status'        => $request->status ?? 0,
            ]);

            $orders = $this->order->whereIn('id', $request->orders)->where('status', Constant::ORDER_STATUS['draft'])->get();

            foreach ($orders as $order) {
                $order->update([
                    'transaction_id'    => $transaction->id,
                    'status'            => Constant::ORDER_STATUS['bought'],
                ]);    
            }

            DB::commit();

            return $this->sendSuccessResponse('Ordered succesfully');
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->sendError($th->getMessage());
        }
    }
}
