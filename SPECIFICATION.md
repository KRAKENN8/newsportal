# Technical Specification

## Development of a Web-Based News Portal

### 1. General Information

**Project name:** News Portal

**Project type:** Web-based news and information portal.

**Purpose:** Develop a dynamic web application that allows visitors to browse news articles, filter publications by category, read individual articles, register as users, and leave comments. The system shall also provide a separate administrative panel for authorized editors to manage news publications.

The application shall be implemented using PHP, MySQL/MariaDB, Apache, and a custom MVC architecture without using a web framework.

---

# 2. Project Objectives

The main objectives of the project are:

1. Develop a functional online news portal.
2. Provide visitors with access to the latest news.
3. Organize news articles into categories.
4. Allow users to open and read individual news articles.
5. Provide a registration system for new users.
6. Allow visitors to leave comments on news articles.
7. Develop an administrative panel for managing news.
8. Implement CRUD operations for news articles.
9. Store all persistent information in a MySQL/MariaDB database.
10. Structure the application using the MVC architectural pattern.
11. Implement clean URL routing using Apache `mod_rewrite`.

---

# 3. Scope of the System

The system shall consist of two major components:

### 3.1. Public Website

The public section is intended for ordinary visitors and registered users.

It shall provide:

* homepage;
* news listing;
* category filtering;
* individual article pages;
* comments;
* user registration;
* navigation;
* basic error handling.

### 3.2. Administrative Panel

The administrative section is intended for editors/administrators.

It shall provide:

* administrator authentication;
* news listing;
* news creation;
* news editing;
* news deletion;
* category selection;
* image upload;
* administrator logout.

The public and administrative sections shall use the same database but have separate routing, controllers, views, and entry points.

---

# 4. Target Users

## 4.1. Visitor

An unauthenticated visitor shall be able to:

* open the homepage;
* view the latest news;
* view all news;
* filter news by category;
* open individual news articles;
* read comments;
* submit comments;
* open the registration page.

The main branch does **not** require public user login or a public user profile.

## 4.2. Registered User

A registered user shall be able to:

* create an account;
* provide a username;
* provide an email address;
* set a password;
* use the account for the available registration-based functionality.

Public authentication after registration is outside the scope of the `main` version.

## 4.3. Administrator / Editor

An administrator shall be able to:

* authenticate through the administrative panel;
* view existing publications;
* create publications;
* edit publications;
* delete publications;
* select a category;
* upload article images;
* log out.

---

# 5. Homepage

The system shall provide a public homepage.

The homepage shall display the latest three news articles in a dedicated **TOP 3 NEWS** section.

Each article preview shall provide enough information for the visitor to identify the publication and open its full version.

The homepage shall also contain the main navigation and category navigation.

The implemented main version explicitly defines the homepage as displaying the latest three articles.

---

# 6. All News Page

The system shall provide a page containing all available news publications.

The visitor shall be able to browse the complete collection of articles.

**Route:**

`/all`

Each publication shall provide a link to its individual article page.

---

# 7. News Categories

The news portal shall organize publications into categories.

The system shall:

* store categories in the database;
* display categories in the website navigation;
* associate news articles with categories;
* allow visitors to filter articles by category.

Example categories may include:

* Sport;
* Politics;
* Culture;
* Technology.

The original database initialization instructions define these categories as example data.

**Route:**

`/category?id=<ID>`

---

# 8. Individual News Article

The system shall provide a dedicated page for each publication.

The article page shall display:

* article title;
* article image;
* article text;
* category;
* comments;
* comment submission form.

**Route:**

`/news?id=<ID>`

The article image shall be retrieved from the database and displayed as part of the publication.

---

# 9. Comments

Visitors shall be able to submit comments on news articles.

A comment shall contain:

* comment text;
* associated news article;
* date/time of submission.

The comment shall be stored in the `comments` table.

The `main` version does not require a relationship between a comment and a registered user. Comments are associated with news articles only.

### Important implementation requirement

The comment functionality shall be implemented as part of the public news page.

The original route is:

`/insertcomment?comment=&id=`

