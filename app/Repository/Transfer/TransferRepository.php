<?php

namespace App\Repository\Transfer;

use App\Models\Transfer;

class TransferRepository implements TransferRepositoryInterface
{
    private $model;

    public function __construct(Transfer $model)
    {
        $this->model = $model;
    }

    public function create(array $data) : Transfer
    {
        return $this->model->create($data);
    }
}