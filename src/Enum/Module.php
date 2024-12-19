<?php

namespace App\Enum;

enum Module: String
{
    case PHP = 'PHP';
    case JS = 'JS';
    case CSS = 'CSS';
    public static function getRoles(): array
    {
        return array_map(fn(self $role) => $role->value, self::cases());
    }
}
