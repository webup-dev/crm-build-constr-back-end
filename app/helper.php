<?php

use App\Models\User;

if (! function_exists('get_role')) {
    function get_role(User $user)
    {
//        $resource = $resource ?? plural_from_model($model);
        $roleName = 'superadmin';

        return $roleName;
    }
}
