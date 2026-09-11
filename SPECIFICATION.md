# Technical Specification

## Development and Modernization of the CyberPulse News Portal

### 1. General Information

**Project name:** CyberPulse — News Portal

**Project type:** Information and news web portal.

**Purpose:** Develop a web application for publishing and viewing technology-related news, with support for user registration, authentication, user profiles, commenting, and content management through an administrative panel.

The project shall be implemented as a PHP-based server-side web application using the MVC architecture and a relational MySQL/MariaDB database.

---

# 2. Project Objectives

The main objectives of the project are:

1. Create a public news portal.
2. Allow users to browse technology news by category.
3. Implement a news search function.
4. Implement user registration and authentication.
5. Implement a user profile.
6. Allow authenticated users to post comments on news articles.
7. Provide an administrative interface for managing news publications.
8. Separate the public and administrative parts of the application.
9. Organize the application according to the MVC architecture.
10. Use a relational database for persistent storage of users, categories, news articles, and comments.

---

# 3. Target Audience

## 3.1. Visitors

Unauthenticated users shall be able to:

* view the homepage;
* view all available news;
* browse news by category;
* open individual news articles;
* search for news;
* view the "About" page;
* access the registration page;
* log in to the system.

## 3.2. Registered Users

Authenticated users shall be able to:

* view their profile;
* view their email address;
* change their username;
* log out of the account;
* post comments under news articles.

## 3.3. Administrator / Editor

The administrator shall be able to:

* log in to the administrative panel;
* view the list of publications;
* create news articles;
* edit news articles;
* delete news articles;
* assign categories to news articles;
* upload images for publications;
* log out of the administrative panel.

---

# 4. Functional Requirements

## 4.1. Homepage

The system shall provide a public homepage for the portal.

The homepage shall display the latest or featured news.

Each news item shall contain at least:

* title;
* category;
* image;
* short or full description depending on the design;
* link to the full article.

In the original version of the project, the homepage displayed the latest three news articles. The redesigned version extends this functionality with an improved latest/featured news section.

---

## 4.2. All News Page

A separate page shall display all available publications.

Users shall be able to open an individual article from the list.

**Route:**

`/all`

---

## 4.3. News Categories

News articles shall be organized into categories.

The system shall provide:

* storage of categories in the database;
* assignment of a category to each news article;
* display of available categories;
* filtering of news by category.

Example categories:

* Artificial Intelligence;
* Hardware;
* Cybersecurity;
* Gaming;
* Space.

**Route:**

`/category?id=<ID>`

---

## 4.4. Individual News Article

Each publication shall have its own page.

The page shall contain:

* article title;
* image;
* article text;
* category;
* author information where applicable;
* comments section;
* comment submission form for authenticated users.

**Route:**

`/news?id=<ID>`

---

# 5. Search System

The system shall provide a search function for news articles.

The user enters a search query, after which the system shall:

1. Receive the search string.
2. Search the existing news articles.
3. Display matching publications.
4. Display an appropriate message if no results are found.

**Route:**

`/search?otsi=<query>`

The original interface contained a search field, but there was no complete search handler in the routing system. The redesigned version shall provide both the search route and a search results page.

---

# 6. User Registration

The system shall provide registration for new users.

The registration form shall contain:

* username;
* email address;
* password.

Requirements:

* email addresses shall be unique;
* passwords shall meet the minimum length requirement;
* passwords shall never be stored in plain text;
* passwords shall be hashed before being stored in the database;
* after successful registration, the user shall be able to log in.

The minimum password length shall be 6 characters.

**Routes:**

`/registerForm`

`/registerAnswer`

---

# 7. User Authentication

The system shall provide public user authentication.

Users shall be able to log in using:

* email;
* password.

After successful authentication, a PHP session shall be created for the user.

If the credentials are incorrect, the system shall display an appropriate error message.

The system shall also provide a logout function.

**Routes:**

`/formLogin`

`/loginAnswer`

`/logout`

The original version did not contain public user authentication. The redesigned version introduces a separate authentication system for regular users in addition to the existing administrative authentication.

---

# 8. User Profile

The system shall provide a profile page for authenticated users.

