<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'badge_text',
        'button_text',
        'button_url',
        'image',
        'sort_order',
        'status',
    ];

    /**
     * Scope for active banners ordered by sort_order.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1)->orderBy('sort_order', 'asc')->orderBy('id', 'desc');
    }
}
