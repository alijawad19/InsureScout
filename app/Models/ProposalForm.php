<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalForm extends Model
{
    use HasFactory;

    protected $table = 'proposal_form';

    protected $fillable = [
        'enqId',
        'address',
        'nominee_name',
        'nominee_relation',
        'nominee_dob',
        'nominee_contact',
    ];
}
