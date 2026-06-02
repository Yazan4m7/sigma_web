<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cases')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `cases` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `case_id` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `patient_name` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `initial_delivery_date` datetime DEFAULT NULL,
  `actual_delivery_date` timestamp NULL DEFAULT NULL,
  `delivered_to_client` tinyint(4) NOT NULL DEFAULT 0,
  `voucher_printed_by` bigint(20) DEFAULT NULL,
  `voucher_recieved_by` bigint(20) DEFAULT NULL,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `finisher` bigint(20) DEFAULT NULL,
  `impression_type` bigint(20) unsigned DEFAULT NULL,
  `locked` tinyint(4) NOT NULL DEFAULT 0,
  `notification_sent` tinyint(4) DEFAULT 0,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_returned` tinyint(4) NOT NULL DEFAULT 0,
  `is_a_remake` tinyint(4) NOT NULL DEFAULT 0,
  `is_rejected` tinyint(4) DEFAULT NULL,
  `contains_modification` tinyint(4) NOT NULL DEFAULT 0,
  `first_case_if_repeated` bigint(20) DEFAULT NULL,
  `delivered_in_box` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `orders_doctor_id_foreign` (`doctor_id`),
  KEY `orders_created_by_foreign` (`created_by`),
  KEY `idx_cases_doctor` (`doctor_id`),
  KEY `idx_cases_deleted_at` (`deleted_at`),
  KEY `idx_cases_doctor_deleted` (`doctor_id`,`deleted_at`),
  KEY `idx_cases_delivery_dates` (`actual_delivery_date`,`initial_delivery_date`),
  KEY `idx_cases_created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=29005 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
