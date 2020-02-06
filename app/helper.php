<?php

use App\Models\User;

if (!function_exists('get_role')) {
    function get_role(User $user)
    {
        $roles    = $user->roles;
        $roleName = 'superadmin';

        return $roleName;
    }
}

if (!function_exists('one_from_arr_in_other_arr')) {
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

if (!function_exists('buildTree')) {
    // build tree of parentId with included parentId
    function buildTree(array $elements, $parentId = 0)
    {
//        dd("here");
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }
}

if (!function_exists('collectValues')) {
    // collect all values of the key from tree
    function collectValues(array $sourceArr, $key = 'id', $result = [])
    {
        foreach ($sourceArr as $item) {
            $result[] = $item[$key];
            if (isset($item['children'])) {
                $result = collectValues($item['children'], $key = 'id', $result);
            }
        }

        return $result;
    }
}

if (!function_exists('isOwn')) {
    // collect all values of the key from tree
    function isOwn(array $elements, $parentId, $checkingId)
    {
        $tree         = buildTree($elements, $parentId);
        $availableIds = collectValues($tree, 'id', []);
        // add parent ID
        $availableIds[] = $parentId;
        $res            = in_array($checkingId, $availableIds);

        return $res;
    }
}

if (!function_exists('collectIds')) {
    // collect all ids from an array of the objects with parent_id
    function collectIds(array $elements, $parentId)
    {
        $tree         = buildTree($elements, $parentId);
        $availableIds = collectValues($tree, 'id', []);
        // add parent ID
        $availableIds[] = $parentId;

        return $availableIds;
    }
}

if (!function_exists('isCustomer')) {
    // Has current user a customer role?
    function isCustomer(array $roleNamesArr)
    {
        return one_from_arr_in_other_arr($roleNamesArr, ['customer-individual', 'customer-organization']);
    }
}

if (!function_exists('isOrganizational')) {
    // Has current user a customer role?
    function isOrganizational(array $roleNamesArr)
    {
        $roles = [
            'organization-superadmin',
            'organization-admin',
            'organization-general-manager',
            'organization-sales-manager',
            'organization-production-manager',
            'organization-administrative-leader',
            'organization-estimator',
            'organization-project-manager',
            'organization-administrative-assistant'
        ];
        return one_from_arr_in_other_arr($roleNamesArr, $roles);
    }
}

if (!function_exists('isPlatform')) {
    // Has current user a customer role?
    function isPlatform(array $roleNamesArr)
    {
        $roles = [
            'developer',
            'platform-superadmin',
            'platform-admin'
        ];
        return one_from_arr_in_other_arr($roleNamesArr, $roles);
    }
}
