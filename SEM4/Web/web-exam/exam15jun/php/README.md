# PHP — How to Run

## Step 1 — Start XAMPP

1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache**
3. Click **Start** next to **MySQL**
4. Both rows should turn green

## Step 2 — Create the database

1. Open browser → `http://localhost/phpmyadmin`
2. Click **SQL** tab at the top
3. Open `C:\xampp\htdocs\exam\setup.sql` in Notepad, copy everything, paste into the SQL box
4. Click **Go**
5. You should see a success message and `examdb` appear in the left panel

## Step 3 — Edit config (only if the exam uses different names)

1. Open `C:\xampp\htdocs\exam\php\config.php`
2. Change lines 4–7 if DB credentials differ:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');        // add password here if set
   define('DB_NAME', 'examdb');  // change DB name here
   ```
3. Change lines 11–19 if the exam uses different table/column names:
   ```php
   define('T_DEV',      'SoftwareDeveloper');  // developer table name
   define('T_PROJ',     'Project');            // project table name
   define('C_DEV_NAME', 'name');               // etc.
   ```
4. Save the file. Nothing else needs to be changed.

## Step 4 — Open the app

Open browser → **`http://localhost/exam/php/`**

That's it. No compilation, no build tools, no restarts needed.

---

## File structure
```
php/
  config.php         ← ★ ONLY FILE YOU NEED TO EDIT ★
  style.css          ← shared styles
  nav.php            ← navigation bar (included in every page)
  index.php          ← set username
  all_projects.php   ← display all projects
  my_projects.php    ← display my projects
  assign.php         ← assign developer to projects
  developers.php     ← all developers + JS skill filter
```
