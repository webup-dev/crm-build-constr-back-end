# DB structure
## DB table "Leads"
name                    // lead name 
department_id
lead_type_id            //from DB table "Lead Types"
status_id               //from DB table "Lead Statuses"
declined_reason_other   //if status_id == declined with the reason "other"
workflow_type_id        //from DB table "Workflow types"
workflow_stage_id       //from DB table "Workflow Stages"

// Goal object
Site_Address
Site_Address_Line2
City
State
Zip

// link to requester account
requester_id

files
note
lead_owner_id
created_by 
updated_by 
created_at 
updated_at 

## DB table "Lead Types"
id
department_id
name

### Examples
Steep Slope Roofing
Siding
Low Slope Roofing
Windows
Doors
Weatherization
Carpentry
Gutters

#### Old Examples
- Retail Capital Improvement
- Storm/Insurance Loss
- Private Bid General Contractor
- Private Bid Property Manager
- Private Bid Direct to Owner
- Public Bid to General Contractor
- Public Bid Prime
- Residential Billable Service
- Commercial Billable Service
- Warranty

## DB table "Lead Statuses"
id
organization_id
parent_id
name

### Examples
- Unqualified
- Qualified & Accepted (Moves to create opportunity)
- Declined
  
  //Decline reasons
  - Not within company scope.
  - Unable to perform work.
  - Test
  - Duplicate
  - Other
  
## DB table "Workflow Stages"
id
organization_id
name
description
 
### Examples
1. Documenting
2. Clarification
3. Estimation by estimator
4. Receiving architectural drawings and specification documents
5. Internal Estimation
6. Decision

## DB table "Workflow types"
id
name
stage_sequence
note

### Examples
- Retail Capital Improvement
- Storm/Insurance Loss
- Private Bid General Contractor
- Private Bid Property Manager
- Private Bid Direct to Owner
- Public Bid to General Contractor
- Public Bid Prime
- Residential Billable Service
- Commercial Billable Service
- Warranty

### Examples
| id | name         | stage_sequence | note                                           |
| -- | -----------: | :------------- | :-------                                       |
| 1  | simplest     | 6              | Decision                                       |
| 2  | Estimator    | 1,3,6          | Documenting, Estimation by estimator, Decision |

## DB table "Requesters"
id
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
website
other_source
note
created_by 
updated_by 
created_at 
updated_at

## DB table "lead_files"
id
lead_id
file_id
