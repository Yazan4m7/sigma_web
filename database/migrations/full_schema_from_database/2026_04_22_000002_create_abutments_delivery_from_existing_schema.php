<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('abutments_delivery')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `abutments_delivery` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `case_id` bigint(20) NOT NULL,
  `job_id` bigint(20) NOT NULL,
  `abutment_id` bigint(20) DEFAULT NULL,
  `implant_id` bigint(20) DEFAULT NULL,
  `ordered_by` bigint(20) DEFAULT NULL,
  `ordered_on` timestamp NULL DEFAULT NULL,
  `qty` bigint(20) NOT NULL,
  `remaining_qty` bigint(20) NOT NULL,
  `units` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4883 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('abutments_delivery');
    }
};
