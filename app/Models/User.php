<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // PENTING UNTUK API

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id']; // Semua kolom boleh diisi (kecuali ID)

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi: User punya banyak transaksi/enrollment
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Relasi: User punya banyak progress belajar
    public function courseProgress()
    {
        return $this->hasMany(CourseProgress::class);
    }
}
