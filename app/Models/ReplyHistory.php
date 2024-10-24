<?php

namespace App\Models;

use App\Traits\HasSqid;
use App\Utility\Sqid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * @property integer reply_id
 */
class ReplyHistory extends History
{
    use HasFactory, HasSqid;

    protected $fillable = [
        'reply_id',
    ];

    public function historyable()
    {
        return $this->morphTo();
    }

    protected function reply(): BelongsTo
    {
        return $this->belongsTo(Reply::class);
    }

    protected function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    protected function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Reply::class);
    }
}
