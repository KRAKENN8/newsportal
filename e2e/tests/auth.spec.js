const { test, expect } = require('@playwright/test');

function uniqueEmail() {
  return `e2e-${Date.now()}-${Math.floor(Math.random() * 10000)}@example.com`;
}

test.describe('Registration', () => {
  test('a visitor can register with valid data', async ({ page }) => {
    await page.goto('/registerForm');

    await page.locator('input[name="name"]').fill('Playwright Tester');
    await page.locator('input[name="email"]').fill(uniqueEmail());
    await page.locator('input[name="password"]').fill('Secret123');
    await page.locator('input[name="confirm"]').fill('Secret123');
    await page.locator('button[name="save"], input[name="save"]').first().click();

    // registerAnswer includes view/answerRegister.php with $result[0] === true
    await expect(page.locator('body')).not.toContainText('already exists');
  });

  test('registration is rejected when passwords do not match', async ({ page }) => {
    await page.goto('/registerForm');

    await page.locator('input[name="name"]').fill('Mismatch Tester');
    await page.locator('input[name="email"]').fill(uniqueEmail());
    await page.locator('input[name="password"]').fill('Secret123');
    await page.locator('input[name="confirm"]').fill('Different1');
    await page.locator('button[name="save"], input[name="save"]').first().click();

    await expect(page.locator('body')).toContainText(/do not match/i);
  });

  test('registration is rejected for a duplicate email', async ({ page }) => {
    // admin@cyberpulse.ee ships pre-seeded in cyberpulse.sql
    await page.goto('/registerForm');

    await page.locator('input[name="name"]').fill('Duplicate Tester');
    await page.locator('input[name="email"]').fill('admin@cyberpulse.ee');
    await page.locator('input[name="password"]').fill('Secret123');
    await page.locator('input[name="confirm"]').fill('Secret123');
    await page.locator('button[name="save"], input[name="save"]').first().click();

    await expect(page.locator('body')).toContainText(/already exists/i);
  });
});

test.describe('Login / Logout', () => {
  let email;

  test.beforeAll(async ({ browser }) => {
    // Create a known account once for this whole describe block.
    email = uniqueEmail();
    const page = await browser.newPage();
    await page.goto('/registerForm');
    await page.locator('input[name="name"]').fill('Login Tester');
    await page.locator('input[name="email"]').fill(email);
    await page.locator('input[name="password"]').fill('Secret123');
    await page.locator('input[name="confirm"]').fill('Secret123');
    await page.locator('button[name="save"], input[name="save"]').first().click();
    await page.close();
  });

  test('login fails with an incorrect password', async ({ page }) => {
    await page.goto('/formLogin');
    await page.locator('input[name="name"]').fill(email);
    await page.locator('input[name="password"]').fill('WrongPassword');
    await page.locator('button[name="save"], input[name="save"]').first().click();

    await expect(page.locator('body')).toContainText(/incorrect password/i);
  });

  test('a registered user can log in and reach their profile', async ({ page }) => {
    await page.goto('/formLogin');
    await page.locator('input[name="name"]').fill(email);
    await page.locator('input[name="password"]').fill('Secret123');
    await page.locator('button[name="save"], input[name="save"]').first().click();

    await page.goto('/profile');
    await expect(page.locator('body')).toContainText('Login Tester');
  });

  test('logging out clears the session and profile becomes inaccessible', async ({ page }) => {
    await page.goto('/formLogin');
    await page.locator('input[name="name"]').fill(email);
    await page.locator('input[name="password"]').fill('Secret123');
    await page.locator('button[name="save"], input[name="save"]').first().click();

    await page.goto('/logout');
    await page.goto('/profile');
    // Controller::profile() redirects to formLogin when not authenticated.
    await expect(page).toHaveURL(/formLogin/);
  });
});

test.describe('Access control', () => {
  test('visiting /profile while logged out redirects to the login form', async ({ page, context }) => {
    await context.clearCookies();
    await page.goto('/profile');
    await expect(page).toHaveURL(/formLogin/);
  });
});
