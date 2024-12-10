<?php

namespace App\Models;

use App\Events\PostCreating;
use App\Events\PostDeleting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer id
 * @property string slug
 * @property string created_at
 * @property string title
 * @property string text
 * @property integer user_id
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'created_at',
        'title',
        'text',
        'user_id',
    ];

    public $timestamps = false;

    protected $dispatchesEvents = [
        'creating' => PostCreating::class,
        'deleting' => PostDeleting::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->morphMany(Reply::class, 'replyable');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(PostRevision::class);
    }
}