The profile shall display:

* username;
* email address;
* functionality for changing the username.

Access to the profile shall be restricted to authenticated users.

**Route:**

`/profile`

---

# 9. Comments

Authenticated users shall be able to post comments under news articles.

Each comment shall contain:

* author;
* text;
* publication date and time;
* associated news article ID;
* associated user ID.

Requirements:

* unauthenticated users shall not be able to submit comments;
* every comment shall be associated with a specific news article;
* every comment shall be associated with a specific user;
* comments shall be permanently stored in the database.

**Route:**

`/insertcomment`

The comment submission request shall use the `POST` method.

The redesigned database shall provide a proper relationship between comments, users, and news articles.

---

# 10. Administrative Panel

A separate administrative section shall be implemented.

**Base address:**

`/admin/`

The administrative section shall have its own:

* routing;
* controllers;
* models;
* views;
* CSS/JavaScript resources;
* authentication system.

The administrative section shall be separated from the public part of the website.

The administrative functionality shall be located in the `admin/` directory.

---

# 11. Administrator Authentication

Access to the administrative panel shall be restricted.

If an unauthenticated user attempts to access the administrative section, the system shall redirect the user to the login page.

The administrator shall authenticate using an email address and password.

After successful authentication, the administrator shall gain access to the administrative panel.

A logout function shall also be provided.

**Routes:**

`/admin/`

`/admin/login`

`/admin/logout`

---

# 12. News Management

The administrator shall have full CRUD functionality for news articles.

## 12.1. View News

The administrative panel shall display a list of news articles containing basic information:

* title;
* category;
* author;
* edit action;
* delete action.

**Route:**

`/admin/newsAdmin`

## 12.2. Create News

The news creation form shall contain:

* title;
* article text;
* category;
* image.

**Routes:**

`/admin/newsAdd`

`/admin/newsAddResult`

## 12.3. Edit News

The administrator shall be able to modify:

* title;
* article text;
* category;
* image.

**Routes:**

`/admin/newsEdit?id=<ID>`

`/admin/newsEditResult?id=<ID>`

## 12.4. Delete News

The system shall display a confirmation step before deleting a publication.

**Routes:**

`/admin/newsDel?id=<ID>`

`/admin/newsDelResult?id=<ID>`

---

# 13. Image Management

Each news article may contain an image.

Images shall be stored in the database using a `MEDIUMBLOB` field.

The administrator shall be able to:

* upload an image when creating a news article;
* replace an image when editing an article;
* display the image on the public article page.

The project shall provide a mechanism for generating placeholder images for demonstration purposes.

A `seed.php` script shall be used to generate SVG cover images and store them in the database.

---

# 14. Database

The application shall use MySQL or MariaDB.

The main database entities shall be:

## 14.1. users

Stores registered users.

Main fields:

* `id`;
* `username`;
* `email`;
* `password`;
* `status`;
* `registration_date`.

The `status` field determines the user type, for example:

* `user`;
* `admin`.

## 14.2. category

Stores news categories.

Fields:

* `id`;
* `name`.

## 14.3. news

Stores news publications.

Fields:

* `id`;
* `title`;
* `text`;
* `picture`;
* `category_id`;
* `user_id`.

## 14.4. comments

Stores comments.

Fields:

* `id`;
* `news_id`;
* `user_id`;
* `text`;
* `date`.

The following relationships shall exist:

`users → news`

`category → news`

`users → comments`

`news → comments`

---

# 15. Application Architecture

The application shall follow the MVC architecture.

## Model

Models shall be responsible for:

* database operations;
* SQL queries;
* retrieving news;
* managing categories;
* managing users;
* authentication;
* comments.

## View

Views shall be responsible only for presentation.

The application shall provide:

* common layout;
* header;
* navigation;
* footer;
* news pages;
* authentication pages;
* profile page;
* comments section;
* administrative forms.

## Controller

Controllers shall be responsible for:

* processing HTTP requests;
* obtaining data from models;
* selecting the appropriate view;
* passing data to views.

## Router

The application shall provide routing between URLs and controller actions.

General request flow:

