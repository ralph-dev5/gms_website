<?php

namespace App\Models;

use Database\Factories\ReportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    /** @use HasFactory<ReportFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'image',
        'location',
    ];

    public function user(): BelongsTo
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
