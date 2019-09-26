## Function getRules
### Output 
````
/**
 * Get rules for requested user
 *
 * @response 200 {
 *  "success": true,
 *  "data": {
 *    "roles": [{
 *       "books": {
 *        "index": true,
 *        "store": true,
 *        "update": true,
 *        "delete": false
 *       },
 *       "roles": {
 *        "index": true,
 *        "store": false,
 *        "update": false,
 *        "delete": false
 *       }
 *    },
 *    "permissions": [{
 *      "roles": {
 *        "store": true,
 *        "update": [132, 134]
 *       }
 *    }],
 *    "restrictions": [{
 *      "roles": {
 *      "index": [133, 158]
 *       }
 *    }]
 * }],
 *  "message": "Rules are retrieved successfully."
 * }
 */
````
 
## Workflow
* Get user (from Auth)
* Get user's roles (from DB table user_roles)
* Get all methods that concerns to roles
* Add to methods their controllers
* Format Data:
````
[
  "success" => true,
  "message" => "Rules are retrieved successfully.",
  "data"    => [
    "controller1" => ["index", "create", "edit", "delete"], 
    "controller2" => ["index"] 
  ]
]
````
