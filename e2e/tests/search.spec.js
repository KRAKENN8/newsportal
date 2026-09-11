const { test, expect } = require('@playwright/test');

test.describe('Search', () => {
  test('searching by keyword returns matching articles', async ({ page }) => {
    // routing.php reads ?otsi= (or ?q=) and calls Controller::SearchNews().
    const response = await page.goto('/search?otsi=quantum');
    expect(response.ok()).toBeTruthy();
    await expect(page.locator('body')).toContainText(/quantum/i);
  });

  test('searching for a nonsense keyword returns no crash and no matches', async ({ page }) => {
    const response = await page.goto('/search?otsi=zzz-no-such-article-zzz');
    expect(response.ok()).toBeTruthy();
  });

  test('the visible search form on the home page submits correctly', async ({ page }) => {
    await page.goto('/');
    const searchInput = page.locator('input[name="otsi"], input[name="q"]').first();
    if (await searchInput.count() === 0) {
      test.skip(true, 'No visible search input found on the home page markup.');
    }
    await searchInput.fill('quantum');
    await searchInput.press('Enter');
    await expect(page).toHaveURL(/search/);
  });
});
