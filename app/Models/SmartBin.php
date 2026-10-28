<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SmartBin extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function deposits()
    {
        return $this->hasMany(WasteDeposit::class);
    }

    public function isFull(): bool
    {
        return $this->capacity_organik >= 90.0 ||
               $this->capacity_plastik >= 90.0 ||
               $this->capacity_kertas >= 90.0 ||
               $this->capacity_logam >= 90.0;
    }
}

