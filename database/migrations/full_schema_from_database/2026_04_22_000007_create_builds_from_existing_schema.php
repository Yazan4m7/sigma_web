<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('builds')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `builds` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `set_at` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `finished_at` timestamp NULL DEFAULT NULL,
  `device_used` varchar(20) NOT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_builds_device_status` (`device_used`,`finished_at`,`started_at`),
  KEY `idx_builds_device` (`device_used`),
  KEY `idx_builds_set_at` (`set_at`),
  KEY `idx_builds_started_at` (`started_at`),
  KEY `idx_builds_finished_at` (`finished_at`),
  KEY `idx_builds_deleted_at` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=1873 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('builds');
    }
};
