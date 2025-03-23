<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Notification extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relasi ke User (Notifikasi bisa dikirim ke user tertentu atau broadcast)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
