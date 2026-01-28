<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaPolicy extends Model
{
      public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
