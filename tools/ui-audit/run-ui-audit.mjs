import { chromium } from 'playwright';
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';

const baseUrl = (process.env.UI_AUDIT_BASE_URL || '').replace(/\/$/, '');
const username = process.env.UI_AUDIT_USER || 'yazan';
const password = process.env.UI_AUDIT_PASSWORD || '1';
const headed = process.env.UI_AUDIT_HEADED === '1';
const audits = JSON.parse(process.env.UI_AUDIT_TARGETS || '{}');

function findChromiumExecutable() {
  const root = process.env.PLAYWRIGHT_BROWSERS_PATH
    || path.join(process.env.LOCALAPPDATA || path.join(os.homedir(), 'AppData', 'Local'), 'ms-playwright');

  if (!fs.existsSync(root)) {
    return null;
  }

  return fs.readdirSync(root)
    .filter(name => /^chromium-\d+$/.test(name))
    .sort((a, b) => Number(b.replace('chromium-', '')) - Number(a.replace('chromium-', '')))
    .map(name => path.join(root, name, 'chrome-win', 'chrome.exe'))
    .find(file => fs.existsSync(file)) || null;
}

function pageUrl(path) {
  return `${baseUrl}${path.startsWith('/') ? path : `/${path}`}`;
}

async function login(page) {
  await page.goto(pageUrl('/login'), { waitUntil: 'domcontentloaded', timeout: 30000 });

  const userInput = page.locator('input[name="email"], input[name="username"]').first();
  await userInput.waitFor({ state: 'visible', timeout: 10000 });
  await userInput.fill(username);
  await page.locator('input[name="password"]').first().fill(password);

  await Promise.all([
    page.waitForLoadState('networkidle', { timeout: 30000 }).catch(() => {}),
    page.locator('button[type="submit"], input[type="submit"]').first().click(),
  ]);

  if (/\/login(?:$|\?)/.test(new URL(page.url()).pathname)) {
    throw new Error('Login did not leave the login page.');
  }
}

async function checkRequiredControls(page, controls) {
  const missing = [];

  for (const control of controls) {
    const count = await page.locator(control.selector).count();
    if (count === 0) {
      missing.push(control.name);
    }
  }

  return missing;
}

async function fillCreateCaseBasics(page) {
  const doctor = page.locator('[name="doctor"]').first();
  if (await doctor.count()) {
    const options = await doctor.locator('option[value]:not([value=""])').count();
    if (options > 0) {
      await doctor.selectOption({ index: 0 }).catch(() => {});
    }
  }

  await page.locator('[name="patient_name"]').first().fill('UI Audit Dummy');
  await page.locator('[name="caseId4"]').first().fill('9999');

  const impression = page.locator('[name="impression_type"]').first();
  if (await impression.count()) {
    await impression.selectOption({ index: 0 }).catch(() => {});
  }
}

async function exerciseCreateCaseInteractions(page) {
  await fillCreateCaseBasics(page);

  const unitsButton = page.locator('.slctUnitsBtn').first();
  await unitsButton.click();
  await page.locator('#unitsDialog').waitFor({ state: 'visible', timeout: 10000 });

  const tooth = page.locator('#unitsDialog .teeth[alt="11"]').first();
  if (await tooth.count()) {
    await tooth.dispatchEvent('click');
  }

  await page.locator('#unitsDialog #submitDialog').first().click();
  await page.waitForTimeout(500);

  const selectedUnits = await page.locator('.hiddenUnitsInput').first().inputValue().catch(() => '');
  if (!selectedUnits) {
    throw new Error('Units dialog did not update the units field.');
  }

  const addJob = page.locator('#addJobBtn').first();
  if (await addJob.count()) {
    await addJob.click();
    await page.waitForTimeout(300);
  }
}

async function runOne(browser, auditKey, audit, viewportName, viewport) {
  const context = await browser.newContext({ viewport });
  const page = await context.newPage();
  const startedAt = Date.now();
  const errors = [];
  const warnings = [];

  page.on('pageerror', error => {
    errors.push(`Page error: ${error.message}`);
  });

  page.on('console', message => {
    if (message.type() === 'error') {
      errors.push(`Console error: ${message.text()}`);
    }
  });

  page.on('requestfailed', request => {
    const type = request.resourceType();
    const failure = request.failure();
    const message = `${type} failed: ${request.url()} ${failure?.errorText || ''}`.trim();

    if (['document', 'script', 'xhr', 'fetch'].includes(type)) {
      errors.push(message);
    } else {
      warnings.push(message);
    }
  });

  try {
    await login(page);
    const response = await page.goto(pageUrl(audit.path), { waitUntil: 'networkidle', timeout: 30000 });
    const status = response?.status() || null;

    if (!status || status >= 400) {
      throw new Error(`Page returned status ${status || 'unknown'}.`);
    }

    const missing = await checkRequiredControls(page, audit.required_controls || []);
    if (missing.length) {
      throw new Error(`Missing controls: ${missing.join(', ')}`);
    }

    if (auditKey === 'create-case') {
      await exerciseCreateCaseInteractions(page);
    }

    if (errors.length) {
      throw new Error(errors[0]);
    }

    return {
      audit: auditKey,
      viewport: viewportName,
      path: audit.path,
      status,
      state: 'ok',
      ms: Date.now() - startedAt,
      checks: (audit.required_controls || []).length,
      warnings,
      error: null,
    };
  } catch (error) {
    return {
      audit: auditKey,
      viewport: viewportName,
      path: audit.path,
      status: null,
      state: 'failed',
      ms: Date.now() - startedAt,
      checks: audit.required_controls?.length || 0,
      warnings,
      error: error.message,
    };
  } finally {
    await context.close();
  }
}

const launchOptions = { headless: !headed };
const executablePath = findChromiumExecutable();
if (executablePath) {
  launchOptions.executablePath = executablePath;
}

const browser = await chromium.launch(launchOptions);
const results = [];

try {
  for (const [auditKey, audit] of Object.entries(audits)) {
    for (const [viewportName, viewport] of Object.entries(audit.viewports || { desktop: { width: 1366, height: 768 } })) {
      results.push(await runOne(browser, auditKey, audit, viewportName, viewport));
    }
  }
} finally {
  await browser.close();
}

process.stdout.write(JSON.stringify({ results }, null, 2));
process.exit(results.some(result => result.state === 'failed') ? 1 : 0);
