# Information

The internal engineering ticketing system hosted at [engineering.banhampoultryuk.com.local](http://engineering.banhampoultryuk.com.local/)

Development hosted at [github.com/BanhamPoultryUK/engineering-2](https://github.com/BanhamPoultryUK/engineering-2)

# Technologies
- Windows
- PHP
- MySQL
- MSSQL
- HTML
- CSS
- JavaScript

# Staging
There is no staging environment.

# Deployment
Push your changes and pull the repository on `BanLinux`

# Actions
 - Members
   - Create
   - Change Details
   - Change Password
   - Enable
   - Disable
   - Log In
   - Log Out
   - Log Out Everywhere
 - Factory Management (See [#2](https://github.com/BanhamPoultryUK/engineering-2/issues/2))
   - Departments
     - Create
     - Edit
     - Disable
   - Lines
     - Create
     - Edit
     - Disable
   - Machines
     - Create
     - Edit
       - Move between Departments/Lines (See [#3](https://github.com/BanhamPoultryUK/engineering-2/issues/3))
       - Change AssetTag (See [#4](https://github.com/BanhamPoultryUK/engineering-2/issues/4))
     - Disable
 - Tickets
   - File
   - Assign Engineers
   - Comment
   - Change Status
     - Previous date (See [#1](https://github.com/BanhamPoultryUK/engineering-2/issues/1))
     - Safety Checks
   - Index
     - List All
     - Soft Search by AssetTag / Machine Description (See [#5](https://github.com/BanhamPoultryUK/engineering-2/issues/5))
     - Filter by Department
     - Filter by Line
     - Filter by Machine (AssetTag)
     - Filter by Status
     - Filter by Assigned to Me
 - Parts
   - Add Parts
   - Remove Parts
 - Attachments
   - Upload