`Browser → Apache → index.php → Router → Controller → Model → View → HTML`

---

# 16. Database Connection

The application shall use PDO for database access.

Requirements:

* use prepared SQL statements;
* use `utf8mb4` encoding;
* centralize the database connection;
* avoid creating separate database connections in individual controllers.

The database connection shall be implemented in:

`inc/Database.php`

The public and administrative sections shall use the same database connection mechanism.

---

# 17. Security Requirements

The application shall:

1. Store passwords only as secure hashes.
2. Use `password_hash()` during registration.
3. Use `password_verify()` during authentication.
4. Use PHP sessions for authentication.
5. Restrict profile access to authenticated users.
6. Restrict administrative functionality to administrators.
7. Use PDO prepared statements.
8. Validate all user input.
9. Never trust raw `GET` or `POST` values.
10. Validate uploaded images.
11. Escape user-generated HTML output.
12. Use `POST` for operations that modify data.

---

# 18. Public Routing

The following public routes shall be supported:

| URL               | Purpose                 |
| ----------------- | ----------------------- |
| `/`               | Homepage                |
| `/all`            | All news                |
| `/category?id=`   | News by category        |
| `/news?id=`       | Individual news article |
| `/search?otsi=`   | Search                  |
| `/about`          | About the project       |
| `/registerForm`   | Registration form       |
| `/registerAnswer` | Registration processing |
| `/formLogin`      | Login form              |
| `/loginAnswer`    | Login processing        |
| `/profile`        | User profile            |
| `/logout`         | User logout             |
| `/insertcomment`  | Add comment             |

---

# 19. Administrative Routing

| URL                         | Purpose                      |
| --------------------------- | ---------------------------- |
| `/admin/`                   | Administrative panel / login |
| `/admin/login`              | Administrator authentication |
| `/admin/logout`             | Administrator logout         |
| `/admin/newsAdmin`          | News list                    |
| `/admin/newsAdd`            | Add news form                |
| `/admin/newsAddResult`      | Save new article             |
| `/admin/newsEdit?id=`       | Edit news form               |
| `/admin/newsEditResult?id=` | Save changes                 |
| `/admin/newsDel?id=`        | Delete confirmation          |
| `/admin/newsDelResult?id=`  | Delete article               |

---

# 20. Design and User Interface

The portal shall use a consistent visual style.

The public section shall include:

* website header;
* logo/name;
* navigation;
* category menu;
* search field;
* main content area;
* news cards;
* footer.

The interface shall be responsive and usable on common desktop and mobile screen resolutions.

The original project used Bootstrap, Font Awesome, Google Fonts, and custom CSS. The redesigned version shall provide a more cohesive visual style based on the CyberPulse technology theme.

---

# 21. Initial Database Population

The project shall provide demonstration data for easier installation and testing.

The initial dataset shall contain:

* a test administrator;
* a test user;
* several categories;
* several news articles;
* several comments.

A script named:

`seed.php`

shall be provided for automatically populating the database.

The seeder shall be able to create categories, demonstration articles, images, and comments.

The project shall also provide an SQL database dump:

`cyberpulse.sql`

---

# 22. Environment Requirements

Minimum environment:

* PHP 8.0+;
* Apache;
* MySQL/MariaDB;
* `pdo_mysql` extension;
* `mbstring` extension;
* PHP Sessions;
* Apache `mod_rewrite`.

XAMPP may be used for local development.

The project shall be able to run from:

`htdocs/newsportal`

or through a properly configured Apache Virtual Host.

---

# 23. Project Structure

The expected project structure shall be:

```text
newsportal/
├── index.php
├── seed.php
├── cyberpulse.sql
├── style.css
├── .htaccess
│
├── inc/
│   └── Database.php
│
├── route/
│   └── routing.php
│
├── controller/
│   └── Controller.php
│
├── model/
│   ├── News.php
│   ├── Category.php
│   ├── Comments.php
│   ├── Register.php
│   ├── Login.php
│   └── Profile.php
│
├── view/
│   ├── layout.php
│   ├── start.php
│   ├── allnews.php
│   ├── catnews.php
│   ├── readnews.php
│   ├── search.php
│   ├── about.php
│   ├── comments.php
│   ├── formLogin.php
│   ├── formRegister.php
│   └── profile.php
│
└── admin/
    ├── index.php
    ├── .htaccess
    ├── routeAdmin/
    ├── controllerAdmin/
    ├── modelAdmin/
    ├── viewAdmin/
    └── public/
```

