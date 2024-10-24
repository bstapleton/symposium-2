<?php
namespace App\Utility;
use Sqids\Sqids;
final class Sqid
{
    public static function encode(?int $number): string
    {
        if (is_null($number)) {
            return '';
        }

        return resolve(Sqids::class)->encode([$number]);
    }

    public static function decode(string $sqid): int
    {
        return resolve(Sqids::class)->decode($sqid)[0];
    }
}
