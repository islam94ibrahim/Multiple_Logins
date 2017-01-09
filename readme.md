## Prerequisites to run the Solution

- Please rollback any migrations from the other solutions.
```
php artisan migrate:rollback
```
- Please run the following commands in project folder:
```
composer require felixkiss/uniquewith-validator:2.*
```
```
php artisan migrate
```

---
## Solution Workflow

### Registration
- The user can register with one of the two different roles: Orchestra Officer and Musician.
- After completing the form an email will be sent to the user, here I’m using “log” as the mail driver. All emails will be at “storage\logs\laravel.log” file.
- User follows confirmation link to activate his account.
- Accounts with same email address will always share the same password.

### Signing in
- The user will enter his email, password and login type to choose either an Orchestra Officer, Musician or Member.

### Orchestra Officer members addition
- I assumed that the Members are Musicians that will join the Orchestra Officer’s Orchestra, so their type will be changed to Members and they can login now as Members.

### Forgotten password
- All users have an ability to reset their password, including members.
-Resetting password is done using the steps mentioned in the scenario.

---
### Solution Done By: Islam Emam Ibrahim
### Email: islam94.ibrahim@gmail.com
