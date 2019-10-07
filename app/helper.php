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

if (! function_exists('one_from_arr_in_other_arr')) {
    function one_from_arr_in_other_arr($needleArr, $otherArr)
    {
        foreach ($needleArr as $item) {
            if (in_array($item, $otherArr)) {
                return true;
            }
        }

        return false;
    }
}
