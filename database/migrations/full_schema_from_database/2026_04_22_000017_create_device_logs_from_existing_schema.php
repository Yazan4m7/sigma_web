<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('device_logs')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `device_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `case_id` bigint(20) NOT NULL,
  `sintered_with` bigint(20) DEFAULT NULL,
  `Impressed_with` bigint(20) DEFAULT NULL,
  `zircon_milled_with` bigint(20) DEFAULT NULL,
  `emax_wax_milled_with` bigint(20) DEFAULT NULL,
  `printed_with` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1081 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('device_logs');
    }
};
