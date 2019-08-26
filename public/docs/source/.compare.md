---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://wny2.com/docs/collection.json)

<!-- END_INFO -->

#Books
<!-- START_5aa83249c70fc4682eb6e7c338c176d4 -->
## Get index of books

> Example request:

```bash
curl -X GET -G "http://wny2.com/api/book" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL("http://wny2.com/api/book");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
[
    {
        "id": 1,
        "name": "Department 1",
        "description": "Description 1",
        "parent_id": null,
        "created_at": "2019-06-24 07:12:03",
        "updated_at": "2019-06-24 07:12:03"
    },
    {
        "id": 4,
        "name": "Department 2",
        "description": "Description 2",
        "parent_id": 1,
        "created_at": "2019-06-24 07:12:03",
        "updated_at": "2019-06-24 07:12:03"
    }
]
```
> Example response (404):

```json
{
    "message": "Departments not found."
}
```

### HTTP Request
`GET /api/book`


<!-- END_5aa83249c70fc4682eb6e7c338c176d4 -->

<!-- START_c2dd6dd5a208acd6b81bc81de3cc6aa6 -->
## /api/book/{id}
> Example request:

```bash
curl -X GET -G "http://wny2.com/api/book/1" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL("http://wny2.com/api/book/1");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "error": {
        "message": "Wrong number of segments",
        "status_code": 401
    }
}
```

### HTTP Request
`GET /api/book/{id}`


<!-- END_c2dd6dd5a208acd6b81bc81de3cc6aa6 -->

<!-- START_c1820a26a881003f80a51e3744e29783 -->
## /api/book
> Example request:

```bash
curl -X POST "http://wny2.com/api/book" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL("http://wny2.com/api/book");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "error": {
        "message": "Wrong number of segments",
        "status_code": 401
    }
}
```

### HTTP Request
`POST /api/book`


<!-- END_c1820a26a881003f80a51e3744e29783 -->

<!-- START_8d6a6d440a728e82bef78eda6f7929ca -->
## /api/book/{id}
> Example request:

```bash
curl -X PUT "http://wny2.com/api/book/1" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL("http://wny2.com/api/book/1");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "error": {
        "message": "Wrong number of segments",
        "status_code": 401
    }
}
```

### HTTP Request
`PUT /api/book/{id}`


<!-- END_8d6a6d440a728e82bef78eda6f7929ca -->

<!-- START_9b8146842203975d47b3ae0c2ec3d0e7 -->
## /api/book/{id}
> Example request:

```bash
curl -X DELETE "http://wny2.com/api/book/1" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL("http://wny2.com/api/book/1");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "error": {
        "message": "Wrong number of segments",
        "status_code": 401
    }
}
```

### HTTP Request
`DELETE /api/book/{id}`


<!-- END_9b8146842203975d47b3ae0c2ec3d0e7 -->

#Users
<!-- START_e9607afa368380b4c34066ca777ef25e -->
## Get the authenticated User

> Example request:

```bash
curl -X GET -G "http://wny2.com/api/auth/me" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL("http://wny2.com/api/auth/me");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "name": "Super User",
        "email": "superuser@admin.com",
        "created_at": "2019-12-08 13:25:36",
        "updated_at": "2019-12-08 13:25:36"
    },
    "message": "User retrieved successfully."
}
```
> Example response (404):

```json
{
    "success": false,
    "data": "Empty",
    "message": "User not found."
}
```
> Example response (401):

```json
{
    "error": {
        "message": "The token has been blacklisted",
        "status_code": 401
    }
}
```

### HTTP Request
`GET /api/auth/me`


<!-- END_e9607afa368380b4c34066ca777ef25e -->

<!-- START_5e71b6bfe676d9132ff093a42227a094 -->
## Delete user

> Example request:

```bash
curl -X DELETE "http://wny2.com/api/users/1" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL("http://wny2.com/api/users/1");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "message": "User deleted successfully."
}
```
> Example response (500):

```json
{
    "success": false,
    "message": "Can not get User."
}
```
> Example response (500):

```json
{
    "success": false,
    "message": "User did not delete."
}
```

### HTTP Request
`DELETE /api/users/{id}`


<!-- END_5e71b6bfe676d9132ff093a42227a094 -->

#general
<!-- START_03d29f415a921367ef8611a608633ffb -->
## /api/auth/signup
> Example request:

```bash
curl -X POST "http://wny2.com/api/auth/signup" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL("http://wny2.com/api/auth/signup");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (422):

```json
{
    "error": {
        "message": "422 Unprocessable Entity",
        "errors": {
            "name": [
                "The name field is required."
            ],
            "email": [
                "The email field is required."
            ],
            "password": [
                "The password field is required."
            ]
        },
        "status_code": 422
    }
}
```

### HTTP Request
`POST /api/auth/signup`


<!-- END_03d29f415a921367ef8611a608633ffb -->

<!-- START_7ba029714012cd9c08cc50ae4dee9d7a -->
## Log the user in

> Example request:

```bash
curl -X POST "http://wny2.com/api/auth/login" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL("http://wny2.com/api/auth/login");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (422):

```json
{
    "error": {
        "message": "422 Unprocessable Entity",
        "errors": {
            "email": [
                "The email field is required."
            ],
            "password": [
                "The password field is required."
            ]
        },
        "status_code": 422
    }
}
```

### HTTP Request
`POST /api/auth/login`


<!-- END_7ba029714012cd9c08cc50ae4dee9d7a -->

<!-- START_b706911fc2143359a27a47586522c8c7 -->
## /api/auth/recovery
> Example request:

```bash
curl -X POST "http://wny2.com/api/auth/recovery" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL("http://wny2.com/api/auth/recovery");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (422):

```json
{
    "error": {
        "message": "422 Unprocessable Entity",
        "errors": {
            "email": [
                "The email field is required."
            ]
        },
        "status_code": 422
    }
}
```

### HTTP Request
`POST /api/auth/recovery`


<!-- END_b706911fc2143359a27a47586522c8c7 -->

<!-- START_bac19b6778c34ade7c9006a863aed43c -->
## /api/auth/reset
> Example request:

```bash
curl -X POST "http://wny2.com/api/auth/reset" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL("http://wny2.com/api/auth/reset");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (422):

```json
{
    "error": {
        "message": "422 Unprocessable Entity",
        "errors": {
            "token": [
                "The token field is required."
            ],
            "email": [
                "The email field is required."
            ],
            "password": [
                "The password field is required."
            ]
        },
        "status_code": 422
    }
}
```

### HTTP Request
`POST /api/auth/reset`


<!-- END_bac19b6778c34ade7c9006a863aed43c -->

<!-- START_5868c9422bc3266cef6569c8b841eb06 -->
## Log the user out (Invalidate the token)

> Example request:

```bash
curl -X POST "http://wny2.com/api/auth/logout" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL("http://wny2.com/api/auth/logout");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "error": {
        "message": "Wrong number of segments",
        "status_code": 401
    }
}
```

### HTTP Request
`POST /api/auth/logout`


<!-- END_5868c9422bc3266cef6569c8b841eb06 -->

<!-- START_c4738b8f9d87493a71d28c323a50e0dc -->
## Refresh a token.

> Example request:

```bash
curl -X POST "http://wny2.com/api/auth/refresh" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL("http://wny2.com/api/auth/refresh");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "error": {
        "message": "Token could not be parsed from the request.",
        "status_code": 500
    }
}
```

### HTTP Request
`POST /api/auth/refresh`


<!-- END_c4738b8f9d87493a71d28c323a50e0dc -->


