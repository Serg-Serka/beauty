This is a test task.

The main purpose is booking for beauty services in salons.

To install the project:
1. ``git clone https://github.com/Serg-Serka/beauty.git``
2. ``cd beauty/``
3. ``composer install``
4. ``npm install``
5. ``cp .env.example .env``
6. ``php artisan key:generate``
7. Create empty database
8. Fill `.env` file to make a bound with application and created database. You should set `DB_DATABASE`, `DB_USERNAME` and `DB_PASSWORD`.
9. ``php artisan migrate``
10. `` php artisan db:seed --class=ServiceSeeder``
11. ``php artisan serve``
12. ``npm run dev``
13. Go to `http://localhost:8000/beauty`, the booking form is waiting for you there!
14. Go to `http://localhost:8000/admin`, at first you should create user, then just login and you will have an access to all admin grids!
