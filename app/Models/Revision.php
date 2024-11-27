<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string created_at
 * @property string title
 * @property string text
 */
abstract class Revision extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'created_at',
        'title',
        'text'
    ];
}
