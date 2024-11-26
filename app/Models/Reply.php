<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * @property integer id
 * @property integer post_history_id
 * @property integer status
 * @property string title
 * @property string text
 * @property string created_at
 * @property string updated_at
 */
class Reply extends Model
{
    use HasFactory;

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replyable()
    {
        return $this->morphTo();
    }

    protected function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    protected function replies(): HasMany
    {
        return $this->hasMany(self::class);
    }
}
