<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'documents',
        'status',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
