const { chromium } = require('playwright');
const fs = require('fs');

// Test scenarios with updated case IDs (214-228)
const testScenarios = [
    {
        id: 'TC-01',
        name: 'Default Load',
        url: 'http://localhost:8000/reports/master?generate_report=1',
        expected: 'Cases 214-224, 226-228 (~14 cases)',
        expectedCount: 14
    },
    {
        id: 'TC-02',
        name: 'Specific Date Range (Old Case)',
        url: 'http://localhost:8000/reports/master?generate_report=1&from=2025-09-28&to=2025-09-30',
        expected: 'Case 225',
        expectedCases: [225]
    },
    {
        id: 'TC-03',
        name: 'Single Doctor (Client 2)',
        url: 'http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=2',
        expected: 'Cases 214, 217, 221, 226',
        expectedCases: [214, 217, 221, 226]
    },
    {
        id: 'TC-04',
        name: 'Multiple Doctors (2, 3)',
        url: 'http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=2&doctor%5B%5D=3',
        expected: 'Cases 214, 215, 217, 218, 221, 223, 226, 228',
        expectedCases: [214, 215, 217, 218, 221, 223, 226, 228]
    },
    {
        id: 'TC-05a',
        name: 'Workflow Stage - Finishing',
        url: 'http://localhost:8000/reports/master?generate_report=1&status%5B%5D=6',
        expected: 'Case 227',
        expectedCases: [227]
    },
    {
        id: 'TC-05b',
        name: 'Workflow Stage - Design',
        url: 'http://localhost:8000/reports/master?generate_report=1&status%5B%5D=1',
        expected: 'Cases 222, 226',
        expectedCases: [222, 226]
    },
    {
        id: 'TC-05c',
        name: 'Workflow Stage - 3D Printing',
        url: 'http://localhost:8000/reports/master?generate_report=1&status%5B%5D=3',
        expected: 'Cases 215, 222, 224',
        expectedCases: [215, 222, 224]
    },
    {
        id: 'TC-08',
        name: 'Amount Range - From Only (>=100)',
        url: 'http://localhost:8000/reports/master?generate_report=1&amount_from=100',
        expected: 'All except 219 (14 cases)',
        expectedCount: 14
    },
    {
        id: 'TC-09',
        name: 'Amount Range - To Only (<=500)',
        url: 'http://localhost:8000/reports/master?generate_report=1&amount_to=500',
        expected: 'All except 220 (14 cases)',
        expectedCount: 14
    },
    {
        id: 'TC-10',
        name: 'Amount Range - Between (100-500)',
        url: 'http://localhost:8000/reports/master?generate_report=1&amount_from=100&amount_to=500',
        expected: 'Cases 214, 215, 216, 217, 218, 221, 223, 224, 225, 227, 228',
        expectedCases: [214, 215, 216, 217, 218, 221, 223, 224, 225, 227, 228]
    },
    {
        id: 'TC-10b',
        name: 'Low Amount Range (1-100)',
        url: 'http://localhost:8000/reports/master?generate_report=1&amount_from=1&amount_to=100',
        expected: 'Cases 217, 219',
        expectedCases: [217, 219]
    },
    {
        id: 'TC-12',
        name: 'Units Range (2-4)',
        url: 'http://localhost:8000/reports/master?generate_report=1&units_from=2&units_to=4',
        expected: 'Cases 215, 222, 224',
        expectedCases: [215, 222, 224]
    },
    {
        id: 'TC-12b',
        name: 'Many Units (6+)',
        url: 'http://localhost:8000/reports/master?generate_report=1&units_from=6&units_to=10',
        expected: 'Case 220',
        expectedCases: [220]
    },
    {
        id: 'TC-13',
        name: 'Completion Status - Completed',
        url: 'http://localhost:8000/reports/master?generate_report=1&show_completed=completed',
        expected: 'Cases 214, 216, 217, 219, 220, 221, 225',
        expectedCases: [214, 216, 217, 219, 220, 221, 225]
    },
    {
        id: 'TC-14',
        name: 'Completion Status - In Progress',
        url: 'http://localhost:8000/reports/master?generate_report=1&show_completed=in_progress',
        expected: 'Cases 215, 218, 222, 223, 224, 226, 227, 228',
        expectedCases: [215, 218, 222, 223, 224, 226, 227, 228]
    },
    {
        id: 'EXTRA-01',
        name: 'Job Type - Crowns Only',
        url: 'http://localhost:8000/reports/master?generate_report=1&job_type%5B%5D=1',
        expected: '10 cases',
        expectedCount: 10
    },
    {
        id: 'EXTRA-02',
        name: 'Job Type - Bridges Only',
        url: 'http://localhost:8000/reports/master?generate_report=1&job_type%5B%5D=2',
        expected: 'Cases 215, 220, 224',
        expectedCases: [215, 220, 224]
    },
    {
        id: 'EXTRA-03',
        name: 'Job Type - Implants Only',
        url: 'http://localhost:8000/reports/master?generate_report=1&job_type%5B%5D=6',
        expected: 'Case 216',
        expectedCases: [216]
    },
    {
        id: 'TC-19',
        name: 'No Results Found',
        url: 'http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=99999',
        expected: 'No cases found',
        expectedCases: []
    },
    {
        id: 'TC-21',
        name: 'Complex Combination',
        url: 'http://localhost:8000/reports/master?generate_report=1&from=2025-10-01&to=2025-10-29&doctor%5B%5D=all&material%5B%5D=all&job_type%5B%5D=all&status%5B%5D=all&amount_from=1&amount_to=200&show_completed=all',
        expected: 'Cases 214, 216, 217, 218, 219, 221, 223, 225, 227, 228',
        expectedCases: [214, 216, 217, 218, 219, 221, 223, 225, 227, 228]
    }
];

