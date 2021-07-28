<?php

namespace App\Repository\Transfer;

use App\Models\Transfer;

interface TransferRepositoryInterface
{
    public function create(array $data): Transfer;
}