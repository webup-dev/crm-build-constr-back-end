<?php

use App\Models\User;

//if (!function_exists('get_role')) {
//    function get_role(User $user)
//    {
//        $roles    = $user->roles;
//        $roleName = 'superadmin';
//
//        return $roleName;
//    }
//}

if (!function_exists('oneFromArrInOtherArr')) {
    /**
     * Is there at least one element from an array in another array
     *
     * @param array $needleArr needle array
     * @param array $otherArr  other array
     *
     * @return bool
     */
    function oneFromArrInOtherArr($needleArr, $otherArr)
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
    /**
     * Function buildTree builds tree as array of parent-children
     *
     * 0 => array:9 [
     *  "id" => 10
     *  "level" => 2
     *  "order" => 1
     *  "name" => "Administration"
     *  "parent_id" => 9
     *  "deleted_at" => null
     *  "created_at" => "2020-03-20 15:58:52"
     *  "updated_at" => "2020-03-20 15:58:52"
     *  "children" => array:1 [
     *    0 => array:8 [
     *    "id" => 16
     *    "level" => 3
     *    "order" => 1
     *    "name" => "Administrative assistant"
     *    "parent_id" => 10
     *    "deleted_at" => null
     *    "created_at" => "2020-03-20 15:58:52"
     *    "updated_at" => "2020-03-20 15:58:52"
     *    ]
     *  ]
     * ],
     * 1 => array:8 [
     *   "id" => 11
     *   "level" => 2
     *   "order" => 2
     *   "name" => "Accounting"
     *   "parent_id" => 9
     *   "deleted_at" => null
     *   "created_at" => "2020-03-20 15:58:52"
     *   "updated_at" => "2020-03-20 15:58:52"
     * ]

     * @param array $elements array of organizations under $parentId without it
     * @param int   $parentId organization ID
     *
     * @return array
     */
    function buildTree(array $elements, $parentId = 0)
    {
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
    /**
     * Function collectValues collects all values the field $key
     *     from the investigated tree
     * array:7 [
     *   0 => 10
     *   1 => 16
     *   2 => 11
     *   3 => 12
     *   4 => 13
     *   5 => 14
     *   6 => 15
     * ]

     * @param array  $sourceArr investigated tree
     * @param string $key       collected field
     * @param array  $result    initial array of collected values
     *
     * @return array
     */
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
    /**
     * Function collects ID from the collection that has tree structure
     *
     * @param array $elements   collection
     * @param int   $parentId   parent ID
     * @param int   $checkingId checking Id
     *
     * @return bool
     */
    function isOwn(array $elements, $parentId, $checkingId)
    {
        $availableIds = collectIds($elements, $parentId);
        return in_array($checkingId, $availableIds);
    }
}

if (!function_exists('collectIds')) {
    /**
     * Function collects ID from the collection that has tree structure
     *
     * @param array $elements collection
     * @param int   $parentId parent ID
     *
     * @return array array of Id included parent Id
     */
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
    /**
     * Is user a customer?
     *
     * @param array $roleNamesArr Array of role names
     *
     * @return bool
     */
    function isCustomer(array $roleNamesArr)
    {
        return oneFromArrInOtherArr(
            $roleNamesArr, ['customer-individual', 'customer-organization']
        );
    }
}

if (!function_exists('isOrganizational')) {
    /**
     * Is user an organizational user?
     *
     * @param array $roleNamesArr Array of role names
     *
     * @return bool
     */
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
        return oneFromArrInOtherArr($roleNamesArr, $roles);
    }
}

if (!function_exists('isPlatform')) {
    /**
     * Is user a platform level user?
     *
     * @param array $roleNamesArr Array of role names
     *
     * @return bool
     */
    function isPlatform(array $roleNamesArr)
    {
        $roles = [
            'developer',
            'platform-superadmin',
            'platform-admin'
        ];
        return oneFromArrInOtherArr($roleNamesArr, $roles);
    }
}
