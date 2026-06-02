<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('materials')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `materials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `price` double NOT NULL,
  `design` tinyint(4) NOT NULL,
  `mill` tinyint(4) NOT NULL,
  `print_3d` tinyint(4) NOT NULL,
  `sinter_furnace` tinyint(4) NOT NULL,
  `press_furnace` tinyint(4) NOT NULL,
  `finish` tinyint(4) NOT NULL,
  `qc` tinyint(4) NOT NULL,
  `delivery` tinyint(4) NOT NULL,
  `restricted` tinyint(4) NOT NULL DEFAULT 0,
  `count_as_unit` tinyint(4) NOT NULL DEFAULT 1,
  `count_in_units_counts_report` tinyint(4) DEFAULT 1,
  `count_in_job_types_report` tinyint(4) NOT NULL DEFAULT 1,
  `count_in_qc_report` tinyint(4) NOT NULL DEFAULT 1,
  `count_in_implants_report` tinyint(4) NOT NULL DEFAULT 1,
  `is_active` mediumint(8) unsigned NOT NULL DEFAULT 1,
  `default_type_id` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=304 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
