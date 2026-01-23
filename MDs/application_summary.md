# Sigma Dental Lab Management System - Application Summary

## Core Purpose
This is a comprehensive dental laboratory management system that tracks dental cases (prosthetics) through their entire production workflow - from design to delivery.

## Main Components

1. **Manufacturing Workflow**: The system manages an 8-stage workflow:
   - Design (Stage 1)
   - Milling (Stage 2)
   - 3D Printing (Stage 3)
   - Sintering Furnace (Stage 4)
   - Pressing Furnace (Stage 5)
   - Finishing (Stage 6)
   - Quality Control (Stage 7)
   - Delivery (Stage 8)

2. **Device Management**: Tracks equipment like milling machines, 3D printers, and furnaces used in production.

3. **Build System**: Groups jobs into batches ("builds") for efficient processing, particularly for stages that require specialized equipment (milling, 3D printing, sintering).

4. **Case Tracking**: The central entity is a dental case with multiple jobs that move through the workflow independently.

## Key Entities

- **sCase**: Central entity representing a dental case
- **Job**: Specific work items within a case
- **Device**: Equipment used in production
- **Build**: Batch of jobs processed together
- **Client/Doctor**: Dental clinics/doctors who submit cases
- **User**: Staff members with different roles

## Key Features

- Role-based permissions for different staff members
- Detailed logging of all actions in the workflow
- Device assignment and management
- Build creation and tracking for batch processing
- Client/doctor management
- Delivery scheduling and driver assignment

## Technical Implementation

The system is built on Laravel (PHP) with:
- Models following Eloquent ORM patterns
- Controllers for different stages of the workflow
- Blade templating for views
- Bootstrap with custom styling for frontend

## Key Technical Details

1. **Stage Configuration**: The system uses a STAGE_CONFIG array in OperationsUpgrade.php that defines configuration for each manufacturing stage, including which stages require build names.

2. **Workflow States**: Each stage has sub-stages (e.g., set, start, complete) with decimal notation (2.1, 2.2, 2.3).

3. **Build Management**: Stages 2 (milling), 3 (3D printing), and 4 (sintering) require build names and have specialized fields in the jobs table (milling_build_id, printing_build_id, sintering_build_id).

4. **Device Types**: Different stages use different device types (mill, printer, furnace, driver).

5. **Controllers**: 
   - CaseController: Base functionality for case management
   - OperationsUpgrade: Extends CaseController for specialized workflow handling
   - DevicesController: Manages production equipment

This document provides a high-level overview of the application architecture and functionality as analyzed in the initial exploration session.