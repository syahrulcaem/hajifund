<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'fundingGoal',
        'deadline',
        'status',
        'entrepreneur_id',
    ];

    public function entrepreneur()
    {
        return $this->belongsTo(EntrepreneurDetails::class, 'entrepreneur_id');
    }

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function businessProposals()
    {
        return $this->hasMany(BusinessProposal::class);
    }
}
