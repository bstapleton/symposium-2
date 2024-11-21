<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use RedExplosion\Sqids\Concerns\HasSqids;

/**
 * @property integer post_id
 */
class PostHistory extends History
{
    use HasFactory, HasSqids;

    protected $fillable = [
        'post_id',
        'parent_id',
        'created_at',
        'title',
        'text',
    ];

    public function historyable()
    {
        return $this->morphTo();
    }

    protected function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    protected function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    protected function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Post::class);
    }

    protected function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }
}
