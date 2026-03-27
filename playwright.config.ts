import { defineConfig, devices } from '@playwright/test';
import fs from 'node:fs';

const chromiumExecutable = [
  process.env.PLAYWRIGHT_CHROMIUM_EXECUTABLE,
  '/snap/bin/chromium',
  '/usr/bin/google-chrome',
  '/usr/bin/google-chrome-stable',
].find((candidate) => candidate && fs.existsSync(candidate));

export default defineConfig({
  testDir: './e2e',
  timeout: 30_000,
  use: {
    baseURL: 'http://127.0.0.1:9080',
    headless: true,
    trace: 'retain-on-failure',
    launchOptions: {
      executablePath: chromiumExecutable,
      args: ['--no-sandbox'],
    },
  },
  projects: [
    {
      name: 'chromium',
      use: {
        ...devices['Desktop Chrome'],
        browserName: 'chromium',
      },
    },
  ],
  webServer: {
    command: 'php -S 127.0.0.1:9080 router.php',
    url: 'http://127.0.0.1:9080/locator/status',
    reuseExistingServer: true,
    timeout: 30_000,
  },
});
