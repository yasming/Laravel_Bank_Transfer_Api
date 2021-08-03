<?php

namespace App\Http\Controllers\Api\Transfer;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use App\Jobs\Transfer\TransferMoneyJob;
class TransferController extends Controller
{
    public function __invoke(TransferRequest $request)
    {
        TransferMoneyJob::dispatch($request->all());
        return response()->json([__('message') => __('Your transfer is being processed !')]);
    }
}
