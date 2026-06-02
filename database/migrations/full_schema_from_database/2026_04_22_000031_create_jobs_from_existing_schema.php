<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('jobs')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `unit_num` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `type` bigint(20) unsigned NOT NULL,
  `color` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `style` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `material_id` bigint(20) unsigned NOT NULL,
  `type_id` bigint(20) unsigned DEFAULT NULL,
  `abutment` bigint(20) DEFAULT NULL,
  `implant` bigint(20) DEFAULT NULL,
  `case_id` bigint(20) unsigned NOT NULL,
  `doctor_id` bigint(20) DEFAULT NULL,
  `stage` float DEFAULT NULL,
  `assignee` tinyint(4) DEFAULT NULL,
  `delivery_accepted` bigint(20) DEFAULT NULL,
  `milling_lab` bigint(20) DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_failed` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `is_rejection` tinyint(4) NOT NULL DEFAULT 0,
  `has_been_rejected` tinyint(4) NOT NULL DEFAULT 0,
  `is_repeat` tinyint(4) NOT NULL DEFAULT 0,
  `is_modification` tinyint(4) NOT NULL DEFAULT 0,
  `repeated_job_id` bigint(20) DEFAULT NULL,
  `modified_job_id` bigint(20) DEFAULT NULL,
  `rejected_job_id` bigint(20) DEFAULT NULL,
  `original_job_id` bigint(20) DEFAULT NULL,
  `is_redo` tinyint(4) DEFAULT 0,
  `redone_job_id` bigint(20) DEFAULT NULL,
  `is_set` tinyint(4) DEFAULT NULL,
  `device_id` bigint(20) DEFAULT NULL,
  `is_active` bigint(20) DEFAULT NULL,
  `milling_build_id` bigint(20) unsigned DEFAULT NULL,
  `printing_build_id` bigint(20) unsigned DEFAULT NULL,
  `pressing_build_id` bigint(20) unsigned DEFAULT NULL,
  `sintering_build_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_material_id_foreign` (`material_id`),
  KEY `jobs_type_foreign` (`type`),
  KEY `jobs_order_id_foreign` (`case_id`),
  KEY `jobs_type_id_index` (`type_id`),
  KEY `idx_jobs_stage_assignee` (`stage`,`assignee`),
  KEY `idx_jobs_stage_set_active` (`stage`,`is_set`,`is_active`),
  KEY `idx_jobs_case_stage` (`case_id`,`stage`),
  KEY `idx_jobs_device_stage_set` (`device_id`,`stage`,`is_set`),
  KEY `idx_jobs_milling_build` (`milling_build_id`),
  KEY `idx_jobs_printing_build` (`printing_build_id`),
  KEY `idx_jobs_sintering_build` (`sintering_build_id`),
  KEY `idx_jobs_pressing_build` (`pressing_build_id`),
  KEY `idx_jobs_stage_delivery` (`stage`,`delivery_accepted`),
  KEY `idx_jobs_deleted_at` (`deleted_at`),
  KEY `idx_jobs_stage` (`stage`),
  KEY `idx_jobs_assignee` (`assignee`),
  KEY `idx_jobs_material` (`material_id`),
  KEY `idx_jobs_type` (`type`),
  CONSTRAINT `jobs_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jobs_order_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jobs_type_foreign` FOREIGN KEY (`type`) REFERENCES `job_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37345 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
