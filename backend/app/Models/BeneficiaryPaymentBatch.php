<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeneficiaryPaymentBatch extends Model
{
    protected $table = 'trans_ben_pg_details';

    protected $casts = [
        'transaction_time_initiated' => 'datetime',  // Casting as datetime
    ];
}


