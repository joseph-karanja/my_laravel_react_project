<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeneficiaryTransactionStatus extends Model
{
    protected $table = 'beneficiary_transaction_status';
    protected $casts = [
        'images' => 'array', // Automatically cast images field to and from JSON
    ];
    protected $fillable = [
        'transaction_id',
        'beneficiary_no',
        'payment_status',
        'images',
        'date_received',
        'gps_latitude',
        'gps_longitude',
        'gps_altitude',
        'gps_timestamp',
    ];
}
