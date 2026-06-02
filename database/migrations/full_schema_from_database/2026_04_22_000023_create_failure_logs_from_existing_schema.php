<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('failure_logs')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `failure_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `case_id` bigint(20) NOT NULL,
  `original_case_id` bigint(20) DEFAULT NULL,
  `failure_type` tinyint(4) DEFAULT NULL,
  `cause_id` bigint(20) NOT NULL,
  `explanation` varchar(255) DEFAULT NULL,
  `done_by` bigint(20) NOT NULL,
  `old_delivery_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2612 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('failure_logs');
    }
};
