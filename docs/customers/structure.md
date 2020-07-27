#Customer structure

## Used DB tables
users
customers
customer-organizations
customer-notes
customer-files
contacts
contact-notes
contact-files
organizations

### Customers
id
user_id
Account Name
Account Type
Mailing Address Line 1 ?
Mailing Address Line 2 ?
City ?
State ?
Zip ?

### Customer-organizations
id
customer_id
organization_id
customer_owner_user_id default organizational super admin

### Customer-notes
id
customer_id
author_user_id
note

### Customer-files
id
customer_id
filename
owner_user_id

### Contacts
id
customer_id
Prefix (Mr. Mrs. Ms. Dr.)
First Name
Last Name
Suffix
Title
Department
Role
Home Phone
Work Phone
Extension
Mobile Phone
Fax
Email (Work)
Email (Personal)
Mailing Address Line 1
Mailing Address Line 2
City
State
Zip
Status
Contact_owner_id default organizational super admin
Comments
Files

### Contact-notes
id
contact_id
author_user_id
note

### Contact-files
id
contact_id
filename
owner_user_id

## Creating of a new customer


