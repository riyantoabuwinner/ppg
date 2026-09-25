<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'subtitle', 'avatar', 'type', 'content', 'media_url', 'rating', 'is_active', 'sort_order'
    ];
}
