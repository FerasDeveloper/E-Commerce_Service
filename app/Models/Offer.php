<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
  protected $guarded = [];

  protected $casts = [
    'benefit_config' => 'array',
    'is_active' => 'boolean',
    'is_code_offer' => 'boolean',
    'start_at' => 'datetime',
    'end_at' => 'datetime',
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }
}

