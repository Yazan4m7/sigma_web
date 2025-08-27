-- Material-JobType relationships for SIGMA dental lab system
-- This creates basic relationships between all materials and job types
-- You can refine these relationships based on your specific requirements

-- Clear existing data (uncomment if needed)
-- DELETE FROM material_jobtypes;

-- Sample relationships (adjust IDs based on your actual data)
-- First, let's create some common relationships:

-- Crown relationships (assuming Crown job type ID = 1)
INSERT INTO material_jobtypes (material_id, jobtype_id, created_at, updated_at) VALUES
(1, 1, NOW(), NOW()),  -- Material 1 with Crown
(2, 1, NOW(), NOW()),  -- Material 2 with Crown
(3, 1, NOW(), NOW()),  -- Material 3 with Crown
(4, 1, NOW(), NOW()),  -- Material 4 with Crown
(5, 1, NOW(), NOW());  -- Material 5 with Crown

-- Bridge relationships (assuming Bridge job type ID = 2)
INSERT INTO material_jobtypes (material_id, jobtype_id, created_at, updated_at) VALUES
(1, 2, NOW(), NOW()),  -- Material 1 with Bridge
(2, 2, NOW(), NOW()),  -- Material 2 with Bridge
(3, 2, NOW(), NOW()),  -- Material 3 with Bridge
(4, 2, NOW(), NOW());  -- Material 4 with Bridge

-- Abutment relationships (assuming Abutment job type ID = 3)
INSERT INTO material_jobtypes (material_id, jobtype_id, created_at, updated_at) VALUES
(1, 3, NOW(), NOW()),  -- Material 1 with Abutment
(2, 3, NOW(), NOW()),  -- Material 2 with Abutment
(6, 3, NOW(), NOW());  -- Material 6 with Abutment

-- Add more relationships as needed...
-- You can also create a comprehensive mapping by running this query:

-- To create relationships between ALL materials and ALL job types (comprehensive approach):
/*
INSERT INTO material_jobtypes (material_id, jobtype_id, created_at, updated_at)
SELECT m.id, jt.id, NOW(), NOW()
FROM materials m
CROSS JOIN job_types jt
WHERE NOT EXISTS (
    SELECT 1 FROM material_jobtypes mjt 
    WHERE mjt.material_id = m.id AND mjt.jobtype_id = jt.id
);
*/

-- Or create selective relationships based on material names:
/*
INSERT INTO material_jobtypes (material_id, jobtype_id, created_at, updated_at)
SELECT m.id, jt.id, NOW(), NOW()
FROM materials m
CROSS JOIN job_types jt
WHERE (
    -- Zirconia compatible with Crown, Bridge, Abutment
    (m.name LIKE '%Zirconia%' AND jt.name IN ('Crown', 'Bridge', 'Abutment'))
    OR
    -- E-max compatible with Crown, Bridge, Veneer
    (m.name LIKE '%E-max%' AND jt.name IN ('Crown', 'Bridge', 'Veneer'))
    OR
    -- Titanium compatible with Abutment, Implant
    (m.name LIKE '%Titanium%' AND jt.name IN ('Abutment', 'Implant'))
    OR
    -- PMMA compatible with Temporary work
    (m.name LIKE '%PMMA%' AND jt.name LIKE '%Temporary%')
    OR
    -- Generic compatibility for other materials
    (m.name NOT LIKE '%Zirconia%' AND m.name NOT LIKE '%E-max%' 
     AND m.name NOT LIKE '%Titanium%' AND m.name NOT LIKE '%PMMA%')
)
AND NOT EXISTS (
    SELECT 1 FROM material_jobtypes mjt 
    WHERE mjt.material_id = m.id AND mjt.jobtype_id = jt.id
);
*/