-- The junction table `material_types` already exists in the database
-- Just need to add constraints and indexes if they don't exist

-- Add foreign key constraints to material_types table if they don't exist
ALTER TABLE `material_types` 
ADD CONSTRAINT `material_types_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `material_types` 
ADD CONSTRAINT `material_types_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Add unique index to prevent duplicate relationships
ALTER TABLE `material_types` 
ADD UNIQUE KEY `material_types_material_id_type_id_unique` (`material_id`, `type_id`);

-- Add composite index for better query performance
ALTER TABLE `material_types` 
ADD KEY `material_types_material_id_type_id_index` (`material_id`, `type_id`);

-- Note: The types table still has material_id column which can be used for backward compatibility
-- Future migration can remove material_id from types table and migrate data to material_types junction table