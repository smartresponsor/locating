import { expect, test } from '@playwright/test';

test('root endpoint renders the locator JSON contract in Chromium', async ({ page }) => {
  await page.goto('/');
  await expect(page.locator('body')).toContainText('"status":"ok"');
  await expect(page.locator('body')).toContainText('"component":"locator-sketch30"');
});

test('status and suggest endpoints respond through the local web server', async ({ request }) => {
  const statusResponse = await request.get('/locator/status');
  expect(statusResponse.ok()).toBeTruthy();
  await expect
    .poll(async () => (await statusResponse.json()).status)
    .toBe('ok');

  const suggestResponse = await request.get('/locator/address/suggest?query=1600+Pennsylvania+Ave&country=US&limit=1');
  expect(suggestResponse.ok()).toBeTruthy();

  const suggestPayload = await suggestResponse.json();
  expect(suggestPayload.items).toHaveLength(1);
  expect(suggestPayload.items[0].providerKey).toBe('local-fixture');
});
