# Dead Code Analysis Report

**Generated:** 2026-02-08
**Project:** Laravel Application (Sigma)

---

## Executive Summary

This report identifies unused code, dependencies, and assets that can be safely removed from the project.

**Total Items Identified:** 7
- **SAFE to Delete:** 5 items
- **CAUTION:** 1 item
- **DANGER:** 1 item

---

## 1. Unused NPM Dependencies

### SAFE - Unused Node Dependencies

The following npm packages are installed but not used in the codebase:

| Package | Reason | Size Impact |
|---------|--------|-------------|
| `claude` | No usage found in any PHP/JS files | ~2MB |
| `node-fetch` | No usage found in any PHP/JS files | ~500KB |
| `dotenv` | Laravel uses `.env` natively, this Node package is unused | ~100KB |

**Action:** Remove from `package.json` dependencies section

**Risk Level:** SAFE
- These are Node.js packages with no references in code
- Build system (Laravel Mix) doesn't use them
- Can reinstall if needed later

---

## 2. Unused Vue.js Components

### SAFE - Example Vue Component

**File:** `resources/js/components/ExampleComponent.vue`

**Status:** Registered in `app.js` but never used in any Blade templates

**Search Results:**
- ✓ Registered: `resources/js/app.js:22`
- ✗ No usage in `resources/views/**/*.blade.php`
- ✗ No `<example-component>` tags found

**Action:** Remove component file and registration

**Risk Level:** SAFE
- Demo/boilerplate component
- No references in views
- Entire Vue.js setup appears unused

---

## 3. Unused JavaScript Build Configuration

### CAUTION - Commented Mix Configuration

**File:** `webpack.mix.js`

**Issue:** Lines 27-29 contain commented-out build configuration:
```javascript
// mix.js('resources/js/app.js', 'public/js')
//     .vue()
//     .sass('resources/sass/app.scss', 'public/css');
```

**Current State:**
- JavaScript compilation is disabled
- Vue compilation is disabled
- SASS compilation is disabled
- `public/js/app.js` exists but may be stale

**Action:** Either:
1. Remove entire `resources/js/` directory if Vue is not needed, OR
2. Enable compilation if Vue will be used

**Risk Level:** CAUTION
- Need to verify if `public/js/app.js` is referenced anywhere
- May be legacy code that was disabled intentionally

---

## 4. Potentially Unused Vue Dependencies

### SAFE - Vue.js NPM Packages

If Vue.js is not being used (based on disabled webpack mix config):

| Package | Purpose | Size |
|---------|---------|------|
| `vue` | Vue.js framework | ~300KB |
| `vue-loader` | Webpack loader for Vue | ~100KB |
| `vue-template-compiler` | Template compiler | ~200KB |

**Action:** Remove if Vue functionality is confirmed unused

**Risk Level:** SAFE (after verification)
- Currently in `devDependencies`
- Build is disabled in webpack.mix.js
- No Vue tags found in Blade templates

---

## 5. Git Tracked Files Outside Repository

### DANGER - Files in Parent Directories

**Issue:** Git status shows tracked files outside the repository:
```
D ../../Supra/web_app/public/README.txt
D ../../Supra/web_app/public/dataHandler.js
D ../../Supra/web_app/public/monitor.html
```

These are deleted files in `../Supra/web_app/` being tracked by this repository.

**Action:** Clean git index of parent directory references

**Risk Level:** DANGER
- Could indicate repository misconfiguration
- May affect git operations
- Requires careful review before cleanup

---

## 6. Test Files Without Tests

### SAFE - Empty Test Infrastructure

**File:** Based on git status, Playwright and Cypress are installed but:

| Tool | Status |
|------|--------|
| Playwright | Installed, no test files visible in scan |
| Cypress | Installed, scripts configured in package.json |

**Action:** Review if test files exist; if not, these are candidates for removal

**Risk Level:** SAFE
- Testing frameworks without tests
- Can reinstall when needed

---

## 7. Unused Helper Autoloads

### Verify - Autoloaded Helper Files

**Files declared in `composer.json:44-48`:**
- `app/Helpers/helpers.php`
- `app/Helpers/ConfigHelper.php`
- `app/Helpers/ImageHelper.php`

**Status:**
- ✓ ImageHelper found used: `app/Helpers/ImageHelper.php`
- ? Need to verify: `helpers.php` and `ConfigHelper.php`

**Action:** Audit these files for actual usage

**Risk Level:** Needs Investigation

---

## Recommended Cleanup Actions

### Phase 1: SAFE Deletions (No Tests Required)

1. **Remove unused npm dependencies:**
   ```bash
   npm uninstall claude node-fetch dotenv
   ```

2. **Remove Example Vue Component:**
   ```bash
   rm resources/js/components/ExampleComponent.vue
   # Edit resources/js/app.js to remove line 22
   ```

### Phase 2: Verify Before Deletion

3. **Verify Vue.js Usage:**
   - Search for Vue component usage across all views
   - If unused, remove Vue dependencies and `/resources/js/` directory
   - Update webpack.mix.js accordingly

4. **Audit Helper Files:**
   - Check if `helpers.php` and `ConfigHelper.php` are actually called
   - Remove from composer.json autoload if unused

### Phase 3: Git Cleanup (CAUTION)

5. **Fix Git Repository Structure:**
   - Investigate why parent directory files are tracked
   - Run `git rm` to clean deleted file references
   - Ensure `.git` directory is only in project root

---

## Testing Protocol

Before deleting any code:

1. ✓ Run existing test suite
   ```bash
   php artisan test
   ```

2. ✓ Verify application builds
   ```bash
   npm run prod
   ```

3. ✓ Check for broken references after each deletion

4. ✓ Test critical user flows manually

---

## Summary Table

| Category | Item | Severity | Est. Space Saved |
|----------|------|----------|------------------|
| NPM Deps | claude, node-fetch, dotenv | SAFE | ~2.6MB |
| Vue Component | ExampleComponent.vue | SAFE | ~5KB |
| Vue Dependencies | vue, vue-loader, etc. | SAFE* | ~600KB |
| Git Issues | Parent dir tracked files | DANGER | N/A |
| Test Frameworks | Unused test files | SAFE | Variable |

**Total Potential Space Savings:** ~3.2MB+ in node_modules

---

## Notes

- This analysis is based on static code scanning
- Some dynamic imports may not be detected
- Always run tests before finalizing deletions
- Keep git history; changes can be reverted if needed

**Next Steps:** Review this report and approve Phase 1 safe deletions.
