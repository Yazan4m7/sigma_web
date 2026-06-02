<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payments')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `amount` double NOT NULL,
  `notes` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `collector` bigint(20) unsigned NOT NULL,
  `received_by` bigint(20) DEFAULT NULL,
  `recieved_on` timestamp NULL DEFAULT NULL,
  `is_credit_note` tinyint(4) NOT NULL DEFAULT 0,
  `from_bank` bigint(20) DEFAULT NULL,
  `additional_notes` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_logs_doctor_id_foreign` (`doctor_id`),
  KEY `payment_logs_collector_foreign` (`collector`),
  CONSTRAINT `payment_logs_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1968 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
