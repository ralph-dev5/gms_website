<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StreetReportLog extends Model
{
    protected $fillable = [
        'street',
        'reported_date',
        'report_id',
    ];

    protected $casts = [
        'reported_date' => 'date',
    ];
}
