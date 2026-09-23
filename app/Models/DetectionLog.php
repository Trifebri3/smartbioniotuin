<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetectionLog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'confidence' => 'float',
        'raw_data' => 'array',
        'latency_ms' => 'integer',
    ];

    protected $appends = ['image_url', 'category_label', 'category_color', 'formatted_time'];

    public function getImageUrlAttribute()
    {
        if (empty($this->image_path)) {
            return asset('camera.jpg');
        }

        // If it starts with http/https
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return asset($this->image_path);
    }

    public function getCategoryLabelAttribute()
    {
        $cat = strtolower($this->category ?? '');
        return match ($cat) {
            'organik' => 'Sampah Organik',
            'plastik' => 'Sampah Plastik',
            'kertas' => 'Sampah Kertas',
            'logam', 'logam_kaca' => 'Sampah Logam & Kaca',
            'auto' => 'Sampah Organik',
            default => ucfirst($this->category ?? 'Tidak Diketahui'),
        };
    }

    public function getCategoryColorAttribute()
    {
        $cat = strtolower($this->category ?? '');
        return match ($cat) {
            'organik' => [
                'name' => 'emerald',
                'hex' => '#10B981',
                'bg' => 'bg-emerald-500/10',
                'border' => 'border-emerald-500/30',
                'text' => 'text-emerald-400',
                'badge' => 'bg-emerald-500 text-black',
                'glow' => 'shadow-[0_0_20px_rgba(16,185,129,0.35)]',
            ],
            'plastik' => [
                'name' => 'sky',
                'hex' => '#0284C7',
                'bg' => 'bg-sky-500/10',
                'border' => 'border-sky-500/30',
                'text' => 'text-sky-400',
                'badge' => 'bg-sky-500 text-white',
                'glow' => 'shadow-[0_0_20px_rgba(2,132,199,0.35)]',
            ],
            'kertas' => [
                'name' => 'amber',
                'hex' => '#F59E0B',
                'bg' => 'bg-amber-500/10',
                'border' => 'border-amber-500/30',
                'text' => 'text-amber-400',
                'badge' => 'bg-amber-500 text-black',
                'glow' => 'shadow-[0_0_20px_rgba(245,158,11,0.35)]',
            ],
            'logam', 'logam_kaca' => [
                'name' => 'rose',
                'hex' => '#E11D48',
                'bg' => 'bg-rose-500/10',
                'border' => 'border-rose-500/30',
                'text' => 'text-rose-400',
                'badge' => 'bg-rose-500 text-white',
                'glow' => 'shadow-[0_0_20px_rgba(225,29,72,0.35)]',
            ],
            default => [
                'name' => 'slate',
                'hex' => '#64748B',
                'bg' => 'bg-slate-500/10',
                'border' => 'border-slate-500/30',
                'text' => 'text-slate-400',
                'badge' => 'bg-slate-600 text-white',
                'glow' => 'shadow-none',
            ],
        };
    }

    public function getFormattedTimeAttribute()
    {
        return $this->created_at ? $this->created_at->format('H:i:s, d M Y') : '-';
    }
}
