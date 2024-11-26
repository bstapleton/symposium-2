<?php

namespace App\Models;

use App\Rules\TitleOrText;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Validator;
use RedExplosion\Sqids\Concerns\HasSqids;

/**
 * @property integer post_id
 * @property integer user_id
 * @property integer parent_id
 * @property string created_at
 * @property string title
 * @property string text
 */
class PostRevision extends History
{
    use HasFactory, HasSqids;

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'created_at',
        'title',
        'text',
    ];

    public static function create(array $attributes = [])
    {
        $attributes = static::validate($attributes);

        return parent::create($attributes);
    }

    protected static function validate(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'title' => [new TitleOrText],
            'text' => [new TitleOrText],
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        return $attributes;
    }

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

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->morphMany(Reply::class, 'replyable');
    }
}
