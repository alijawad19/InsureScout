<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentData extends Model
{
    use HasFactory;

    protected $table = 'payment_data';

    protected $fillable = [
        'enqId', 
        'application_id', 
        'payment_status'
    ];
}
