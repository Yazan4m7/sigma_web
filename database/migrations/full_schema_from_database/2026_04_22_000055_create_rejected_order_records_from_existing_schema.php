<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('rejected_order_records')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `rejected_order_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rejected_order_id` int(11) NOT NULL,
  `original_order_id` bigint(20) NOT NULL,
  `cause` varchar(255) NOT NULL,
  `details` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=313 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('rejected_order_records');
    }
};
