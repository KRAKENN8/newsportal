# News Portal

A student news website with a public front for reading articles and leaving comments, and a separate admin panel for managing news.

The project uses a simple **MVC** layout in PHP, MySQL for storage, and Bootstrap for the UI. The public interface is in Estonian (navigation labels such as *Stardileht*, *Kategooriad*, *Otsi*).

---

## Table of contents

1. [Description](#description)
2. [Tech stack](#tech-stack)
3. [Main features](#main-features)
4. [Requirements](#requirements)
5. [Installation](#installation)
6. [How to run](#how-to-run)
7. [Demo / admin account](#demo--admin-account)
8. [Project structure](#project-structure)
9. [What each layer does](#what-each-layer-does)
10. [Database](#database)
11. [Public routes](#public-routes)
12. [Admin routes](#admin-routes)
13. [Configuration](#configuration)
14. [Troubleshooting](#troubleshooting)

---

## Description

The application is a classic PHP news site:

- visitors open the homepage (latest articles), browse categories, read a full article, and post comments;
- new readers can register (username, email, password);
- editors sign in at `/admin/` and add, edit, or delete news.

Pretty URLs are handled with Apache `mod_rewrite` (`.htaccess`). Article images are stored in MySQL as BLOB (`MEDIUMBLOB` recommended).

This is the **main** (original) version of the course project. There is no public login, profile, about page, or working search handler in the router.

---

## Tech stack

| Layer | Technology |
|---|---|
| Language | PHP 7.4+ / 8.x |
| Architecture | MVC (custom, no framework) |
| Database | MySQL / MariaDB |
| Access | PDO (`SET NAMES utf8`) |
| Web server | Apache (XAMPP) |
| Routing | `.htaccess` + PHP routers |
| Auth (admin) | PHP sessions, `password_hash` / `password_verify` |
| Frontend | HTML, CSS, Bootstrap 4, Font Awesome, Google Fonts |

There is no Composer, Node, or npm. PHP files are served directly by Apache.

---

## Main features

**Public site**

- Homepage with the latest 3 articles (`TOP 3 NEWS`)
- Full news list (`all`)
- Filter by category (`category?id=`)
- Article page with image, text, comments, and a comment form
- Registration (`registerForm` → `registerAnswer`)
- Navigation: categories dropdown, home, register, search field in the header

**Admin panel**

- Login form
- News list (title, category, author)
- Create news (title, text, category, picture)
- Edit news (picture optional)
- Delete news (confirmation form)

**Not implemented on this branch**

- Public login / logout / profile
- Search results page (`search` is in the menu but has no route)
- `Info` menu item (`testError`) — leads to the 404 view

---

## Requirements

- Windows, macOS, or Linux
- [XAMPP](https://www.apachefriends.org/) (Apache + PHP + MySQL/MariaDB), or an equivalent stack
- PHP 7.4 or newer (8.x is fine)
- PHP extensions: `pdo_mysql`, `mbstring`, `session`
- Apache modules: `mod_rewrite`
- `AllowOverride All` for the site directory (so `.htaccess` works)

Expected local path with XAMPP on Windows:

```text
C:\xampp\htdocs\newsportal
```

---

## Installation

### 1. Place the project

Copy or clone the repository into the web root:

```text
C:\xampp\htdocs\newsportal
```

Folder name `newsportal` matches the default URLs below.

### 2. Start services

In **XAMPP Control Panel** start:

1. Apache
2. MySQL

### 3. Create the database

The app connects to a database named **`newsportal`**.

Open http://localhost/phpmyadmin and create a database:

- Name: `newsportal`
- Collation: `utf8_general_ci` or `utf8mb4_unicode_ci`

Default credentials in `inc/Database.php`:

| Setting | Value |
|---|---|
| Host | `localhost` |
| User | `root` |
| Password | *(empty)* |
| Database | `newsportal` |

If your MySQL user or password is different, edit `inc/Database.php` before running the site.

### 4. Create tables

This branch has **no SQL dump**. Create tables in phpMyAdmin (SQL tab) or the MySQL client:

```sql
CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(100) NOT NULL,
  email VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  status VARCHAR(20) NOT NULL,
  registration_date DATE NOT NULL,
  pass VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE category (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE news (
  id INT NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  text MEDIUMTEXT NOT NULL,
  picture MEDIUMBLOB NOT NULL,
  category_id INT NOT NULL,
  user_id INT NOT NULL,
  PRIMARY KEY (id),
  KEY category_id (category_id, user_id),
  CONSTRAINT news_ibfk_1 FOREIGN KEY (category_id) REFERENCES category (id),
  CONSTRAINT news_ibfk_2 FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE comments (
  id INT NOT NULL AUTO_INCREMENT,
  news_id INT NOT NULL,
  text VARCHAR(500) NOT NULL,
  date DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

Add at least one category, for example:

```sql
INSERT INTO category (name) VALUES
  ('Sport'),
  ('Politics'),
  ('Culture'),
  ('Technology');
```

---

## How to run

1. Apache and MySQL are running.
2. Database `newsportal` exists and has the tables above.
3. Open the public site:

   http://localhost/newsportal/

4. Open the admin panel:

   http://localhost/newsportal/admin/

The front controller is `index.php` (public) and `admin/index.php` (admin). Rewrite rules send unknown paths to those files.

---

## Demo / admin account

There is no bundled demo user. Create one in two steps:

1. Open http://localhost/newsportal/registerForm and register (password at least 6 characters).
2. Promote that user to admin in phpMyAdmin:

```sql
UPDATE users SET status = 'admin' WHERE email = 'your@email.com';
```

Then sign in at http://localhost/newsportal/admin/ with the same email and password.

**Note:** new articles in admin are saved with `user_id = 1`. Keep the first registered user as `id = 1`, or change that value in `admin/modelAdmin/modelAdminNews.php`.

These accounts are for local study only.

---

## Project structure

```text
newsportal/
├── index.php                 Public front controller
├── style.css
├── .htaccess                 Pretty URLs → index.php
│
├── inc/
│   └── Database.php          PDO connection helper
│
├── route/
│   └── routing.php           Public URL → controller method
│
├── controller/
│   └── Controller.php        Public actions
│
├── model/
│   ├── News.php              Articles
│   ├── Category.php          Categories
│   ├── Comments.php          Comments
│   └── Register.php          Sign up
│
├── view/
│   ├── layout.php            Shared HTML shell (header, nav, footer)
│   ├── start.php             Homepage
│   ├── allnews.php           All articles
│   ├── catnews.php           Articles by category
│   ├── readnews.php          Single article
│   ├── news.php              News HTML helpers (ViewNews)
│   ├── comments.php          Comment HTML helpers
│   ├── category.php          Category menu items
│   ├── formRegister.php      Registration form
│   ├── answerRegister.php    Registration result
│   └── error404.php
│
├── public/
│   └── css/                  Bootstrap and custom styles
│
└── admin/
    ├── index.php             Admin front controller
    ├── .htaccess
    ├── routeAdmin/           Admin routing
    ├── controllerAdmin/      Admin controllers
    ├── modelAdmin/           Admin models (auth, news CRUD, categories)
    ├── viewAdmin/            Admin templates
    └── public/               Admin CSS / JS
```

---

## What each layer does

| Path | Responsibility |
|---|---|
| `index.php` | Starts the session, loads models, includes the public router, prints `$response` |
| `inc/Database.php` | Opens a PDO connection; `getOne`, `getAll`, `executeRun` |
| `route/routing.php` | Reads the last URL segment and GET data, calls `Controller` |
| `controller/Controller.php` | Loads data from models and includes the matching view |
| `model/` | SQL (no HTML) |
| `view/` | Markup; most pages fill `$content` and include `layout.php` |
| `admin/index.php` | Same idea for the back office |
| `admin/modelAdmin/modelAdmin.php` | Admin session login / logout |
| `admin/modelAdmin/modelAdminNews.php` | Create / update / delete news |
| `admin/modelAdmin/modelAdminCategory.php` | Category list for news forms |
| `admin/controllerAdmin/` | Admin page flow |
| `admin/viewAdmin/` | Admin forms and lists |

Request flow (public):

```text
Browser → Apache (.htaccess) → index.php → routing.php → Controller → Model → View → HTML
```

---

## Database

| Table | Purpose |
|---|---|
| `users` | Accounts: username, email, hashed `password`, `status` (`admin` / `user`), `registration_date`, plain `pass` (legacy field filled on register) |
| `category` | Rubrics shown in the top menu |
| `news` | Title, text, picture BLOB, `category_id`, `user_id` |
| `comments` | `news_id`, text, datetime (no user link on this branch) |

`news` has foreign keys to `category` and `users`.

---

## Public routes

Defined in `route/routing.php`.

| Path | Action |
|---|---|
| `/` | Homepage (latest 3 news) |
| `all` | All news |
| `category?id=` | News in a category |
| `news?id=` | Single article |
| `insertcomment?comment=&id=` | Add comment (GET) |
| `registerForm` | Registration form |
| `registerAnswer` | Registration POST handler |

Unknown paths render a 404 view.

---

## Admin routes

Defined in `admin/routeAdmin/routingAdmin.php`. Base URL: `/newsportal/admin/`.

| Path | Action |
|---|---|
| `/` | Login form (or dashboard if already signed in) |
| `login` | Process login |
| `logout` | Log out |
| `newsAdmin` | News list |
| `newsAdd` | Add form |
| `newsAddResult` | Save new article |
| `newsEdit?id=` | Edit form |
| `newsEditResult?id=` | Save changes |
| `newsDel?id=` | Delete confirmation |
| `newsDelResult?id=` | Delete article |

---

## Configuration

All DB settings live in `inc/Database.php` (`$host`, `$user`, `$password`, `$baseName`). Both public and admin code use this class.

The router uses the **last** path segment. Keep the project in `htdocs/newsportal`, or point a virtual host at the repository root.
