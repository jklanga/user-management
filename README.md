## User Management
This is a web-based application written in Laravel 7. It allows the users to register and manage their profile including other user's profile if you are an admin user.
### User roles:
- Admin: Can list/view and edit other user's details.
- Manager: Can only list/view users.
- User: Can only update his/her profile.

## Setup

```bash
cp .env.example .env
```
Update the database and mail settings in the .env file

Run the migrations and seeds
```bash
php artisan migrate
php artisan db:seed
