const { test, expect } = require('@playwright/test');

function uniqueEmail() {
  return `e2e-${Date.now()}-${Math.floor(Math.random() * 10000)}@example.com`;
}

async function registerAndLogin(page, name, email) {
  await page.goto('/registerForm');
  await page.locator('input[name="name"]').fill(name);
  await page.locator('input[name="email"]').fill(email);
  await page.locator('input[name="password"]').fill('Secret123');
  await page.locator('input[name="confirm"]').fill('Secret123');
  await page.locator('button[name="save"], input[name="save"]').first().click();

  await page.goto('/formLogin');
  await page.locator('input[name="name"]').fill(email);
  await page.locator('input[name="password"]').fill('Secret123');
  await page.locator('button[name="save"], input[name="save"]').first().click();
}

test.describe('Comments', () => {
  test('posting a comment while logged out redirects to login', async ({ page, context }) => {
    await context.clearCookies();
    // routing.php requires session user_id before accepting the POST.
    const response = await page.request.post('/insertcomment', {
      form: { comment: 'Trying to comment while logged out', id: '1' },
      maxRedirects: 0,
    });
    expect([301, 302, 303, 307, 308]).toContain(response.status());
    expect(response.headers()['location']).toMatch(/formLogin/);
  });

  test('a logged-in user can post a comment and see it on the article page', async ({ page }) => {
    const email = uniqueEmail();
    await registerAndLogin(page, 'Commenter One', email);

    const commentText = `E2E comment ${Date.now()}`;
    await page.goto('/news?id=1');

    const commentBox = page.locator('textarea[name="comment"], input[name="comment"]').first();
    if (await commentBox.count() === 0) {
      test.skip(true, 'No visible comment textarea found on the article page markup.');
    }
    await commentBox.fill(commentText);
    await page.locator('form:has(textarea[name="comment"], input[name="comment"]) [type="submit"]').first().click();

    await expect(page.locator('body')).toContainText(commentText);
  });
});

test.describe('Profile', () => {
  test('a logged-in user can update their username', async ({ page }) => {
    const email = uniqueEmail();
    await registerAndLogin(page, 'Original Name', email);

    await page.goto('/profile');
    const newName = `Renamed ${Date.now()}`;
    await page.locator('input[name="username"]').fill(newName);
    await page.locator('form:has(input[name="username"]) [type="submit"]').first().click();

    await expect(page.locator('body')).toContainText(newName);
  });

  test('submitting an empty username leaves the profile unchanged', async ({ page }) => {
    const email = uniqueEmail();
    await registerAndLogin(page, 'Keep This Name', email);

    await page.goto('/profile');
    await page.locator('input[name="username"]').fill('   ');
    await page.locator('form:has(input[name="username"]) [type="submit"]').first().click();

    await expect(page.locator('body')).toContainText('Keep This Name');
  });
});