async function extractCaseIds(page) {
    try {
        // Wait for either the table or "no cases" message
        await page.waitForSelector('table, .no-cases-message, .alert', { timeout: 5000 });

        // Check for "no cases found" message
        const noResults = await page.locator('text=/no cases found/i').count();
        if (noResults > 0) {
            return [];
        }

        // Extract case IDs from table rows with data-case-id attribute
        const caseIds = await page.$$eval('[data-case-id]', elements =>
            elements.map(el => parseInt(el.getAttribute('data-case-id')))
                    .filter(id => !isNaN(id))
                    .sort((a, b) => a - b)
        );

        return [...new Set(caseIds)]; // Remove duplicates
    } catch (error) {
        console.log('  ⚠️ Could not extract case IDs:', error.message);
        return null;
    }
}

function compareResults(actual, expected) {
    if (expected.expectedCases) {
        const actualSet = new Set(actual);
        const expectedSet = new Set(expected.expectedCases);
        const missing = expected.expectedCases.filter(id => !actualSet.has(id));
        const extra = actual.filter(id => !expectedSet.has(id));

        const pass = missing.length === 0 && extra.length === 0;
        return {
            pass,
            missing,
            extra,
            message: pass ? '✅ PASS' : '❌ FAIL'
        };
    } else if (expected.expectedCount !== undefined) {
        const pass = actual.length === expected.expectedCount;
        return {
            pass,
            message: pass ? '✅ PASS' : `❌ FAIL (expected ${expected.expectedCount}, got ${actual.length})`
        };
    }
    return { pass: true, message: '✓ OK' };
}

