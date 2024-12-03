<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeneficiaryImage extends Model
{
    protected $table = 'beneficiary_images';
    protected $fillable = ['beneficiary_number', 'image_id', 'image_url'];
}

