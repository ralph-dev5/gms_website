<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\StreetReportLog;

class Report extends Model
{
    /** @use HasFactory<\Database\Factories\ReportFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'image',
        'location',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    protected static function booted(): void
    {
        static::created(function (Report $report) {
            if ($report->title) {
                StreetReportLog::create([
                    'street' => $report->title,
                    'reported_date' => $report->created_at->toDateString(),
                    'report_id' => $report->id,
                ]);
            }
        });
    }
}
