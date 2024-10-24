<?php

namespace App\Models;

use App\Traits\HasSqid;
use App\Utility\Sqid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property integer parent_id
 * @property string created_at
 * @property string title
 * @property string text
 */
abstract class History extends Model
{
    use HasFactory, HasSqid;

    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'created_at',
        'title',
        'text'
    ];

    protected function sqid(): Attribute
    {
        return Attribute::make(
            get: fn () => Sqid::encode($this->id)
        );
    }

    public function getRouteKeyName(): string
    {
        return 'sqid';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->resolveRouteBinding($this, Sqid::decode($value), 'id')->first();
    }
}
