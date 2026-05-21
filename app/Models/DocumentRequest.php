<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentRequest extends Model
{
    protected $fillable = [
        'user_id',
        'document_type',
        'purpose',
        'status',
        'admin_note',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function documentTypes(): array
    {
        return [
            'Barangay Clearance',
            'Cedula / Community Tax Certificate (CTC)',
            'Certificate of Indigency',
            'Barangay Residency Certificate',
            'Certificate of Good Moral Character',
            'Barangay Business Clearance',
            'First Time Job Seeker Certificate',
            'Solo Parent Certification',
            'Barangay Blotter Report',
            'Certificate of Cohabitation',
            'Certificate of No Objection',
            'Barangay Permit',
            'Travel Clearance for Minors',
            'Certification for Low Income / Unemployment',
            'Lupon / Mediation Certification',
        ];
    }
}
