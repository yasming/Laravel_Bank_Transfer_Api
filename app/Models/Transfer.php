<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    const FAILURE = 0;
    const SUCCESS = 1;
    
    protected $fillable = [
        'payee_id',
        'payer_id',
        'amount'
    ];
}
