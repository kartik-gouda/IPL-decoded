# IPL Decoded Backend Setup

This project now includes a PHP backend and MySQL database for the IPL dashboard.

## Files added
- `index.php` — frontend page that loads charts and tables from the backend.
- `api.php` — JSON API endpoint for stats, toss, overs, players, and venues.
- `setup.php` — creates the `ipl_decoded` database, tables, and inserts sample data.
- `config.php` — database configuration for XAMPP (`root` / blank password by default).
- `db_init.sql` — SQL schema and seed data for manual database import.

## Setup
1. Copy the project folder to `xampp/htdocs/ipl_project`.
2. Open `http://localhost/ipl_project/setup.php` in your browser.
3. After setup succeeds, open `http://localhost/ipl_project/index.php`.

## Notes
- If your MySQL user or password is different, edit `config.php` before running `setup.php`.
- The old `IPL_PROJECT.html` file remains as a static copy, but the working dashboard is now `index.php`.
