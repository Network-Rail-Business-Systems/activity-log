<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AuthorisedUser extends User
{
    use SoftDeletes;

    protected $table = 'users';

    public function permission(): string|false
    {
        return 'manage';
    }
}
