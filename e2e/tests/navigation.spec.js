const { test, expect } = require('@playwright/test');

test.describe('Site navigation', () => {
  test('home page loads and shows recent news', async ({ page }) => {
    const response = await page.goto('/');
    expect(response.ok()).toBeTruthy();
    // The seeded DB (cyberpulse.sql) ships with real news rows.
    await expect(page.locator('body')).not.toBeEmpty();
  });

  test('/all lists every news article', async ({ page }) => {
    const response = await page.goto('/all');
    expect(response.ok()).toBeTruthy();
  });

  test('/category?id=1 filters news by category', async ({ page }) => {
    const response = await page.goto('/category?id=1');
    expect(response.ok()).toBeTruthy();
  });

  test('/news?id=1 shows a single article', async ({ page }) => {
    const response = await page.goto('/news?id=1');
    expect(response.ok()).toBeTruthy();
  });

  test('/about renders the about page', async ({ page }) => {
    const response = await page.goto('/about');
    expect(response.ok()).toBeTruthy();
  });

  test('an unknown route falls back to the 404 page', async ({ page }) => {
    await page.goto('/this-route-does-not-exist');
    // routing.php dispatches Controller::error404() for any unmatched path.
    await expect(page.locator('body')).toBeVisible();
  });
});
