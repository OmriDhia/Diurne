# User Management Module

## Purpose
Handles authentication, profiles, and permissions.

## Conception
![USER Architecture](images/USER_conception.png)  

## Key Tables
```sql
User (id, email, password)
Profile (id, name)
Permission (id, name)