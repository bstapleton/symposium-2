<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class TitleOrText implements Rule
{
    public function passes($attribute, $value)
    {
        $data = Arr::get(request()->all(), $attribute);

        return !empty($data['title']) || !empty($data['text']);
    }

    public function message()
    {
        return 'Either title or text must be provided.';
    }
}
