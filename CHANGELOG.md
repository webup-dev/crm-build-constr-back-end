# Changelog
All notable changes to this project will be documented in this file.

## [Unreleased]
## [0.26.3] - 2020-04-09
### Changed 
- LeadStatusesController. Method Soft-deleted lead-statuses
    - Tests are updated
    - Model LeadStatus. Relationship requests functions are added
    - lead_status_of_parent is added in the response.
    
## [0.26.2] - 2020-04-08
### Changed 
- MenusController. Soft-deleted lead-statuses are added
    - MenusControllerTest are updated.

## [0.26.1] - 2020-04-08
### Changed 
- Lead Status migration. Column other_reason is deleted
    - Model LeadStatus
    - Validations for requests of store, update
    - LeadStatusesControllerTest
    - LeadStatusesController.

## [0.26.0] - 2020-04-07
### Changed 
- MenusController. Soft-deleted trades are added
    - MenusControllerTest are updated.

## [0.25.0] - 2020-04-06
### Added 
- Module Lead Status is created
    - Specification
    - Migration
    - Seeders
    - Tests
    - Request validations
    - Methods: index, store, update, softDestroy, show,
      indexSoftDeleted, restore, permanentDelete. 

## [0.24.0] - 2020-04-03
### Added 
- Module Lead Types/Trades is created
    - Specification
    - Migration
    - Seeders
    - Tests
    - Request validations
    - Methods: index, store, update, softDestroy, show,
      indexSoftDeleted, restore, permanentDelete. 

## [0.23.4] - 2020-04-02
### Changed
- Lead Source Category Seeder is changed.

## [0.23.3] - 2020-03-27
### Changed
- MenusController
    - SoftDeleted LeadSources is added.

## [0.23.2] - 2020-03-26
### Added
- Module Lead Sources. Method getListOfOrganizations
    - Tests are created,
    - Method is created.
- Module Lead Sources. Method getListOfCategories
    - Tests are created,
    - Method is created.
    
## [0.23.1] - 2020-03-25
### Changed
- Module Lead Sources. Method index
    - Eloquent relationships are added,
    - Tests are updated.
    
## [0.23.0] - 2019-03-18
### Added 
- Module Lead Sources is created
    - Tests for all methods, seeders, request validations are created,
    - Methods are created: index, store, update, softDestroy, show,
      indexSoftDeleted, restore, permanentDelete. 

## [0.22.2] - 2019-03-18
### Changed
- Module LeadSources is renamed to LsCategories
    - DB table name is changed to ls_categories,
    - Migration is changed,
    - LeadSourcesController is renamed to LsCategoriesController,
    - LsCategoriesController is updated,
    - Validation files is renamed and updated,
    - Model is renamed and updated,
    - Seeders are renamed and updated (main and test),
    - Documentation is updated,
    - Tests are renamed and updated.

## [0.22.1] - 2019-03-17
### Fixed
- Bug CCFEC-863. Deploy is made.

## [0.22.0] - 2019-03-12
### Added
- Menus for platform-admin, organization-general-manager are created,
- Module LeadSources is created
        Methods: index, store, update, softDestroy, show,
        indexSoftDeleted, restore, permanentDelete.

## [0.21.2] - 2019-03-10
### Added
- Menus for platform-admin, organization-general-manager are created.

### Changed
- code in CustomersController is cleaned,
- code in middleware Organizations_OrganizationAdmin is cleaned,
- code in middleware UserProfiles_OrganizationAdmin is cleaned,
- code in web routes is cleaned,
- MTI license is implemented as license prototype,
- Changelog is updated.
  
## [0.21.1] - 2019-03-09
### Changed
- Module "File Download". File extension is changed to 
  from src filename.
### Added
    - Examples of uploading files: doc/files.   

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
