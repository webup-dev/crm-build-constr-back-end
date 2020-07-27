# Customer account. Specification
## Definitions
| Term               | Description                           | Abbreviation   |
| :----------------: | :------------------------------------ | :------------: |
| Customer User      | A User that is a part of the Customer | CU             |

## Goal
CU must have a page where it may see customer's main info like to:
- Name, Customer type, Billing City, Billing Line 1, Billing Line 2, State, Postal Code
- list of users that make up the customer
- contacts of all users that make up the customer

CU must have availability to:
- add new user to the customer
- create request to delete a user from the customer
- edit own contact data.

### Technical notes
This page is similar to /#/admin/customers/1/show but has some differences. So the development of described page here may use this analogue. 

This page may use the same developed front-end components.
