# AGENTS.md - Codex AI Agent Instructions

## Project Overview
Dental Operations Management System - A comprehensive platform for managing dental cases, schedules, doctors, and production workflows.

**🏠 LOCAL DEVELOPMENT MODE** ⬅️ **ADDED**
- Running on local machine via `php artisan serve`
- No Git version control
- DO NOT analyze the repo.
- Database: Local MySQL
- URL: http://127.0.0.1:8000

---

## 🚫 CRITICAL: DO NOT MODIFY

### Protected Files - Never Change These:
- `.env` - Environment configuration (contains sensitive credentials)
- `.env.example` - Environment template
- `composer.lock` - PHP dependency lock file (only update via composer)
- `package-lock.json` - NPM dependency lock file (only update via npm)
- `storage/logs/*` - Log files (read-only for debugging)
- `database/migrations/*_create_*.php` - Initial migration files (create new migrations instead)
- `public/.htaccess` - Apache rewrite rules (unless explicitly requested)
- `config/database.php` - Database configuration (use .env instead)

### Protected Directories:
- `storage/app/private/` - User uploaded files
- `storage/framework/` - Laravel framework cache
- `vendor/` - Composer dependencies (managed by composer)
- `node_modules/` - NPM dependencies (managed by npm)

---

### Database Guidelines
- **NEVER** run raw SQL without parameterization
- **ALWAYS** use Eloquent ORM or Query Builder
- **NEVER** delete migration files - create new ones to modify tables
- **ALWAYS** use database transactions for multiple related operations
- **ALWAYS** add indexes for foreign keys and frequently queried columns
```php
// Good
DB::transaction(function () {
    $case = Case::create($data);
    $case->jobs()->create($jobData);
});

// Bad - no transaction
$case = Case::create($data);
$case->jobs()->create($jobData);
```

### Frontend Guidelines
- **DO NOT** use inline styles - use CSS classes
- **DO** maintain consistent spacing and indentation
- **DO** use semantic HTML elements
- **DO** ensure WCAG AA accessibility compliance
- **DO** test responsive design on mobile/tablet/desktop

---

---

- **NEVER** allow dropdowns to animate from corners
- **ALWAYS** position directly below trigger element
- **DISABLE** Popper.js auto-positioning

### Forms:
- **ALWAYS** show validation errors inline
- **ALWAYS** mark required fields with asterisk
- **NEVER** submit forms without validation
- **ALWAYS** provide success/error feedback

### Tables:
- **PROVIDE** search/filter functionality
- **ENSURE** mobile responsive (stack or scroll)

---

---

## 📁 File Structure Guidelines

### Controllers:
```
app/Http/Controllers/
├── CaseController.php           # Case CRUD operations
├── DoctorController.php         # Doctor management
├── DeliveryScheduleController.php
└── ReportController.php
```
- Keep controllers thin - move logic to services
- One resource per controller
- Use resource controllers for CRUD operations

### Models:
```
app/Models/
├── Case.php
├── Doctor.php
├── Patient.php
└── Job.php
```
- Define relationships in models
- Use mutators/accessors for data transformation
- Include fillable or guarded properties

### Views:
```
resources/views/
├── layouts/
│   └── app.blade.php           # Main layout - DO NOT BREAK
├── cases/
│   ├── index.blade.php         # Cases list
│   ├── create.blade.php        # New case form
│   └── show.blade.php          # Case details
└── dashboard/
    └── index.blade.php         # Operations dashboard
```

---
## 🧪 Testing Requirements

### After Codex Makes Changes: 
- [ ] Test all modified features manually in browser
- [ ] Check responsive design on mobile/tablet
- [ ] Verify no console errors in browser DevTools
- [ ] Test with different user roles
- [ ] Check for any breaking changes
- [ ] Verify no exceptions 

### Critical Pages to Test:
1. **Operations Dashboard** - `http://127.0.0.1:8000/dashboard` ⬅️ **MODIFIED**
2. **Cases List** - `http://127.0.0.1:8000/cases` (with filters) ⬅️ **MODIFIED**
3. **Create Case** - `http://127.0.0.1:8000/cases/create` (all dropdowns must work) ⬅️ **MODIFIED**
4. **Delivery Schedule** - `http://127.0.0.1:8000/delivery-schedule` ⬅️ **MODIFIED**
5. **Reports** - `http://127.0.0.1:8000/reports` ⬅️ **MODIFIED**

### After Codex Changes Files: ⬅️ **ADDED**
```bash
# If PHP files changed:
composer dump-autoload

# If CSS/views changed:
npm run dev
# or for auto-recompile: npm run watch

# If routes changed:
php artisan route:clear

# If config changed:
php artisan config:clear



## 🐛 Debugging Guidelines

### When Investigating Issues:
1. Enable query logging temporarily if needed
2. Use browser DevTools Console and Network tabs
3. Check database for data integrity issues
4. Check Laravel logs: `storage/logs/laravel.log` ⬅️ **ADDED**

### Common Issues:
- **Dropdown positioning**: Check CSS position/transform properties
- **Brightness issues**: Verify color values match design system
- **Form validation**: Check both client and server-side validation
- **500 errors**: Check `storage/logs/laravel.log` ⬅️ **ADDED**
- **CSS not updating**: Run `npm run dev` and hard refresh browser ⬅️ **ADDED**

---

## ⚡ Performance Optimization

### ALWAYS Consider:
- Database query optimization (use eager loading)
- Cache frequently accessed data
- Minimize CSS/JS file sizes
- Optimize images before upload
- Use CDN for static assets if available
### Example - Eager Loading:
```php
// Bad - N+1 query problem
$cases = Case::all();
foreach ($cases as $case) {
    echo $case->doctor->name; // Separate query each time
}

// Good - Single query with eager loading
$cases = Case::with('doctor')->get();
foreach ($cases as $case) {
    echo $case->doctor->name; // No additional queries
}
```

---

## 📞 Contact & Escalation ⬅️ **SIMPLIFIED**

### When Codex Should Ask for Clarification:
- Ambiguous requirements
- Changes that might affect other features
- Database schema changes
- Breaking changes to existing functionality

### When Codex Should STOP and Ask:
- ❌ Request to delete database data
- ❌ Changes to authentication/authorization logic
- ❌ Changes affecting sensitive medical data
- ❌ Anything that could cause data loss

---

## 📋 Task Completion Checklist

Before marking any task as complete:
- [ ] Code follows style guidelines
- [ ] No protected files were modified
- [ ] All dropdowns work correctly
- [ ] Design system colors are used
- [ ] Responsive design is verified
- [ ] No console errors
- [ ] Database queries are optimized
- [ ] Comments added for complex logic

---

## 🎯 Current Project Priorities

### High Priority:
1. Fix dropdown positioning across all pages
2. Reduce brightness/improve color scheme consistency
3. Optimize dashboard performance
4. Improve mobile responsiveness

### Medium Priority:
1. Add comprehensive form validation
2. Implement better error handling
3. Add loading states to async operations
4. Improve accessibility

### Low Priority:
1. Code refactoring for maintainability
2. ~~Add automated tests~~ ⬅️ **REMOVED** (not priority for local dev)
3. Performance monitoring
4. Documentation improvements

---

**Last Updated**: December 2024  
**Version**: 1.0  
**Maintained By**: Development Team