<?php

namespace App\Constants;

class Permission
{
    const MANAGE_USERS = 'MANAGE_USERS';
    const MANAGE_ROLES = 'MANAGE_ROLES';
    const MANAGE_PERMISSIONS = 'MANAGE_PERMISSIONS';
    const MANAGE_MERCHANTS = 'MANAGE_MERCHANTS';

    public static function all(): array
    {
        return [
            self::MANAGE_USERS,
            self::MANAGE_ROLES,
            self::MANAGE_PERMISSIONS,
            self::MANAGE_MERCHANTS
        ];
    }
}
