<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalData extends Model
{
    use HasFactory;

    protected $table = 'proposal_data';

    protected $fillable = [
        'enqId',
        'provider_id',
        'tenure',
        'sum_insured',
        'net_premium',
        'gst',
        'total_premium'
    ];
}
