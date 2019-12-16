#Customer structure

## Used DB tables
users
contacts // includes user_id, one-to-one
customers // includes organization_id, one-to-one
contact-customers // one-to-many 
customer-notes // one-to-many 
customer-files // one-to-many 
contact-notes // one-to-many 
contact-files // one-to-many 
customer-contacts // one-to-many
organizations

### Contacts
id
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

### Customers
id
Account Name
Account Type
Billing Address Line 1
Billing Address Line 2
Billing City
Billing State
Billing Zip

### User-Customers
id
user_id
customer_id

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

### Customer-contacts
id
customer_id
contact_id
customer_owner_user_id default organizational super admin






## Creating of a new customer


