<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PremiumData extends Model
{
    use HasFactory;

    protected $table = 'premium_data';

    protected $fillable = [
        'name',
        'dob',
        'gender',
        'email',
        'contact',
        'pincode',
    ];
}