The `id` parameter identifies the news article.

---

# 10. User Registration

The system shall provide a registration form for new users.

The form shall contain:

* username;
* email;
* password.

The system shall validate the submitted information before creating the account.

The email address shall be unique.

The password shall contain at least six characters.

The password shall be stored using a secure password hash.

**Routes:**

`/registerForm`

`/registerAnswer`

The registration functionality is part of the public website, but public login is intentionally outside the scope of this version.

---

# 11. Public Authentication Scope

The `main` version shall **not** implement a public login system.

The following features are explicitly outside the scope of this version:

* public login;
* public logout;
* public user profile;
* username editing;
* user account management.

These features may be added in a future version of the application.

---

# 12. Search

A search field shall be displayed in the website header/navigation.

However, the initial version shall not implement a functional search-results system.

The search interface is therefore considered a reserved feature for future development.

The intended future functionality is:

1. User enters a search query.
2. System searches news articles.
3. Matching articles are displayed.
4. If no results exist, an appropriate message is shown.

The current `main` version does not provide a working `search` route.

---

# 13. Information Page

The navigation may contain an **Info** menu item.

The initial version shall not provide a dedicated information page.

The existing `Info` action leads to the application's 404/error view.

A future version may implement a proper About/Information page.

---

# 14. Administrative Panel

The application shall provide a separate administrative panel.

**Base route:**

`/admin/`

The administrative panel shall be implemented independently from the public routing system.

It shall contain:

* administrative authentication;
* dashboard/news list;
* news creation;
* news editing;
* news deletion;
* category selection;
* image management;
* logout functionality.

The administrative panel shall have its own:

* front controller;
* router;
* controllers;
* models;
* views;
* CSS/JavaScript resources.

---

# 15. Administrator Authentication

The administrative panel shall require authentication.

When an administrator accesses:

`/admin/`

the system shall display the login form if no valid administrator session exists.

The administrator shall provide:

* email;
* password.

After successful authentication, an administrator session shall be created.

The system shall support administrator logout.

**Routes:**

`/admin/`

`/admin/login`

`/admin/logout`

PHP sessions shall be used to maintain the administrator's authenticated state.

Password verification shall use secure password hashing functions such as:

`password_hash()`

and

`password_verify()`.

---

# 16. News Management

The administrator shall have CRUD functionality for news publications.

CRUD consists of:

* Create;
* Read;
* Update;
* Delete.

## 16.1. News List

The administrator shall be able to view existing publications.

The list shall contain:

* title;
* category;
* author;
* available management actions.

**Route:**

`/admin/newsAdmin`

---

## 16.2. Create News

The administrator shall be able to create a new article.

The form shall contain:

* title;
* article text;
* category;
* image.

**Routes:**

`/admin/newsAdd`

`/admin/newsAddResult`

The new article shall be stored in the database.

---

## 16.3. Edit News

The administrator shall be able to modify an existing article.

The editable fields shall include:

* title;
* article text;
* category;
* image.

The image shall be optional when editing an existing article.

**Routes:**

`/admin/newsEdit?id=<ID>`

`/admin/newsEditResult?id=<ID>`

---

## 16.4. Delete News

The administrator shall be able to delete an existing article.

The system shall display a confirmation page before performing the deletion.

**Routes:**

`/admin/newsDel?id=<ID>`

`/admin/newsDelResult?id=<ID>`

---

# 17. Image Management

News articles shall support image attachments.

Images shall be stored directly in MySQL as binary data.

The recommended database type is:

`MEDIUMBLOB`

The administrator shall be able to upload an image when creating a publication.

When editing a publication, the administrator may optionally replace the existing image.

The public article page shall display the stored image.

---

# 18. Database Requirements

The application shall use:

* MySQL;
* or MariaDB.

The database shall be named:

`newsportal`

The application shall access the database through PDO.

---

# 19. Database Structure

The main version shall contain four primary tables.

## 19.1. users

Stores registered users and administrator accounts.

Fields:

* `id`;
* `username`;
* `email`;
* `password`;
* `status`;
* `registration_date`;
* `pass`.

