<?php

namespace App\Http\Controllers\User;

use App\Constants\Constant;
use App\Http\Controllers\BaseController;
use App\Http\Requests\User\ListOrderHistoryRequest;
use App\Models\Transaction;

class TransactionController extends BaseController
{
    protected $transaction;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function listOrderHistory(ListOrderHistoryRequest $request)
    {
        try {
            $user = auth()->user();

            $transactions = $this->transaction->with(
                'order', 'order.product', 'order.product.category', 'order.product.category.shop')
                ->whereHas('order', function ($q) use ($user) {
                    $q->where([
                        ['user_id', '=', $user->id],
                        ['status', '=', Constant::ORDER_STATUS['bought']]
                    ]);
                })
                ->when(!empty($request->start_day), function ($query) use ($request) {
                    $query->whereDate('created_at', '>=', $request->start_day);
                })
                ->when(!empty($request->end_day), function ($query) use ($request) {
                    $query->whereDate('created_at', '<=', $request->end_day);
                })
                ->get();

            return $this->sendSuccessResponse($transactions);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}
