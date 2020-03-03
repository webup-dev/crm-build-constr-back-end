# Changelog
All notable changes to this project will be documented in this file.

## [Unreleased]
## [0.21.0] - 2019-03-02
### Added
- Module "File Download".

## [0.20.1] - 2019-03-01
### Changed
- FilesController. indexSoftDeleted. Error Code 209 is substitute to 204.

## [0.20.0] - 2019-02-06
### Added
- Module Files.

## [0.19.0] - 2019-01-21
### Added
- Module User-Customers.
- Module User-Details with add/edit actions.
### Changed
- Module Customers: customer_owner_user_id is added.
- Module Customers: added relations with DB tables user_customers, customer_comments.

## [0.18.0] - 2019-12-13
### Changed
- Customer. Field "City" is implemented. Fields "City", "Line 1" are required.
### Added
- Module CustomerComments.

## [0.17.0] - 2019-11-21
### Changed
- Module "Customers". 

## [0.16.0] - 2019-11-15
### Added
- Level of organizations (Platform is "owner" of all organizations). Roles are updated according to the new organizations structure. API endpoint SoftDeleted is created. 

## [0.15.0] - 2019-10-31
### Added
- Module Customers.

## [0.14.2] - 2019-10-23
### Added
- Module Organizations. Item ordering is added.

## [0.14.1] - 2019-10-19
### Fixed
- Bugs are fixed.

## [0.14.0] - 2019-10-18
### Added
- Soft deleting, restoring, permanent deleting are added to Module User Profiles.

## [0.13.1] - 2019-10-17
### Added
- Module User Profiles. Bugs are fixed. Middlewares are added.

## [0.13.0] - 2019-10-10
### Added
- Module User Profiles.

## [0.12.2] - 2019-10-07
### Added
- Middlewares developer, guest, platform-superadmin. Tests are adapted.

## [0.12.1] - 2019-10-04
### Fixed
- Module Organizational Structure. Fixing of bugs.

## [0.12] - 2019-10-03
### Added
- Module Organizational Structure. 

## [0.11] - 2019-10-01
### Added
- Module Activities. 

## [0.10.1] - 2019-09-30
### Changed
- Module Rules (logic of entering). Controller was changed. 

## [0.10] - 2019-09-26
### Added
- Module Rules (logic of entering). 

## [0.9.1] - 2019-09-23
### Added
- Module Method-Roles. Store, Update actions are changed. 

## [0.9.0] - 2019-09-21
### Added
- Module Method-Roles is added. 

## [0.8.1] - 2019-09-18
### Added
- Module Methods. Method "show" is changed. 

## [0.8.0] - 2019-09-18
### Added
- Module Methods is added. 

## [0.7.1] - 2019-09-17
### Added method "show" in the module Controllers.
- Method "show" in the module Controllers. 

## [0.0.7] - 2019-09-16
### Added
- Module Controllers. 

## [0.0.6] - 2019-09-10
### Added
- Module User-Roles. Function index/full is added (users, roles, user-roles by 1 request).

## [0.0.5] - 2019-09-05
### Added
- Module User-Roles is added. API documentation is added (http://.../api-documentation)

## [0.0.4] - 2019-09-02
### Added
- Module Roles.

## [0.0.3] - 2019-08-15
### Changed
- Demo module Books. Delete book with specific ID. Response is changed.

## [0.0.2] - 2019-08-13
### Changed
- Changelog.md => CHANGELOG.md.
- Demo module Books. Show book with specific ID. Creator name is added to response.

## [0.0.1] - 2019-08-04
### Added
- Changelog.md.

## [0.0] - 2019-08-03
### Added
- Source Template Installation [@francescomalatesta](https://github.com/francescomalatesta/laravel-api-boilerplate-jwt?source=post_page---------------------------).
- Configuration.
- Books module added for testing and further configuration.
- CORS module is configured for localhost_dev_front - local_domain_api_server, localhost_dist->local_domain - local_domain_api_server