The `status` field identifies the account type:

* `user`;
* `admin`.

The `email` field shall be unique.

The `password` field shall contain the hashed password.

The `pass` field is a legacy field present in the original database structure and shall not be required for authentication.

---

## 19.2. category

Stores news categories.

Fields:

* `id`;
* `name`.

Each category shall have a unique identifier.

---

## 19.3. news

Stores news publications.

Fields:

* `id`;
* `title`;
* `text`;
* `picture`;
* `category_id`;
* `user_id`.

Relationships:

`news.category_id → category.id`

`news.user_id → users.id`

The article image shall be stored as binary data.

---

## 19.4. comments

Stores comments associated with news articles.

Fields:

* `id`;
* `news_id`;
* `text`;
* `date`.

Relationship:

`comments.news_id → news.id`

The original version does not require:

`comments.user_id`

Therefore comments are not linked to individual registered users in the `main` version.

---

# 20. Database Relationships

The database shall provide the following logical relationships:

```text
users
  │
  └──────< news
              │
              ├──────> category
              │
              └──────< comments
```

A user may be associated with multiple news publications.

A category may contain multiple news publications.

A news article may have multiple comments.

---

# 21. MVC Architecture

The application shall follow a custom MVC architecture.

No external PHP framework shall be used.

## Model

The model layer shall:

* communicate with the database;
* execute SQL queries;
* retrieve news;
* retrieve categories;
* retrieve comments;
* create users;
* perform administrative CRUD operations.

Models shall not contain HTML markup.

---

## Controller

The controller layer shall:

* receive application requests;
* call appropriate models;
* prepare data;
* load the required views;
* return the generated response.

The main public controller shall be:

`controller/Controller.php`

---

## View

The view layer shall contain:

* HTML markup;
* page layouts;
* news presentation;
* category menus;
* registration forms;
* comments;
* error pages.

The main shared layout shall provide:

* header;
* navigation;
* footer;
* page content.

---

## Router

The router shall map URLs to controller actions.

Public routing shall be handled by:

`route/routing.php`

Administrative routing shall be handled by:

`admin/routeAdmin/routingAdmin.php`

---

# 22. Request Flow

The public request flow shall be:

```text
Browser
   ↓
Apache
   ↓
.htaccess
   ↓
index.php
   ↓
routing.php
   ↓
Controller
   ↓
Model
   ↓
Database
   ↓
View
   ↓
HTML Response
```

The administrative section shall use the same general architecture with its own entry point and routing system.

---

# 23. Database Connection

Database access shall be centralized in:

`inc/Database.php`

The class shall provide a PDO connection to the MySQL/MariaDB database.

The connection shall contain configuration values for:

* host;
* username;
* password;
* database name.

The default configuration shall use:

| Setting  | Value        |
| -------- | ------------ |
| Host     | `localhost`  |
| User     | `root`       |
| Password | empty        |
| Database | `newsportal` |

The application shall use PDO methods for database operations.

---

# 24. Security Requirements

The application shall implement basic security measures.

### Password Security

Passwords shall not be stored in plain text for authentication.

The application shall use:

* `password_hash()`;
* `password_verify()`.

### Session Security

Administrator authentication shall use PHP sessions.

### Database Security

Database access shall be performed through PDO.

Prepared statements should be used for user-controlled input.

### Input Validation

The application shall validate:

* registration fields;
* news identifiers;
* category identifiers;
* comment content;
* uploaded images.

### Output Handling

User-generated content should be escaped before being rendered as HTML.

---

# 25. URL Routing

The public application shall support the following routes:

| Route                         | Function                      |
| ----------------------------- | ----------------------------- |
| `/`                           | Homepage                      |
| `/all`                        | Display all news              |
| `/category?id=<ID>`           | Display news from a category  |
| `/news?id=<ID>`               | Display an individual article |
| `/insertcomment?comment=&id=` | Add a comment                 |
| `/registerForm`               | Registration form             |
| `/registerAnswer`             | Process registration          |

Unknown paths shall result in a 404 error page.

