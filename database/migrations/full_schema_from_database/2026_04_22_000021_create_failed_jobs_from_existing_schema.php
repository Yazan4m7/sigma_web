<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('failed_jobs')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `unit_num` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `type` bigint(20) unsigned NOT NULL,
  `color` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `style` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `material_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `is_modification` tinyint(4) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_failed` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `original_job_id` bigint(20) unsigned NOT NULL,
  `note_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `failed_jobs_material_id_foreign` (`material_id`),
  KEY `failed_jobs_type_foreign` (`type`),
  KEY `failed_jobs_order_id_foreign` (`order_id`),
  CONSTRAINT `failed_jobs_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  CONSTRAINT `failed_jobs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE,
  CONSTRAINT `failed_jobs_type_foreign` FOREIGN KEY (`type`) REFERENCES `job_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('failed_jobs');
    }
};
