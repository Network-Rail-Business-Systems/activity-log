<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    public function manage(User $user): Response
    {
        return $user->can('manage') === true
            ? $this->allow('User can manage')
            : $this->deny('User cannot manage');
    }
}
