// tests/case-reports.spec.js
import { test, expect } from '@playwright/test';

test.describe('Case Management and Reports', () => {
  
  test.beforeEach(async ({ page }) => {
    await page.goto('/login');
    await page.waitForTimeout(2000);
    
    await page.fill('.form-input-modern[name="username"]', 'yazan');
    await page.fill('.form-input-modern[name="password"]', '1');
    await page.click('.login-button');
    await page.waitForTimeout(3000);
  });

  test('create cases and verify report numbers', async ({ page }) => {
    const fromDate = '2025-10-01';
    const toDate = '2025-10-31';
    let totalUnitsCreated = 0;
    
    for (let i = 0; i < 3; i++) {
      await page.goto('/new-case');
      await page.waitForTimeout(3000);
      
      await page.click('button.dropdown-toggle.btn-light');
      await page.waitForTimeout(500);
      await page.click('ul.dropdown-menu.show li:nth-child(2)');
      await page.waitForTimeout(500);
      
      await page.fill('input[name="patient_name"]', `Test Patient ${Date.now()}_${i}`);
      await page.fill('input[name="caseId4"]', String(Date.now()).slice(-4));
      await page.selectOption('select[name="impression_type"]', { index: 0 });
      
      await page.click('button.slctUnitsBtn');
      await page.waitForTimeout(1000);
      await page.click('img.teeth[alt="11"]');
      await page.waitForTimeout(300);
      await page.click('img.teeth[alt="12"]');
      await page.waitForTimeout(300);
      await page.click('#submitDialog');
      await page.waitForTimeout(2000);
      
      const jobTypeSelect = page.locator('select[id="jobType"]').first();
      await jobTypeSelect.waitFor({ state: 'visible', timeout: 3000 });
      await jobTypeSelect.selectOption({ index: 1 });
      await page.waitForTimeout(500);
      
      const materialSelect = page.locator('select[id="material_id"]').first();
      await materialSelect.waitFor({ state: 'visible', timeout: 3000 });
      await materialSelect.selectOption({ index: 1 });
      await page.waitForTimeout(500);
      
      totalUnitsCreated += 2;
      
      console.log(`Creating case ${i + 1} with 2 units`);
      
      // Submit and wait for navigation to complete
      await page.click('button[type="submit"]');
await page.waitForTimeout(2000);
    }
    
    console.log('Total units created:', totalUnitsCreated);
    
    await page.goto('/reports/num-of-units');
    await page.waitForTimeout(2000);
    
    await page.fill('input[name="from"]', fromDate);
    await page.fill('input[name="to"]', toDate);
    
    await page.click('button[type="submit"]:has-text("Generate Report")');
await page.waitForTimeout(2000);
    
    const reportTotal = await page.locator('tr.totals-row td strong').last().textContent();
    const reportTotalNum = parseInt(reportTotal.trim());
    
    console.log(`Report shows: ${reportTotalNum}`);
    console.log(`Expected at least: ${totalUnitsCreated}`);
    
    expect(reportTotalNum).toBeGreaterThanOrEqual(totalUnitsCreated);
    
    console.log('✅ Test passed');
  });
});