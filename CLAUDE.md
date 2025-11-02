
	# CLAUDE.md

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

## Development Commands

### Initial Setup
```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env  # Configure database settings
php artisan key:generate
php artisan migrate
php artisan db:seed  # If applicable
```

### Development Server
```bash
# Start Laravel development server
php artisan serve  # http://localhost:8000

# Asset compilation and watching
npm run watch      # Development with file watching
npm run hot       # Hot reload with BrowserSync
npm run dev       # Single development build
npm run prod      # Production build
```

### Cache Management
```bash
# Clear all caches (recommended)
./clear-cache.sh

# Individual cache clearing
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan optimize:clear
```

### Testing
```bash
# Run tests
./vendor/bin/phpunit
./vendor/bin/phpunit tests/Feature
./vendor/bin/phpunit tests/Unit
```

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
APP_NAME=Laravel_Staging
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=mysql
DB_DATABASE=sigma
```

### Optional Integrations
- OpenAI API integration (OPENAI_API_KEY, OPENROUTER_API_KEY)
- Firebase notifications (service account JSON files present)

## Asset Pipeline
- **Laravel Mix** configuration in `webpack.mix.js`
- **BrowserSync** setup for live reload during development
- Custom CSS in `public/assets/css/` including `v3styles.css` and custom styling
- Vue.js components supported for frontend interactivity

## File Upload Handling
- Case images stored in `public/caseImages/{case_id}/`
- Device images in `public/devicesImages/`
- User profile images in `public/users/`
- the relationship between cases and devices and how you fetch the devices used in cases
- the table structre and style i asked for
- the database schema