<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasUuids; // Tambahkan HasUuids

    protected $keyType = 'string'; // UUID adalah string
    public $incrementing = false; // Non-increment karena UUID

    protected $fillable = [
        'id', // Tambahkan id agar bisa diisi otomatis
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Relasi ke user_details (satu ke satu)
    public function userDetails()
    {
        return $this->hasOne(UserDetails::class);
    }

    // Relasi ke entrepreneur_details (satu ke satu)
    public function entrepreneurDetails()
    {
        return $this->hasOne(EntrepreneurDetails::class);
    }

    // Relasi ke business (satu ke banyak)
    public function businesses()
    {
        return $this->hasMany(Business::class, 'entrepreneur_id');
    }

    // Relasi ke investment (satu ke banyak)
    public function investments()
    {
        return $this->hasMany(Investment::class, 'investor_id');
    }

    // Relasi ke transactions (satu ke banyak)
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
