<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('clients')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `clinic_phone` varchar(255) DEFAULT NULL,
  `address` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `balance` double DEFAULT 0,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `doc_password` varchar(191) DEFAULT NULL,
  `clinic_password` varchar(191) DEFAULT NULL,
  `doc_notification_token` varchar(255) DEFAULT NULL,
  `clinic_notification_token` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
