<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaPolicy extends Model
{
    protected $fillable = [
        'organization_id',
        'priority',
        'first_response_minutes',
        'resolution_minutes',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
