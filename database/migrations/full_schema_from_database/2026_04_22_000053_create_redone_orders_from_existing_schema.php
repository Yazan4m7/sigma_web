<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('redone_orders')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `redone_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `redone_to_stage` int(11) DEFAULT NULL,
  `cause` varchar(255) DEFAULT NULL,
  `details` varchar(255) DEFAULT NULL,
  `redone_by` int(11) NOT NULL,
  `desinged_by` bigint(20) DEFAULT NULL,
  `milled_by` bigint(20) DEFAULT NULL,
  `furnaced_by` bigint(20) DEFAULT NULL,
  `finished_by` bigint(20) DEFAULT NULL,
  `units_redone` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('redone_orders');
    }
};
