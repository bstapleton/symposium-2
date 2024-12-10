<?php

namespace App\Models;

use App\Events\ReplyDeleting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property integer id
 * @property string replyable_type
 * @property integer replyable_id
 * @property integer parent_id
 * @property integer user_id
 * @property string title
 * @property string text
 * @property string created_at
 * @property string updated_at
 */
class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'replyable_type',
        'replyable_id',
        'parent_id',
        'user_id',
        'title',
        'text',
        'created_at',
        'updated_at',
    ];

    protected $morphMap = [
        Post::class => 'post',
        PostRevision::class => 'post_revision',
        Reply::class => 'replies',
    ];

    protected $dispatchesEvents = [
        'deleting' => ReplyDeleting::class,
    ];

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function postRevision(): BelongsTo
    {
        return $this->belongsTo(PostRevision::class);
    }

    public function replyable()
    {
        return $this->morphTo();
    }

    public function replies(): MorphMany
    {
        return $this->morphMany(self::class, 'replyable');
    }
}
