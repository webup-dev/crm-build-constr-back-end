# Repository for demo-purposes
## What things I'd like to pay attention
This is back-end API server only. Front-end is in another repository. 
### CHANGELOG.md
It uses [Semantic Versioning](https://semver.org/).
Commits are frequent to give the client an ability to see current development status (Constant Viewable Development, CVD).
There is a full description of each version.

### TDD (Test Driven Development)
Path: /tests/Functional/Api/V1/Controllers

There are more than 700 test. They cover all api-endpoints, all available HTTP code responses, all available permissions.

To create correct testing it was created:
 + copy of existing DB
 + copies of existing migrations and seeds. 
 
It means that tests may run and don't influence the work of the project.

### API documentation
Path: /public/docs

[API documentation](http://codeci.pp.ua/docs/index.html) is created automatically from PHP comments.

### Models
Path: /app/models

Almost all models use soft deleting. Most of them use complex eloquent relationships between DB tables.  

### Controllers
Path: /app/Api/V1/Controllers

Example: /app/Api/V1/Controllers/StagesController.php

All controllers has detailed PHP comments. Comments are included in API documentation and are the main content for API Documentation.
