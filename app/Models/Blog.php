<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'category',
        'image',
        'author',
        'tags',
        'status',
        'views'
    ];

    protected $casts = [
        'tags' => 'array',
        'views' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $attributes = [
        'status' => 'draft',
        'views' => 0,
        'author' => 'Admin'
    ];
}