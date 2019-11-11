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
//        dd("here isOwn");
        $tree         = buildTree($elements, $parentId);
//        dd($tree);
        $availableIds = collectValues($tree, 'id', []);
        // add parent ID
        $availableIds[] = $parentId;
//        dd($availableIds);
        $res          = in_array($checkingId, $availableIds);
//        dd($res);
        return $res;
    }
}