async function runTests() {
    const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
    const logFile = `master-report-playwright-results-${timestamp}.md`;
    let log = `# Master Report Test Results (Playwright)\n\n`;
    log += `**Test Date:** ${new Date().toLocaleString()}\n`;
    log += `**Base URL:** http://localhost:8000\n`;
    log += `**Total Tests:** ${testScenarios.length}\n\n`;
    log += `---\n\n`;

    console.log('\n===========================================');
    console.log('Master Report Playwright Test Execution');
    console.log('===========================================\n');

    const browser = await chromium.launch({
        headless: true,
        args: ['--no-sandbox']
    });

    const context = await browser.newContext();
    const page = await context.newPage();

    // Login first
    console.log('🔐 Logging in to SIGMA...');
    try {
        await page.goto('http://localhost:8000/login');
        await page.fill('input[name="email"]', 'admin@admin.com');
        await page.fill('input[name="password"]', 'admin');
        await page.click('button[type="submit"]');
        await page.waitForNavigation({ timeout: 10000 });
        console.log('✅ Login successful\n');
    } catch (error) {
        console.error('❌ Login failed:', error.message);
        await browser.close();
        return;
    }

    let totalPassed = 0;
    let totalFailed = 0;

    // Run each test
    for (const test of testScenarios) {
        console.log(`\n${'='.repeat(50)}`);
        console.log(`Testing: ${test.id} - ${test.name}`);
        console.log(`URL: ${test.url}`);
        console.log(`Expected: ${test.expected}`);
        console.log('='.repeat(50));

        log += `## ${test.id}: ${test.name}\n\n`;
        log += `**URL:**\n\`\`\`\n${test.url}\n\`\`\`\n\n`;
        log += `**Expected Result:** ${test.expected}\n\n`;

        try {
            await page.goto(test.url, { waitUntil: 'networkidle', timeout: 15000 });

            // Wait a bit for any JS to execute
            await page.waitForTimeout(2000);

            const caseIds = await extractCaseIds(page);

            if (caseIds === null) {
                console.log('  ⚠️ Could not determine results');
                log += `**Actual Result:** Could not extract case IDs\n\n`;
                log += `**Status:** ⚠️ ERROR\n\n`;
                totalFailed++;
            } else if (caseIds.length === 0) {
                console.log('  📊 Case Count: 0');
                console.log('  📋 Case IDs: None (no cases found)');

                const comparison = compareResults(caseIds, test);
                console.log(`  ${comparison.message}`);

                log += `**Actual Result:**\n`;
                log += `- Case Count: 0\n`;
                log += `- Case IDs: None\n\n`;
                log += `**Status:** ${comparison.message}\n\n`;

                if (comparison.pass) totalPassed++;
                else totalFailed++;
            } else {
                console.log(`  📊 Case Count: ${caseIds.length}`);
                console.log(`  📋 Case IDs: ${caseIds.join(', ')}`);

                const comparison = compareResults(caseIds, test);
                console.log(`  ${comparison.message}`);

                if (comparison.missing && comparison.missing.length > 0) {
                    console.log(`  ⚠️ Missing cases: ${comparison.missing.join(', ')}`);
                }
                if (comparison.extra && comparison.extra.length > 0) {
                    console.log(`  ⚠️ Extra cases: ${comparison.extra.join(', ')}`);
                }

                log += `**Actual Result:**\n`;
                log += `- Case Count: ${caseIds.length}\n`;
                log += `- Case IDs: ${caseIds.join(', ')}\n`;
                if (comparison.missing && comparison.missing.length > 0) {
                    log += `- Missing: ${comparison.missing.join(', ')}\n`;
                }
                if (comparison.extra && comparison.extra.length > 0) {
                    log += `- Extra: ${comparison.extra.join(', ')}\n`;
                }
                log += `\n**Status:** ${comparison.message}\n\n`;

                if (comparison.pass) totalPassed++;
                else totalFailed++;
            }
        } catch (error) {
            console.log(`  ❌ ERROR: ${error.message}`);
            log += `**Error:** ${error.message}\n\n`;
            totalFailed++;
        }

        log += `---\n\n`;
    }

    await browser.close();

    // Summary
    const passRate = ((totalPassed / testScenarios.length) * 100).toFixed(1);

    console.log('\n' + '='.repeat(50));
    console.log('TEST SUMMARY');
    console.log('='.repeat(50));
    console.log(`Total Tests: ${testScenarios.length}`);
    console.log(`Passed: ${totalPassed} ✅`);
    console.log(`Failed: ${totalFailed} ❌`);
    console.log(`Pass Rate: ${passRate}%`);
    console.log('='.repeat(50) + '\n');

    log += `## Test Summary\n\n`;
    log += `**Total Tests:** ${testScenarios.length}\n`;
    log += `**Tests Passed:** ${totalPassed} ✅\n`;
    log += `**Tests Failed:** ${totalFailed} ❌\n`;
    log += `**Pass Rate:** ${passRate}%\n\n`;
    log += `---\n\n`;
    log += `**Generated:** ${new Date().toLocaleString()}\n`;

    // Write log file
    fs.writeFileSync(logFile, log);
    console.log(`📄 Results saved to: ${logFile}\n`);
}

runTests().catch(console.error);
