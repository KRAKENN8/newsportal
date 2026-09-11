// @ts-check
const { defineConfig, devices } = require('@playwright/test');

module.exports = defineConfig({
  testDir: './tests',
  fullyParallel: false, // tests share one MySQL DB and register/login state
  workers: 1,
  retries: 0,
  reporter: [['html', { open: 'never' }], ['list']],

  use: {
    baseURL: process.env.BASE_URL || 'http://localhost:8000',
    trace: 'retain-on-failure',
    screenshot: 'only-on-failure',
  },

  projects: [
    { name: 'chromium', use: { ...devices['Desktop Chrome'] } },
  ],

  // Automatically boots `php -S localhost:8000` for you unless you already
  // have the app running (e.g. under Apache) — see README-TESTING.md.
  webServer: process.env.SKIP_WEBSERVER ? undefined : {
    command: 'php -S localhost:8000 -t ..',
    url: 'http://localhost:8000',
    reuseExistingServer: true,
    timeout: 10_000,
  },
});
