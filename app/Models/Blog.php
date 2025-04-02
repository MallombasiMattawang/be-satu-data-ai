<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'author_id',
        'category',
        'image_url',
        'published_at',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn($image) => $image ? url('/storage/public/blogs/' . $image) : null,
        );
    }
}
