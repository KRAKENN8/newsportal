# CyberPulse — Technical Audit & System Improvements

This document outlines **3 major shortcomings** identified across the CyberPulse codebase (encompassing database architecture/performance, security/authorization, and user interface/mobile responsiveness) along with the concrete technical improvements implemented to resolve them.

---

## Shortcoming 1: Data Layer Architecture & N+1 Query Inefficiency

### Problem Description
1. **Lack of Parameter Binding Support in `Database.php`**: The core data access wrapper (`Database.php`) only offered raw query execution methods (`getOne($query)`, `getAll($query)`, `executeRun($query)`) with zero parameter binding (`$params = []`). This forced model methods to either interpolate variables directly into SQL queries or bypass the wrapper entirely to manually invoke PDO preparation.
2. **Connection Churn**: Every instantiation via `new Database()` created an entirely new PDO connection rather than maintaining a shared connection instance across the request lifecycle.
3. **Severe N+1 Query Problem on News Listings**: On the homepage, category pages, and search results, rendering the news feed via `ViewNews::NewsByCategory()` triggered a separate SQL query (`SELECT COUNT(id) as count FROM details WHERE news_id = :news_id`) for every single news card through `Controller::CommentsCount()`. For a page with 10–20 articles, this resulted in 10–20 redundant roundtrips to the database.

### Improvement Plan
1. **Modernize `Database.php`**:
   - Add native parameter binding to `getOne($query, $params = [])`, `getAll($query, $params = [])`, and `executeRun($query, $params = [])` using PDO prepared statements.
   - Reuse the active connection instance to eliminate redundant connection handshakes during request handling, while fully preserving compatibility with the test suite hook (`$testConnection`).
2. **Batch Comment Counts with Single-Query Aggregation**:
   - Update `News::getAllNews()`, `News::getLast10News()`, `News::getNewsByCategoryID()`, and `News::searchNews()` to compute the comment count using a subquery `LEFT JOIN (SELECT news_id, COUNT(id) AS comments_count FROM details GROUP BY news_id) AS c ON items.id = c.news_id`.
   - Consume the pre-aggregated `comments_count` directly in `ViewNews::NewsByCategory()`, reducing database queries on listing pages from O(N) down to a single efficient query.

---

## Shortcoming 2: Security Vulnerabilities & Admin Access Control Bypass

### Problem Description
1. **Critical Authorization Bypass in Admin News Controller**: `admin/controllerAdmin/controllerAdminNews.php` completely lacked authentication and access checks across all actions (`NewsList`, `newsAddForm`, `newsAddResult`, `newsEditForm`, `newsEditResult`, `newsDeleteForm`, `newsDeleteResult`). Any unauthenticated external visitor could invoke `/admin/newsDelResult?id=X` and delete or modify news and comments.
2. **Privilege Escalation in Admin Authentication**: In `admin/modelAdmin/modelAdmin.php`, `userAuthentication()` validated credentials but never verified if `$item['status'] === 'admin'`. Any regular registered member (`status = 'user'`) was granted full access to the admin dashboard.
3. **SQL Injection**: In `admin/modelAdmin/modelAdmin.php`, the email parameter was directly concatenated into the SQL statement (`"SELECT * FROM users WHERE email = '".$email."'"`).
4. **Plaintext Password Storage**: In `model/Register.php`, user registration explicitly saved plaintext passwords into the database column `pass` (`':pass' => $password`), violating basic cryptographic security practices.

### Improvement Plan
1. **Enforce Role-Based Access Control in the Admin Area**:
   - Add a centralized access guard in `controllerAdmin` and `controllerAdminNews` that strictly validates `isset($_SESSION['userId']) && isset($_SESSION['status']) && $_SESSION['status'] === 'admin'`.
   - Automatically redirect unauthorized or unauthenticated visitors to the login screen with an access-denied flash alert.
2. **Patch SQL Injection & Verify Admin Status**:
   - Refactor `admin/modelAdmin/modelAdmin.php` to execute parameterized queries.
   - Enforce that user status must strictly equal `'admin'` for an admin session to be granted.
3. **Eliminate Plaintext Password Storage**:
   - Update `model/Register.php` to prevent storing unhashed passwords in the database.
4. **Add CSRF Protection**:
   - Generate and validate unique session-based CSRF tokens for administrative actions (deleting and editing articles) and comment submission.

---

## Shortcoming 3: Broken Mobile Navigation & Missing Interactive UX Feedback

### Problem Description
1. **Vanishing Navigation on Mobile Devices**: In `style.css`, screen widths below 768px applied `@media (max-width: 768px) { .cp-nav-links { display: none; } }`. However, `view/layout.php` contained no mobile hamburger toggle button or mobile navigation drawer. On smartphones and tablets, visitors were completely unable to browse categories, access "All News", or view the "About" page.
2. **Absence of Interactive Feedback (Toast / Flash Notifications)**: Actions such as posting a comment, updating profile details, or logging in had no animated notifications or flash feedback, causing confusion on redirect.
3. **Lack of Modern Reading and Navigation Conveniences**:
   - No "Back to Top" smooth scroll button for long-form tech articles.
   - No quick "Copy Article Link" button with instant clipboard feedback.
   - No visual active-page indicator in the primary navigation.

### Improvement Plan
1. **Implement an Animated Mobile Drawer & Hamburger Menu**:
   - Add a responsive hamburger toggle icon to `view/layout.php`.
   - Build a modern off-canvas mobile drawer with smooth CSS transitions, category dropdowns, and quick auth buttons.
2. **Build a Flash / Toast Notification System**:
   - Implement session-backed Flash messaging (`$_SESSION['flash']`) in `Controller.php` and `view/layout.php`.
   - Display auto-dismissing, animated toast notifications for successful comments, profile changes, and authentication states.
3. **Enhance Reader Convenience**:
   - Add a floating "Back to Top" button that smoothly returns the user to the top of long articles.
   - Add a "Share / Copy Link" button on full article pages (`view/news.php`) with instant clipboard copy and tooltip feedback.
   - Add active link state indicators for current routes in the navbar.
