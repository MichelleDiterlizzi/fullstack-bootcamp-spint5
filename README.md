# 🎉 sprint5-event-api

A RESTful API for event management with authentication and role-based access control, built with Laravel.  
**Note:** This project is API-only (no web views).

---

## 🚀 Features

- **User Management:** Register, login, profile management, and role assignment.
- **Event Management:** Create, update, delete, list, and view events.
- **Event Attendance:** Join and leave events.
- **Categories:** List and filter events by category.
- **OAuth2 Authentication:** Secure endpoints with Laravel Passport.
- **Role-Based Access:** User and admin roles with different permissions.
- **Advanced Logic:** Popular events, free events, time-based filters, etc.
- **Functional Testing:** Automated tests with PHPUnit (see `tests/Feature`).

---

## 👤 User Roles

- **User:** Manage own profile, create/manage own events, join events.
- **Admin:** Manage all users and events, full API access.

---

## 📚 Main Endpoints

See [`routes/api.php`](routes/api.php) for the full list.  
Some examples:

- `POST /api/register` — Register user
- `POST /api/login` — User login
- `GET /api/events` — List events
- `POST /api/events` — Create event (auth required)
- `POST /api/events/{id_event}/users` — Join event
- `DELETE /api/events/{id_event}/users` — Leave event
- `GET /api/categories` — List categories

---

## ⚙️ Installation & Usage

> **You do NOT need to have Laravel installed globally.**  
> All dependencies are managed via Composer.

### 1. Requirements

- PHP 8.2 or higher (with extensions: `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `sodium`, `gd`)
- Composer (https://getcomposer.org/)
- MySQL (for development)
- SQLite (for testing, in-memory)
- Git (recommended)
- XAMPP or similar stack for Windows users

### 2. Clone the repository

```sh
git clone https://github.com/your-username/sprint5-event-api.git
cd sprint5-event-api
```

### 3. Install dependencies

```sh
composer install
```

If you get an error about the `ext-sodium` or `ext-gd` extension, enable them in your `php.ini`:
- Open your `php.ini` (e.g. `C:\xampp\php\php.ini`)
- Find `;extension=sodium` and/or `;extension=gd` and remove the `;`
- Save and restart Apache

### 4. Environment setup

Copy the example environment file and generate the app key:

```sh
cp .env.example .env
php artisan key:generate
```

### 5. Database setup

By default, the **application uses MySQL**.  
Make sure your `.env` file has the correct MySQL settings, for example:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sprint5_api_db
DB_USERNAME=root
DB_PASSWORD=
```

- Create the database `sprint5_api_db` in your MySQL server before running migrations.
- You can use phpMyAdmin, MySQL Workbench, or the command line:

  ```sh
  mysql -u root -p -e "CREATE DATABASE sprint5_api_db;"
  ```

> **Note:**  
> The automated tests use an in-memory SQLite database, so you do **not** need to configure SQLite for development.

### 6. Run migrations and seeders

```sh
php artisan migrate --seed
```

### 7. Install Passport

```sh
php artisan passport:install
```
If prompted to run pending migrations and you get an error like `table already exists`, see the Troubleshooting section below.

---

## 🧪 Running the API (optional)

If you want to run the API locally (for manual testing):

```sh
php artisan serve
```

The API will be available at `http://localhost:8000`.

---

## 🧪 Running Feature Tests

**This is the main way to verify the API.**

```sh
php artisan test --testsuite=Feature
```

Or simply:

```sh
php artisan test
```

All tests in [`tests/Feature`](tests/Feature) will be executed.

---

## ⚠️ Troubleshooting

### Duplicate Passport Migration Files

If you see errors like `table 'oauth_auth_codes' already exists` during migrations or tests, you may have duplicate Passport migration files.  
**Solution:**  
- Check your `database/migrations` folder.
- Keep only one migration file for each Passport table (`oauth_auth_codes`, `oauth_access_tokens`, etc.).
- Delete any duplicates (files with similar names but different timestamps).

### GD Extension Not Installed

If you get an error like `GD extension is not installed`, PHP is missing the GD extension (used for image processing).

**How to enable GD in XAMPP (Windows):**
1. Open `C:\xampp\php\php.ini`
2. Find the line `;extension=gd` or `;extension=gd2`
3. Remove the `;` at the beginning to uncomment it:
   ```
   extension=gd
   ```
4. Save the file.
5. Restart Apache from the XAMPP control panel.

### Other Common Issues

- **ext-sodium missing:** Enable `extension=sodium` in your `php.ini`.
- **Tests fail due to database:** Make sure your `phpunit.xml` contains:
  ```xml
  <env name="DB_CONNECTION" value="sqlite"/>
  <env name="DB_DATABASE" value=":memory:"/>
  ```
- **Cache issues:** Run `php artisan config:clear` and `php artisan cache:clear`.

---

## 📁 Project Structure

- Controllers: `app/Http/Controllers/API`
- Models: `app/Models`
- Policies: `app/Policies`
- Routes: [`routes/api.php`](routes/api.php)
- Feature Tests: [`tests/Feature`](tests/Feature)

---

## ❓ FAQ

- **Do I need to install Laravel globally?**  
  No, everything is handled via Composer.

- **Is there a frontend?**  
  No, this is API-only.

- **How do I test the API?**  
  Run the feature tests as described above.

---
