<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;

    protected $table = 'user_details';

    protected $fillable = [
        'user_id',
        'fullName',
        'phone',
        'ktpNumber',
        'bankAccount',
        'is_approved',
        'ktpImage',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    /**
     * Relasi ke model User (One to One)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Set path upload KTP ke dalam storage
     */
    public function getKtpImageUrlAttribute()
    {
        return $this->ktpImage ? asset('storage/' . $this->ktpImage) : null;
    }
}
