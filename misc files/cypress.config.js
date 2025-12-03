// cypress.config.js
const { defineConfig } = require("cypress");

module.exports = defineConfig({
    e2e: {
        baseUrl: 'http://localhost:8000', // Your Laravel dev server
        supportFile: 'cypress/support/e2e.js',
        specPattern: 'cypress/e2e/**/*.cy.{js,jsx,ts,tsx}',
        viewportWidth: 1920,
        viewportHeight: 1080,
        defaultCommandTimeout: 10000,
        video: true,
        screenshotOnRunFailure: true,

        setupNodeEvents(on, config) {
            // Laravel-specific tasks
            on('task', {
                seedDatabase() {
                    const { execSync } = require('child_process');
                    execSync('php artisan migrate:fresh --seed --env=testing');
                    return null;
                },
                clearCache() {
                    const { execSync } = require('child_process');
                    execSync('php artisan cache:clear');
                    return null;
                }
            });
        },
    },
});
