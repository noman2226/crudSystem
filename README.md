# Student CRUD (PHP + MySQL)

Minimal modern PHP CRUD app to manage student records.

Setup
1. Ensure you have PHP and MySQL (e.g., XAMPP) running.
2. Import the schema: in MySQL client or phpMyAdmin run `schema.sql`.

   mysql -u root -p < schema.sql

3. Place this folder in your webroot (e.g., `htdocs/crudSystem`).
4. Adjust DB credentials in `db.php` if needed.
5. Open `http://localhost/crudSystem/` in your browser.

Files
- `db.php` — PDO connection helper
- `index.php` — list students (Read)
- `create.php` — create student (Create)
- `edit.php` — edit student (Update)
- `delete.php` — delete student (Delete)
- `assets/style.css` — simple modern styles
- `schema.sql` — database + table creation
