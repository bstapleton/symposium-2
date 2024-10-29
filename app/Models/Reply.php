<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer id
 * @property integer post_id
 */
class Reply extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    protected function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    protected function replies(): HasMany
    {
        return $this->hasMany(self::class);
    }

    protected function histories(): HasMany
    {
        return $this->hasMany(ReplyHistory::class);
    }
}