---

# 26. Administrative Routes

The administrative section shall support:

| Route                       | Function                    |
| --------------------------- | --------------------------- |
| `/admin/`                   | Login page / dashboard      |
| `/admin/login`              | Process administrator login |
| `/admin/logout`             | Administrator logout        |
| `/admin/newsAdmin`          | News list                   |
| `/admin/newsAdd`            | News creation form          |
| `/admin/newsAddResult`      | Save new article            |
| `/admin/newsEdit?id=`       | Edit article form           |
| `/admin/newsEditResult?id=` | Save article changes        |
| `/admin/newsDel?id=`        | Delete confirmation         |
| `/admin/newsDelResult?id=`  | Delete article              |

These routes constitute the main administrative functionality of the project.

---

# 27. User Interface

The public interface shall provide a simple news-oriented design.

The interface shall include:

* website header;
* navigation menu;
* category dropdown;
* homepage news section;
* news cards/list;
* article page;
* comments section;
* registration form;
* footer.

The navigation shall contain options for:

* Home;
* Categories;
* Registration;
* Search.

The public interface of the original project is primarily in Estonian, with labels such as:

* `Stardileht`;
* `Kategooriad`;
* `Otsi`.

The UI shall use Bootstrap 4 together with custom CSS, Font Awesome, and Google Fonts.

---

# 28. Responsive Design

The website shall be usable on common desktop and mobile screen sizes.

Bootstrap shall be used to simplify responsive layout implementation.

The design shall maintain:

* readable typography;
* accessible navigation;
* appropriately sized images;
* usable forms;
* clear separation between articles and comments.

---

# 29. Technology Requirements

The application shall use the following technology stack:

| Layer                | Technology                              |
| -------------------- | --------------------------------------- |
| Programming language | PHP 7.4+ / PHP 8.x                      |
| Architecture         | Custom MVC                              |
| Database             | MySQL / MariaDB                         |
| Database access      | PDO                                     |
| Web server           | Apache                                  |
| Local environment    | XAMPP                                   |
| Routing              | `.htaccess` + PHP router                |
| Authentication       | PHP Sessions                            |
| Password security    | `password_hash()` / `password_verify()` |
| Frontend             | HTML, CSS, Bootstrap 4                  |
| Icons                | Font Awesome                            |
| Fonts                | Google Fonts                            |

The project shall not require Composer, Node.js, or npm. PHP files shall be served directly by Apache.

---

# 30. Environment Requirements

The system shall be compatible with:

* Windows;
* macOS;
* Linux.

Required software:

* Apache;
* PHP 7.4 or newer;
* MySQL or MariaDB.

Required PHP extensions:

* `pdo_mysql`;
* `mbstring`;
* `session`.

Required Apache functionality:

* `mod_rewrite`.

The Apache configuration shall allow `.htaccess` overrides using:

`AllowOverride All`

---

# 31. Installation Requirements

The project shall be placed inside the Apache web root.

For a standard XAMPP installation on Windows:

```text
C:\xampp\htdocs\newsportal
```

The user shall then:

1. Start Apache.
2. Start MySQL.
3. Create a database named `newsportal`.
4. Create the required database tables.
5. Insert at least one category.
6. Create a user through the registration page.
7. Promote the user to administrator if administrative functionality is required.
8. Open the public website.

Public URL:

```text
http://localhost/newsportal/
```

Administrative URL:

```text
http://localhost/newsportal/admin/
```

The original branch does not contain a database dump, so the database must be created manually.

---

# 32. Initial Database Data

The system shall provide initial categories for testing.

Example:

```text
Sport
Politics
Culture
Technology
```

At least one category shall exist before news articles are created.

The system shall not require a bundled demonstration administrator account.

An administrator can be created by:

1. registering a user;
2. changing the user's `status` field to `admin` in the database.

The original project documentation specifies this initialization procedure.

---

# 33. Project Structure

The expected project structure shall be:

