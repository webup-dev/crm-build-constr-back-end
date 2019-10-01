<?php

use App\Models\User;

if (! function_exists('get_role')) {
    function get_role(User $user)
    {
        $roles = $user->roles;
        $roleName = 'superadmin';

        return $roleName;
    }
}
