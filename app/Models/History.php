<?php

namespace App\Models;

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
    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'created_at',
        'title',
        'text'
    ];
}