```text
newsportal/
├── index.php
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
│   └── Register.php
│
├── view/
│   ├── layout.php
│   ├── start.php
│   ├── allnews.php
│   ├── catnews.php
│   ├── readnews.php
│   ├── news.php
│   ├── comments.php
│   ├── category.php
│   ├── formRegister.php
│   ├── answerRegister.php
│   └── error404.php
│
├── public/
│   └── css/
│
└── admin/
    ├── index.php
    ├── .htaccess
    │
    ├── routeAdmin/
    │   └── routingAdmin.php
    │
    ├── controllerAdmin/
    │
    ├── modelAdmin/
    │   ├── modelAdmin.php
    │   ├── modelAdminNews.php
    │   └── modelAdminCategory.php
    │
    ├── viewAdmin/
    │
    └── public/
```

This structure corresponds to the actual organization of the `main` branch.

---

# 34. Functional Requirements Summary

The completed system shall provide the following functionality:

### Public Area

* [x] Homepage;
* [x] Latest three news articles;
* [x] Complete news list;
* [x] Category filtering;
* [x] Individual news page;
* [x] News images;
* [x] Comments;
* [x] User registration;
* [x] 404 error page;
* [x] Category navigation.

### Administrative Area

* [x] Administrator login;
* [x] Administrator logout;
* [x] News list;
* [x] Create news;
* [x] Edit news;
* [x] Delete news;
* [x] Category selection;
* [x] Image upload.

### Not Included in the Main Version

* [ ] Public login;
* [ ] Public logout;
* [ ] User profile;
* [ ] User profile editing;
* [ ] Working search;
* [ ] Search results page;
* [ ] About/Info page;
* [ ] User-to-comment relationship;
* [ ] Automatic database seeding;
* [ ] SQL database dump.

These limitations are explicitly documented in the repository's `main` branch.

---

# 35. Acceptance Criteria

The project shall be considered successfully completed if:

1. The homepage can be opened without authentication.
2. The homepage displays the latest three news articles.
3. The visitor can open the complete news list.
4. The visitor can filter news by category.
5. The visitor can open an individual news article.
6. Article images are displayed correctly.
7. The visitor can submit a comment.
8. Comments are stored in the database.
9. A visitor can register a new account.
10. The registration form validates the required fields.
11. Passwords are securely hashed.
12. An administrator can access `/admin/`.
13. An administrator can log in.
14. An administrator can view existing news.
15. An administrator can create a news article.
16. An administrator can edit a news article.
17. An administrator can delete a news article.
18. An administrator can assign a category to a publication.
19. An administrator can upload an article image.
20. An administrator can log out.
21. The application uses the MVC architecture.
22. The application uses PDO for database access.
23. The application runs under Apache with `mod_rewrite`.
24. Unknown public URLs display a 404 page.
25. The project can be installed using PHP, Apache, and MySQL/MariaDB.

---

# 36. Limitations and Future Development

The initial version shall intentionally remain relatively simple.

The following functionality may be implemented in future versions:

### User System

* public login;
* logout;
* user profile;
* profile editing;
* user-specific comments;
* role-based access control.

### Search

* working search form;
* full-text search;
* search results page;
* search filters.

### Content

* About/Info page;
* pagination;
* article sorting;
* article tags;
* featured articles.

### Administration

* user management;
* category management;
* comment moderation;
* dashboard statistics;
* image validation;
* configurable site settings.

### Database

* SQL dump;
* automatic migrations;
* improved foreign-key relationships;
* removal of legacy fields;
* `utf8mb4` as the standard character set.

---

# 37. Final Result

The final product shall be a functional student news portal implemented with PHP and MySQL/MariaDB.

The system shall allow visitors to browse and read news, filter publications by category, submit comments, and register an account. Administrators shall have a separate authenticated interface for creating, editing, and deleting publications.

The application shall follow a custom MVC architecture and use Apache URL rewriting, PDO database access, PHP sessions for administrative authentication, Bootstrap for the frontend, and MySQL/MariaDB for persistent data storage.

The `main` branch represents the **initial/original version of the project**. Its scope is intentionally smaller than the later `remade` version: public authentication, profiles, working search, and the information page are not part of the implemented requirements.
