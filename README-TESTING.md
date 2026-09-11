# CyberPulse — Test Suite

Unit + integration tests (PHPUnit) and E2E tests (Playwright) for the CyberPulse project.

## What changed in your existing code (2 small, behavior-preserving fixes)

1. **`inc/Database.php`** — added an optional `static ?PDO $testConnection` hook and an
   optional constructor parameter. If neither is used, `new Database()` behaves **exactly**
   like before (opens the real MySQL connection). Tests use the hook to inject an
   isolated in-memory SQLite database instead — no mocking of PDOStatement needed.
2. **`model/Login.php` and `model/Register.php`** — replaced
   `filter_input(INPUT_POST, 'x', FILTER_VALIDATE_EMAIL)` with
   `filter_var($_POST['x'] ?? '', FILTER_VALIDATE_EMAIL)`.
   `filter_input(INPUT_POST, ...)` **always returns `NULL` when run under the CLI SAPI**
   (a documented PHP behavior, not a bug) — which is exactly how PHPUnit runs. Without this
   fix, every login/registration attempt in a test would hit the "Invalid email address
   format" branch no matter what data was submitted, making the success paths untestable
   and tanking coverage. Behavior with real HTTP POST requests (Apache/php -S) is identical.

If you'd rather not keep fix #2, the success paths of `Login`/`Register` will only be
covered via the Playwright E2E suite instead of PHPUnit.

## Folder layout to drop into your project

```
inc/Database.php              <- replace (adds test hook, same public API)
model/Login.php               <- replace (filter_var fix)
model/Register.php            <- replace (filter_var fix)
composer.json                 <- add (or merge require-dev into your existing one)
phpunit.xml                   <- add
tests/
  bootstrap.php
  Unit/
    CategoryTest.php
    NewsTest.php
    CommentsTest.php
    LoginTest.php
    RegisterTest.php
    ProfileTest.php
  Integration/
    ControllerNewsTest.php
    ControllerAuthTest.php
e2e/
  package.json
  playwright.config.js
  tests/
    navigation.spec.js
    search.spec.js
    auth.spec.js
    comments-and-profile.spec.js
```

## 1. Unit + Integration tests (PHPUnit)

These do **not** need a real MySQL server — every test spins up a fresh, isolated
in-memory SQLite database via `Database::$testConnection` and tears it down afterwards.

```bash
composer install
vendor/bin/phpunit
```

### Coverage report (target: ≥60%)

Coverage needs either the **Xdebug** or **PCOV** PHP extension installed locally
(neither ships with PHP by default):

```bash
# pick one:
pecl install pcov          # faster, coverage-only
# or
pecl install xdebug

vendor/bin/phpunit --coverage-html coverage-html --coverage-text
```

Open `coverage-html/index.html` for the line-by-line breakdown, or read the
`--coverage-text` summary printed to the terminal.

**Known gaps (by design, not oversight):**
- `Controller::InsertComment()`, `Controller::profile()`'s unauthenticated branch, and
  `Controller::logout()` all call `header()` + `exit()`. A single PHPUnit process can't
  survive `exit()`, so these are covered by the **Playwright E2E suite** instead
  (`auth.spec.js`, `comments-and-profile.spec.js`).
- `Controller::Comments()`, `Controller::CommentsCount()`, `Controller::CommentsCountWithAncor()`
  call an undefined `ViewComments` class — they appear to be dead code (nothing in
  `route/routing.php` calls them). They're intentionally left uncovered; delete them if
  confirmed unused, or share `ViewComments` if they're actually live so tests can be added.

## 2. E2E tests (Playwright)

These run against your **real, running app with a real MySQL database** (they exercise
the exact HTTP behavior — redirects, sessions, cookies — that PHPUnit can't touch).

```bash
# 1. Load the real schema + seed data into a MySQL database named `cyberpulse`
mysql -u root cyberpulse < cyberpulse.sql

# 2. Install Playwright
cd e2e
npm install
npx playwright install --with-deps chromium

# 3. Run the app (playwright.config.js does this for you automatically,
#    or start it yourself and set SKIP_WEBSERVER=1)
php -S localhost:8000 -t ..

# 4. Run the tests
npm test

# View the HTML report after a run
npm run report
```

Tests create their own throwaway user accounts (unique email per run), so they're safe
to run repeatedly against the same database without manual cleanup — except that it will
accumulate test users/comments over time in that MySQL database, so **use a dedicated
test database, never production data**.

### If your view markup differs from these selectors

The E2E tests target form fields by their `name` attribute (`name="email"`,
`name="password"`, `name="comment"`, `name="username"`, etc.) because those exact names
are required by the backend (`$_POST['email']`, `$_POST['comment']`, ...) regardless of
your HTML/CSS. If a selector doesn't match your actual view templates, the affected test
will fail (or skip, for the two tests with an explicit `test.skip` fallback) — adjust the
locator to match your markup; the underlying flow being tested doesn't change.

## Why some things are "integration" rather than pure "unit" tests

`model/*.php` files call `new Database()` internally rather than receiving it via
constructor injection, so true isolated unit testing (mocking `PDOStatement` chains)
would require touching every model file. Instead, `Database::$testConnection` swaps in a
real (but in-memory, disposable) SQLite database — each test still gets full isolation
and runs in milliseconds, it just exercises real SQL instead of a mock. This is standard
practice for legacy data-access code and is why `tests/Unit/*` and `tests/Integration/*`
share the same bootstrap helper.
