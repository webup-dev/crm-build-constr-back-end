#Customer structure

## Used DB tables
users
customers
user_customers 
user_contacts
customer_notes 
customer_files
user_contact_notes
user_contact_files 
organizations

### Customers
#### customers <-> organizations: many-to-one
id
Account Name
Account Type
Organization ID
Billing Address Line 1
Billing Address Line 2
Billing City
Billing State
Billing Zip
customer_owner_user_id default organizational super admin

### User-Customers (pivot table)
#### Users <-> Customers: many-to-many
id
user_id
customer_id

### User_contacts
#### users <-> users_contacts: one-to-one
id
user_id
Prefix (Mr., Mrs., Ms., Dr.)
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

### Customer_notes
#### Customer <-> Customer_notes: one-to-many
id
customer_id
author_id
comment
parent_id
level
deleted_at

### Customer_files
####  Customer <-> Customer_files: one-to-many 
id
customer_id
description
filename
owner_user_id
deleted_at

### Contact_notes
#### Contact <-> Contact_notes: one-to-many 
id
contact_id
author_id
comment
parent_id
level
deleted_at

### Contact_files
#### User <-> Contact_files: one-to-many
id
contact_id
description
filename
owner_user_id
deleted_at
