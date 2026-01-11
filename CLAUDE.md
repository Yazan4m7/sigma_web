
	# CLAUDE.md
 SIGMA (Dental Lab Management System) — Project Memory

## What this repo is
SIGMA is a Laravel-based dental lab workflow management system.
Core flow: Dentists/Clients create cases → cases move through lab stages (Design, Milling, 3D Printing, Sintering, Pressing, QC, Delivery) → billing & statements.

## Non-goals (avoid breaking scope)
- Do not redesign UI/UX without explicit request.
- Do not change database schema without a migration + clear rationale.
- Do not mix “quick fixes” with large refactors in the same patch.

## Tech + Structure assumptions
- Backend: Laravel (modular domains: Cases, Jobs, Clients/Dentists, Billing/Statements, Inventory, Logistics/Delivery).
- Frontend: Blade views + CSS (likely Bootstrap/Tailwind/custom) + JS as needed (Alpine/vanilla).
- Data: MySQL.

## Codebase map (update paths if different)
- Routes:
  - routes/web.php (app UI)
  - routes/api.php (AJAX/portal endpoints)
- App:
  - app/Http/Controllers/...
  - app/Models/...
  - app/Services/... (business logic if present)
- Views:
  - resources/views/...
  - resources/views/components/...
- Assets:
  - resources/css/...
  - resources/js/...
  - public/...

## Workflow rules (how you should work here)
1) For non-trivial tasks: propose a short plan (3–7 bullets) before editing.
2) Make minimal diffs. Don’t “clean up” unrelated files.
3) If you change behavior: update or add a test (Pest/PHPUnit) when feasible.
4) Always explain what changed + why, and list files touched.
5) always ask if theres a siginficant edit or something youre not sure of



## UI/CSS conventions
- Prefer CSS variables/tokens for brand colors.
- Prefer responsive rules using container-based scaling (avoid fixed px layouts on modals).
- For dialogs/modals: ensure mobile viewport fits without horizontal scroll.

## When to use subagents

- Use **css-specialist** for UI/CSS/responsive/layout tasks,
Search only those files : 
  /css/bootstrap.min.css
  /css/style.css
  assets/css/jquery.imagesloader.css
  assets/css/pages/login/login-3.css
  assets/css/skins/aside/dark.css
  assets/css/skins/brand/dark.css
  assets/css/skins/header/base/light.css
  assets/css/skins/header/menu/light.css
  assets/css/style.bundle.css
  assets/plugins/global/plugins.bundle.css
  {{ asset('assets') }}/css/active-cases.css
  {{ asset('assets') }}/css/blank_page_style.css
  {{ asset('assets') }}/css/custom-styling.css
  {{ asset('assets') }}/css/devices-dialog-fix.css
  {{ asset('assets') }}/css/devices-page.css
  {{ asset('assets') }}/css/operations-dashboard-table-fix.css
  {{ asset('assets') }}/css/operations-nav-responsive.css
  {{ asset('assets') }}/css/responsive.css
  {{ asset('assets') }}/css/sidebar-fix.css
  {{ asset('assets') }}/css/sidebar-fullwidth-fix.css
  {{ asset('assets') }}/css/sidebar-layout-improvements.css
  {{ asset('assets') }}/css/sweetalert2.min.css
  {{ asset('assets') }}/css/theme.css
  {{ asset('assets') }}/css/timeline.css
  {{ asset('assets') }}/css/v3styles.css
  {{ asset('assets') }}/css/waiting-dialog-merged.css
  {{ asset('assets') }}/css/waiting-dialog.css
  {{ asset('assets') }}/css/white-dashboard.css?v=1.0.0
  {{ asset('assets') }}/css/ysh-custom-css/OperationsDashboardStyling.css
  {{ asset('assets') }}/css/ysh-custom-css/dialog.css
  {{ asset('assets/css/bootstrap-select-fix.css') }}
  {{ asset('assets/css/jquery.imagesloader.css') }}
  {{ asset('assets/css/lightgallery.css') }}
  {{ asset('assets/css/permissions-checkbox.css') }}
  {{ asset('assets/css/sigma-reports-master.css') }}
  {{ asset('assets/css/sigma-reports-theme.css') }}
  {{ asset('css/ysh-custom-css/machine-images.css') }}
  {{asset('assets/css/menu.css')}}
  {{asset('assets/css/slidebars.min.css')}}
  {{asset('assets/css/style.css')}}





## ##############################################################

