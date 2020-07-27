# DB structure. Accounts
````
 accounts
 |--customers
 |  |--individuals---------------------------|
 |  |--organizations--|                      |
 |--suppliers---------|                      |
 |--installers--------|                      |
 |--manufacturers-----|                      |
 |--other-------------|                      |
                      |--organization roles--|
                                             |--personal contacts
````
## Customers
id
name
type //individuals or organization
files
note
created_by 
updated_by 
created_at 
updated_at 

## Customer_individual
id
customer_id
billing_address_line_1
billing_address_line_2
billing_city
billing_state
billing_zip
note
created_by 
updated_by 
created_at 
updated_at 

## Customer_organization
id
customer_id
address_line_1
address_line_2
city
state
zip
company_email_1
company_email_2
web_site
note
created_by 
updated_by 
created_at 
updated_at 

## Organization roles
id
organization_type // customer, supplier, etc
organization_id
title
department
role
note
created_by 
updated_by 
created_at 
updated_at 

## Personal contacts
id
account_type //(installer, supplier,…) array
account_id
prefix // mr., mrs., Ms., Dr.
first_name
last_name
suffix
email_work
email_personal
address_line_1
address_line_2
city
state
zip
phone_home
phone_work
phone_extension
mob_1
mob_2
fax
note
files //in te storage
created_by 
updated_by 
created_at 
updated_at

## Supplier Account
id
name
address_line_1
address_line_2
city
state
zip
company_email_1
company_email_2
web_site
tax_id
files
note
status
created_by 
updated_by 
created_at 
updated_at

## Installer Account
id
name
tax_id
address_line_1
address_line_2
city
state
zip
company_email_1
company_email_2
web_site
status 
files
trades // string 
Workers Comp Carrier
Workers Comp Expiration Date
General Liability Carrier
General Liability Expiration Date
Excess Liability Umbrella Carrier
Excess Liability Umbrella Expiration Date
Commercial Automobile Carrier
Commercial Automobile Expiration Date
note
created_by 
updated_by 
created_at 
updated_at

## Manufacturer Account
id
name
address_line_1
address_line_2
city
state
zip
company_email_1
company_email_2
web_site
status 
files
products // string 
note
created_by 
updated_by 
created_at 
updated_at

## Other Vendor Account
id
name
address_line_1
address_line_2
city
state
zip
company_email_1
company_email_2
web_site
status 
files
services // string 
note
created_by 
updated_by 
created_at 
updated_at


