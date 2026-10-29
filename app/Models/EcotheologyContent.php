<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcotheologyContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'theme_tag',
        'waste_category',
        'arabic_text',
        'translation',
        'reference',
        'reflection',
        'action',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
