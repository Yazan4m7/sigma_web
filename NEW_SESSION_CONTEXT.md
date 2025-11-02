SIGMA SYSTEM - NEW SESSION CONTEXT
===================================

PROJECT OVERVIEW:
SIGMA is a comprehensive dental laboratory management system built with Laravel 8.x that manages the complete manufacturing workflow for dental prosthetics (crowns, bridges, implants, abutments).

CURRENT PROJECT STATE:
======================
- Laravel 8.x application
- Database: MySQL (local: sigma, staging: staging_db)
- Working devices page implementation completed
- Operations dashboard functional
- 8-stage manufacturing pipeline: Design → Milling → 3D Printing → Sintering → Pressing → Finishing → QC → Delivery

CORE ENTITIES & RELATIONSHIPS:
=============================
1. Cases (sCase model) - Patient cases with delivery dates
2. Jobs - Individual work items within cases
3. Materials - Physical substances (Zirconia, PMMA, Lithium Disilicate, etc.)
4. Job Types - Categories of work (Crown, Bridge, Implant, Abutment, Veneer)
5. Devices - Manufacturing equipment (mills, printers, furnaces)
6. Clients - Dental clinics and doctors
7. Users - System users with role-based access

KEY RELATIONSHIPS:
- Case 1:N Jobs
- Job N:1 Material
- Job N:1 JobType
- Job N:1 Device (when active)
- Case N:1 Client

CURRENT ARCHITECTURE:
====================
Controllers:
- CaseController.php - Core case lifecycle, employee dashboards, devices page
- OperationsUpgrade.php - Manufacturing operations, batch processing
- ReportsController.php - Analytics and reporting
- ClientsController.php - Client management
- DevicesController.php - Equipment management

Key Models:
- sCase.php - Main case model
- job.php - Job model
- device.php - Device model
- Build.php - Manufacturing build model
- material.php - Materials model
- job_type.php - Job types model

Database Structure:
- cases table (main case data)
- jobs table (work items)
- devices table (equipment)
- builds table (manufacturing batches)
- materials table (material definitions)
- job_types table (work categories)

RECENT WORK COMPLETED:
=====================
- Devices page (/devices) fully functional with:
  - Device grid layout with visual effects
  - Configuration panel (image sizes, grouping, sorting)
  - Sortable drag-and-drop functionality
  - Device state management (active, waiting, inactive)
  - Modal dialogs for device operations
  - Badge system showing job counts
  - Redirect handling for form submissions

- Operations dashboard improvements:
  - Device counting logic matches across pages
  - Visual state effects implementation
  - Form submission redirects properly

CURRENT TASK IN PROGRESS:
=========================
Planning implementation of "Type" (Sub Material) system:
- Type = Sub-category of Material (e.g., Zirconia → Full Contour, Layered, Monolithic)
- 5-phase implementation plan created (see TYPE_IMPLEMENTATION_PLAN.txt)
- Ready to start Phase 1: Database & Core Models

PHASE 1 TASKS (READY TO BEGIN):
==============================
1. Create migration for 'types' table with material_id foreign key
2. Create Type.php model with Material relationship
3. Update Job.php model to include type_id relationship  
4. Update material.php model with hasMany types relationship
5. Create TypeSeeder.php with sample data

FILES STRUCTURE:
===============
Key Directories:
- app/Http/Controllers/ - Main controllers
- app/Models/ - Eloquent models
- resources/views/ - Blade templates
- database/migrations/ - Database migrations
- database/seeders/ - Data seeders
- public/assets/ - CSS, JS, images
- routes/web.php - Route definitions

Important Files:
- resources/views/devices/devices-page.blade.php - Devices page
- resources/views/admin/adminDashboard_v2.blade.php - Operations dashboard
- resources/views/components/active-cases-dialog.blade.php - Device dialog
- app/Http/Controllers/CaseController.php - Main controller
- app/Http/Controllers/OperationsUpgrade.php - Operations controller

ENVIRONMENT:
============
- Working directory: /mnt/c/Users/Yazan/Desktop/sigma/staging
- Platform: WSL2 Linux on Windows
- PHP artisan commands available
- Composer and npm installed
- Git repository active

DEVELOPMENT COMMANDS:
====================
Cache clearing: php artisan cache:clear && php artisan view:clear && php artisan config:clear
Migrations: php artisan migrate
Seeders: php artisan db:seed
Development server: php artisan serve

NEXT STEPS:
===========
Ready to implement Phase 1 of Type system:
1. Start with database migration creation
2. Follow the detailed plan in TYPE_IMPLEMENTATION_PLAN.txt
3. Test each component before proceeding

TECHNICAL NOTES:
===============
- Use Laravel 8.x syntax and conventions
- Follow existing code patterns in the project
- Maintain backward compatibility
- Test thoroughly before proceeding to next phase
- Use proper relationships and foreign keys
- Follow the established naming conventions