Below diff file

## ##############################################################

## Working Style
- **Be fast and surgical** - no exploratory scans or broad file searches
- **Make direct edits** - trust the specific file/line I reference
- **Skip verification phases** - don't check "related files" unless I ask
- **One-shot changes** - edit immediately, don't ask for confirmation


This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Application Overview

**SIGMA** is a comprehensive dental laboratory management system that orchestrates the complete manufacturing workflow for dental prosthetics (crowns, bridges, implants, abutments). The system manages the entire production pipeline from initial case creation through final delivery to dental clinics.

### Core Business Domains

1. **Case Management** - Patient case tracking with delivery dates and workflow progression
2. **Manufacturing Pipeline** - 8-stage sequential workflow (Design � Milling � 3D Printing � Sintering � Pressing � Finishing � QC � Delivery)
3. **Client Relations** - Dental clinics, doctors, payments, and invoicing
4. **Equipment Management** - Manufacturing devices (mills, printers, furnaces) with capacity tracking
5. **Materials & Job Types** - Dental materials, job definitions, and material-job relationships
6. **Financial Management** - Invoicing, payments, client accounts, and reporting


## Architecture & Code Organization

### Key Controllers
- **CaseController** (`app/Http/Controllers/CaseController.php`) - Core case lifecycle management, employee dashboards, workflow progression
- **OperationsUpgrade** - Advanced manufacturing operations, batch processing, device management
- **ReportsController** - Business intelligence, analytics, material usage reports
- **ClientsController** - Dental clinic management, payments, account statements
- **DevicesController** - Equipment management and maintenance tracking

### Database Structure
- **Core Entities**: `cases` (sCase model), `jobs`, `clients`, `devices`, `users`
- **Workflow Tracking**: `case_logs`, `builds`, `invoices`, `payments`
- **Reference Data**: `materials`, `job_types`, `implants`, `abutments`, `failure_causes`

### Middleware System
Role-based access control with extensive middleware for each manufacturing role:
- `Designer`, `Miller`, `Print3D`, `SinterFurnace`, `PressFurnace`, `Finishing`, `QC`, `Delivery`
- `AdminMiddleware`, `AccountantMiddleware`, `DeliveryMiddleware`

### Key Features
- **State Machine Workflow** - Sophisticated stage progression with sub-stages (e.g., 2.1, 2.2, 2.3)
- **Feature Flag System** - Controlled rollouts (`juststeveking/laravel-feature-flags`)
- **Soft Deletes** - Comprehensive audit trail preservation across models
- **Observer Pattern** - Automated logging via `AbutmentsObserver`, `JobObserver`
- **Mobile API** - RESTful endpoints for mobile access
- **Real-time Dashboards** - Live equipment status and performance metrics

## Development Patterns

### Route Organization
- Middleware-grouped routes by role and permission level
- Employee dashboard routes pattern: `/{role}/{id}` (e.g., `/milling/1`, `/design/2`)
- API routes for mobile integration in `routes/api.php`

### Model Conventions
- Models use soft deletes extensively for audit trails
- Observer pattern for automatic logging and notifications
- Relationships established between core entities (cases, jobs, devices, clients)

### View Components
Blade components in `app/View/Components/`:
- `devices-container.php` - Equipment status displays
- `delivery-dialog.php` - Delivery workflow modals
- `view-case-dialog.php` - Case detail modals

### Helper Utilities
- `app/Traits/OperationsHelper.php` - Reusable business logic
- `app/Helpers/CaseCache.php` - Performance optimization for complex queries
- Helper functions in `app/Http/Controllers/Helpers.php`

## Environment Configuration

### Database
- **Local**: MySQL on `127.0.0.1:3306`, database: `sigma`
- **Staging**: Database: `staging_db`
- Configure in `.env` file

### Key Environment Variables
```env
APP_NAME=sigma
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=mysql
DB_DATABASE=sigma
```

## Asset Pipeline
- Custom CSS in `public/assets/css/` including `v3styles.css` and custom styling

## File Upload Handling
- Case images stored in `public/caseImages/{case_id}/`
- Device images in `public/devicesImages/`
- User profile images in `public/users/`
- the relationship between cases and devices and how you fetch the devices used in cases
- the table structre and style i asked for
- the database schema

Be direct and efficient. Don't scan entire files or do broad searches before making changes. Trust that I know what I'm asking for. Make targeted edits to specific files/lines without unnecessary verification steps.