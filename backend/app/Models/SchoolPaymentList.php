<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolPaymentList extends Model
{
    protected $table = 'school_payment_list';

    protected $fillable = [
        'school',
        'school_emis',
        'province',
        'district',
        'school_bank',
        'school_bank_branch',
        'school_bank_branch_code',
        'school_bank_account',
        'district_grant_bank',
        'district_grant_bank_branch',
        'district_grant_bank_branch_code',
        'district_grant_bank_account',
        'district_administration_bank',
        'district_administration_bank_branch',
        'district_administration_bank_branch_code',
        'district_administration_bank_account'
    ];

    // You might want to disable timestamps if you are not using them
    public $timestamps = true;
}
