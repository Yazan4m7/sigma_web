// scripts/cypress-test.js
const { exec } = require('child_process');
const fs = require('fs');

console.log('🔄 Running SIGMA Cypress tests...');

exec('npx cypress run', (error, stdout, stderr) => {
  const results = {
    timestamp: new Date().toISOString(),
    passed: !error,
    output: stdout,
    errors: stderr
  };
  
  fs.writeFileSync('cypress-results.json', JSON.stringify(results, null, 2));
  
  if (error) {
    console.log('❌ Some tests failed');
    console.log(stdout);
  } else {
    console.log('✅ All tests passed!');
  }
});