---

# 24. Expected Result

The final result shall be a complete information system called **CyberPulse**, consisting of two interconnected parts.

## Public Section

Users shall be able to:

* open the website;
* browse news;
* filter news by category;
* search for publications;
* open individual articles;
* register;
* log in;
* view and edit their profile;
* post comments;
* log out.

## Administrative Section

Administrators shall be able to:

* log in;
* view publications;
* create news articles;
* edit news articles;
* delete news articles;
* assign categories;
* upload images;
* log out.

---

# 25. Modernization Compared to the Original Version

If the `main` branch is considered the original state of the project, the `remade` branch can be described as the completed modernization of the system.

| Requirement                | Original State | Implemented in `remade` |
| -------------------------- | -------------: | ----------------------: |
| Homepage                   |              ✓ |                       ✓ |
| News list                  |              ✓ |                       ✓ |
| Categories                 |              ✓ |                       ✓ |
| Individual news page       |              ✓ |                       ✓ |
| Comments                   |              ✓ |                       ✓ |
| Registration               |              ✓ |                       ✓ |
| Public login               |              ✗ |                       ✓ |
| User logout                |              ✗ |                       ✓ |
| User profile               |              ✗ |                       ✓ |
| Username editing           |              ✗ |                       ✓ |
| Search                     |              ✗ |                       ✓ |
| About / Info page          |              ✗ |                       ✓ |
| Admin authentication       |              ✓ |                       ✓ |
| News CRUD                  |              ✓ |                       ✓ |
| User-comment relationship  |        Partial |                       ✓ |
| SQL database dump          |              ✗ |                       ✓ |
| Automatic database seeding |              ✗ |                       ✓ |
| UTF-8 / `utf8mb4`          |          Basic |                Improved |
| Demonstration data         |         Manual |               Automated |
| Technology-focused theme   |        General |              CyberPulse |
| MVC architecture           |              ✓ |  Preserved / structured |

The main improvements consist of completing the public user system, adding search and profile functionality, improving the database structure, strengthening the relationship between users and comments, and providing automated project initialization.

---

# 26. Acceptance Criteria

The project shall be considered complete when:

1. A visitor can open the homepage without authentication.
2. A visitor can open the list of news articles.
3. A visitor can select a news category.
4. A visitor can open an individual article.
5. The search function works correctly.
6. User registration works correctly.
7. User authentication works correctly.
8. An authenticated user can access their profile.
9. An authenticated user can change their username.
10. An authenticated user can post a comment.
11. An unauthenticated user cannot post a comment.
12. A user can log out.
13. An administrator can access `/admin/`.
14. An administrator can create a news article.
15. An administrator can edit a news article.
16. An administrator can delete a news article.
17. An administrator can upload an image.
18. All required data is stored in MySQL/MariaDB.
19. Passwords are stored as secure hashes.
20. The public and administrative sections follow the MVC architecture.
21. The application can be installed and launched using Apache, PHP, and MySQL/MariaDB.
22. The database can be populated with demonstration data using `seed.php`.
23. Unknown URLs are handled appropriately as 404 pages.

---

# 27. Conclusion

The final system shall be a complete technology news portal named **CyberPulse**, built using PHP and MySQL/MariaDB according to the MVC architecture.

The system shall include:

* a public news portal;
* news categories;
* news search;
* individual news pages;
* user registration;
* user authentication;
* user profiles;
* comments;
* administrator authentication;
* a news management system;
* image management;
* database initialization;
* security mechanisms;
* responsive user interface.

The original project already contained the basic news portal and administrative CRUD functionality. The main modernization task was to transform it into a more complete user-oriented information system by adding public authentication, profiles, improved comments, search, the About page, an improved database structure, automatic database seeding, demonstration content, and a unified CyberPulse design.
