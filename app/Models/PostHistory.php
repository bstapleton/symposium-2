<?php

namespace App\Models;

use App\Traits\HasSqid;
use App\Utility\Sqid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * @property integer post_id
 */
class PostHistory extends History
{
    use HasFactory, HasSqid;

    protected $fillable = [
        'post_id',
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
}
