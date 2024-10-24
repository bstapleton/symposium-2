<?php

namespace App\Models;

use Awobaz\Compoships\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer id
 * @property string slug
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
    ];

    public $timestamps = false;

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function histories(): HasMany
    {
        return $this->hasMany(PostHistory::class);
    }

    protected function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }
}
