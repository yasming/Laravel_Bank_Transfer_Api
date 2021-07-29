<?php

namespace App\Http\Controllers\Api\Transfer;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use App\Jobs\Transfer\TransferMoneyJob;
use App\Jobs\Transfer\SendNotificationTransferJob;
class TransferController extends Controller
{
    public function __invoke(TransferRequest $request)
    {
        TransferMoneyJob::withChain([
            (new SendNotificationTransferJob)
        ])->dispatch($request->all());
        return response()->json([__('message') => __('Your transfer is being processed !')]);
    }
}
