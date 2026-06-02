<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('page_load_tests')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `page_load_tests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mode` varchar(20) NOT NULL DEFAULT 'http',
  `page_key` varchar(100) DEFAULT NULL,
  `label` varchar(150) NOT NULL,
  `url` varchar(2048) NOT NULL,
  `http_status` smallint(5) unsigned DEFAULT NULL,
  `total_time_ms` int(10) unsigned DEFAULT NULL,
  `namelookup_time_ms` int(10) unsigned DEFAULT NULL,
  `connect_time_ms` int(10) unsigned DEFAULT NULL,
  `appconnect_time_ms` int(10) unsigned DEFAULT NULL,
  `pretransfer_time_ms` int(10) unsigned DEFAULT NULL,
  `starttransfer_time_ms` int(10) unsigned DEFAULT NULL,
  `redirect_time_ms` int(10) unsigned DEFAULT NULL,
  `size_download` bigint(20) unsigned DEFAULT NULL,
  `size_upload` bigint(20) unsigned DEFAULT NULL,
  `speed_download` bigint(20) unsigned DEFAULT NULL,
  `speed_upload` bigint(20) unsigned DEFAULT NULL,
  `primary_ip` varchar(64) DEFAULT NULL,
  `local_ip` varchar(64) DEFAULT NULL,
  `error_message` varchar(255) DEFAULT NULL,
  `tested_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_page_key` (`page_key`),
  KEY `idx_tested_by` (`tested_by`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('page_load_tests');
    }
};
