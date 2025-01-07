<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Models;

class AuthorisedUser extends User
{
    protected $table = 'users';

    public function permission(): string|false
    {
        return 'manage';
    }
}
