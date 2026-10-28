<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'points' => 'integer',
            'total_deposits' => 'integer',
        ];
    }

    public function deposits()
    {
        return $this->hasMany(WasteDeposit::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Berikan poin gamifikasi berdasarkan kategori sampah
     */
    public function awardPoints(string $category, float $weightKg = 0.25): int
    {
        $pointsMap = [
            'organik' => 10,
            'kertas' => 15,
            'plastik' => 20,
            'logam' => 25,
            'logam_kaca' => 25,
        ];

        $earned = $pointsMap[strtolower($category)] ?? 10;
        $this->points += $earned;
        $this->total_deposits += 1;

        // Perbarui Eco Badge otomatis
        if ($this->points >= 600) {
            $this->eco_badge = 'Earth Hero 👑';
        } elseif ($this->points >= 300) {
            $this->eco_badge = 'Green Guardian 🥇';
        } elseif ($this->points >= 100) {
            $this->eco_badge = 'Eco Warrior 🥈';
        } else {
            $this->eco_badge = 'Eco Starter 🥉';
        }

        $this->save();
        return $earned;
    }
}
