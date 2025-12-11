<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'subtitle', 'button_text', 'category', 'image_url', 'text_color', 'position', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
