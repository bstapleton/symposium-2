<?php

namespace App\Enums;

enum Role: int
{
    case ADMIN = 1;
    case USER = 2;
    case MODERATOR = 4;
}
