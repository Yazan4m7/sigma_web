<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterReportFilterFixturesSeeder extends Seeder
{
    private const DATE_RANGE_START = '2031-01-01';
    private const DATE_RANGE_END = '2031-01-31';

    private const USER_ID = 901;

    private const CLIENT_IDS = [
        'completed' => 101,
        'printing' => 102,
        'pressing' => 103,
    ];

    private const MATERIAL_IDS = [
        'completed' => 301,
        'printing' => 302,
        'pressing' => 303,
    ];

    private const JOB_TYPE_IDS = [
        'completed' => 201,
        'printing' => 202,
        'pressing' => 203,
    ];

    private const TYPE_IDS = [
        'completed' => 401,
        'printing' => 402,
        'pressing' => 403,
    ];

    private const DEVICE_IDS = [
        'milling' => 801,
        'printing' => 802,
        'pressing' => 803,
    ];

    private const FAILURE_CAUSE_ID = 701;
    private const ABUTMENT_ID = 501;
    private const IMPLANT_ID = 602;

    private const CASE_IDS = [
        'completed' => 29001,
        'printing' => 29002,
        'pressing' => 29003,
    ];

    public function run()
    {
        DB::transaction(function () {
            $this->seedLookupTables();
            $this->seedFixtureUser();
            $this->clearFixtureCases();
            $this->seedCases();
        });
    }

    private function seedLookupTables(): void
    {
        $timestamp = $this->timestamp();

        foreach (self::CLIENT_IDS as $key => $id) {
            DB::table('clients')->updateOrInsert(
                ['id' => $id],
                [
                    'name' => "MR Fixture Doctor " . strtoupper(substr($key, 0, 1)),
                    'phone' => '0000000000',
                    'clinic_phone' => '0000000000',
                    'address' => 'Master Report Fixture',
                    'balance' => 0,
                    'active' => 1,
                    'doc_password' => 'fixture',
                    'clinic_password' => 'fixture',
                    'doc_notification_token' => null,
                    'clinic_notification_token' => null,
                    'deleted_at' => null,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]
            );
        }

        foreach (self::MATERIAL_IDS as $key => $id) {
            DB::table('materials')->updateOrInsert(
                ['id' => $id],
                [
                    'name' => 'MR Fixture Material ' . strtoupper(substr($key, 0, 1)),
                    'price' => 0,
                    'design' => 1,
                    'mill' => 1,
                    'print_3d' => 1,
                    'sinter_furnace' => 1,
                    'press_furnace' => 1,
                    'finish' => 1,
                    'qc' => 1,
                    'delivery' => 1,
                    'restricted' => 0,
                    'count_as_unit' => 1,
                    'count_in_units_counts_report' => 1,
                    'count_in_job_types_report' => 1,
                    'count_in_qc_report' => 1,
                    'count_in_implants_report' => 1,
                    'is_active' => 1,
                    'default_type_id' => self::TYPE_IDS[$key],
                    'deleted_at' => null,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]
            );
        }

        foreach (self::JOB_TYPE_IDS as $key => $id) {
            DB::table('job_types')->updateOrInsert(
                ['id' => $id],
                [
                    'name' => 'MR Fixture Job Type ' . strtoupper(substr($key, 0, 1)),
                    'teeth_or_jaw' => 'teeth',
                    'a_secondary_item' => 0,
                    'default_material_id' => self::MATERIAL_IDS[$key],
                    'deleted_at' => null,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]
            );
        }

        foreach (self::TYPE_IDS as $key => $id) {
            DB::table('types')->updateOrInsert(
                ['id' => $id],
                [
                    'name' => 'MR Fixture Type ' . strtoupper(substr($key, 0, 1)),
                    'description' => 'Master report fixture type',
                    'material_id' => self::MATERIAL_IDS[$key],
                    'is_enabled' => 1,
                    'deleted_at' => null,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]
            );
        }

        DB::table('devices')->updateOrInsert(
            ['id' => self::DEVICE_IDS['milling']],
            [
                'name' => 'MR Fixture Mill X1',
                'type' => 2,
                'sorting_order' => 1,
                'img' => null,
                'hidden' => 0,
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]
        );

        DB::table('devices')->updateOrInsert(
            ['id' => self::DEVICE_IDS['printing']],
            [
                'name' => 'MR Fixture Print X2',
                'type' => 3,
                'sorting_order' => 1,
                'img' => null,
                'hidden' => 0,
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]
        );

        DB::table('devices')->updateOrInsert(
            ['id' => self::DEVICE_IDS['pressing']],
            [
                'name' => 'MR Fixture Press X6',
                'type' => 5,
                'sorting_order' => 1,
                'img' => null,
                'hidden' => 0,
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]
        );

        DB::table('failure_causes')->updateOrInsert(
            ['id' => self::FAILURE_CAUSE_ID],
            [
                'text' => 'MR Fixture Failure Cause',
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]
        );

        DB::table('abutments')->updateOrInsert(
            ['id' => self::ABUTMENT_ID],
            [
                'name' => 'MR Fixture Abutment',
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]
        );

        DB::table('implants')->updateOrInsert(
            ['id' => self::IMPLANT_ID],
            [
                'name' => 'MR Fixture Implant',
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]
        );
    }

    private function seedFixtureUser(): void
    {
        $timestamp = $this->timestamp();

        DB::table('users')->updateOrInsert(
            ['id' => self::USER_ID],
            [
                'first_name' => 'Master',
                'last_name' => 'Fixture',
                'name_initials' => 'MF',
                'username' => 'master_report_fixture',
                'email' => 'master-report-fixture@example.test',
                'phone' => '0000000000',
                'password' => Hash::make('fixture-password'),
                'is_admin' => 0,
                'status' => 1,
                'included_in_reports' => 1,
                'is_developer' => 0,
                'remember_token' => null,
                'has_photo' => 0,
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]
        );

        foreach ([2, 3] as $permissionId) {
            DB::table('user_permissions')->updateOrInsert(
                [
                    'user_id' => self::USER_ID,
                    'permission_id' => $permissionId,
                ],
                [
                    'deleted_at' => null,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]
            );
        }
    }

    private function clearFixtureCases(): void
    {
        $caseIds = array_values(self::CASE_IDS);

        DB::table('failure_logs')->whereIn('case_id', $caseIds)->delete();
        DB::table('case_logs')->whereIn('case_id', $caseIds)->delete();
        DB::table('invoices')->whereIn('case_id', $caseIds)->delete();
        DB::table('jobs')->whereIn('case_id', $caseIds)->delete();
        DB::table('cases')->whereIn('id', $caseIds)->delete();
    }

    private function seedCases(): void
    {
        $timestamp = $this->timestamp();

        DB::table('cases')->insert([
            [
                'id' => self::CASE_IDS['completed'],
                'case_id' => 'MR-29001',
                'patient_name' => 'Master Report Fixture Completed',
                'initial_delivery_date' => self::DATE_RANGE_START . ' 09:00:00',
                'actual_delivery_date' => '2031-01-10 14:00:00',
                'delivered_to_client' => 1,
                'voucher_printed_by' => null,
                'voucher_recieved_by' => null,
                'doctor_id' => self::CLIENT_IDS['completed'],
                'finisher' => null,
                'impression_type' => null,
                'locked' => 0,
                'notification_sent' => 0,
                'created_by' => self::USER_ID,
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
                'is_returned' => 0,
                'is_a_remake' => 0,
                'is_rejected' => 0,
                'contains_modification' => 0,
                'first_case_if_repeated' => null,
                'delivered_in_box' => 0,
            ],
            [
                'id' => self::CASE_IDS['printing'],
                'case_id' => 'MR-29002',
                'patient_name' => 'Master Report Fixture Printing',
                'initial_delivery_date' => '2031-01-12 10:00:00',
                'actual_delivery_date' => null,
                'delivered_to_client' => 0,
                'voucher_printed_by' => null,
                'voucher_recieved_by' => null,
                'doctor_id' => self::CLIENT_IDS['printing'],
                'finisher' => null,
                'impression_type' => null,
                'locked' => 0,
                'notification_sent' => 0,
                'created_by' => self::USER_ID,
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
                'is_returned' => 0,
                'is_a_remake' => 0,
                'is_rejected' => 0,
                'contains_modification' => 0,
                'first_case_if_repeated' => null,
                'delivered_in_box' => 0,
            ],
            [
                'id' => self::CASE_IDS['pressing'],
                'case_id' => 'MR-29003',
                'patient_name' => 'Master Report Fixture Pressing',
                'initial_delivery_date' => '2031-01-18 11:00:00',
                'actual_delivery_date' => null,
                'delivered_to_client' => 0,
                'voucher_printed_by' => null,
                'voucher_recieved_by' => null,
                'doctor_id' => self::CLIENT_IDS['pressing'],
                'finisher' => null,
                'impression_type' => null,
                'locked' => 0,
                'notification_sent' => 0,
                'created_by' => self::USER_ID,
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
                'is_returned' => 0,
                'is_a_remake' => 0,
                'is_rejected' => 0,
                'contains_modification' => 0,
                'first_case_if_repeated' => null,
                'delivered_in_box' => 0,
            ],
        ]);

        DB::table('jobs')->insert([
            [
                'unit_num' => '11,12',
                'type' => self::JOB_TYPE_IDS['completed'],
                'color' => null,
                'style' => null,
                'material_id' => self::MATERIAL_IDS['completed'],
                'type_id' => self::TYPE_IDS['completed'],
                'abutment' => self::ABUTMENT_ID,
                'implant' => null,
                'case_id' => self::CASE_IDS['completed'],
                'doctor_id' => self::CLIENT_IDS['completed'],
                'stage' => -1,
                'assignee' => self::USER_ID,
                'delivery_accepted' => self::USER_ID,
                'milling_lab' => null,
                'unit_price' => 75,
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
                'unit_failed' => 0,
                'is_rejection' => 0,
                'has_been_rejected' => 0,
                'is_repeat' => 0,
                'is_modification' => 0,
                'repeated_job_id' => null,
                'modified_job_id' => null,
                'rejected_job_id' => null,
                'original_job_id' => null,
                'is_redo' => 0,
                'redone_job_id' => null,
                'is_set' => 0,
                'device_id' => self::DEVICE_IDS['milling'],
                'is_active' => 1,
                'milling_build_id' => null,
                'printing_build_id' => null,
                'pressing_build_id' => null,
                'sintering_build_id' => null,
            ],
            [
                'unit_num' => '21,22,23',
                'type' => self::JOB_TYPE_IDS['printing'],
                'color' => null,
                'style' => null,
                'material_id' => self::MATERIAL_IDS['printing'],
                'type_id' => self::TYPE_IDS['printing'],
                'abutment' => null,
                'implant' => null,
                'case_id' => self::CASE_IDS['printing'],
                'doctor_id' => self::CLIENT_IDS['printing'],
                'stage' => 3,
                'assignee' => self::USER_ID,
                'delivery_accepted' => null,
                'milling_lab' => null,
                'unit_price' => 216.67,
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
                'unit_failed' => 0,
                'is_rejection' => 0,
                'has_been_rejected' => 0,
                'is_repeat' => 0,
                'is_modification' => 0,
                'repeated_job_id' => null,
                'modified_job_id' => null,
                'rejected_job_id' => null,
                'original_job_id' => null,
                'is_redo' => 0,
                'redone_job_id' => null,
                'is_set' => 0,
                'device_id' => self::DEVICE_IDS['printing'],
                'is_active' => 1,
                'milling_build_id' => null,
                'printing_build_id' => null,
                'pressing_build_id' => null,
                'sintering_build_id' => null,
            ],
            [
                'unit_num' => '31,32,33,34',
                'type' => self::JOB_TYPE_IDS['pressing'],
                'color' => null,
                'style' => null,
                'material_id' => self::MATERIAL_IDS['pressing'],
                'type_id' => self::TYPE_IDS['pressing'],
                'abutment' => null,
                'implant' => self::IMPLANT_ID,
                'case_id' => self::CASE_IDS['pressing'],
                'doctor_id' => self::CLIENT_IDS['pressing'],
                'stage' => 5,
                'assignee' => self::USER_ID,
                'delivery_accepted' => null,
                'milling_lab' => null,
                'unit_price' => 87.5,
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
                'unit_failed' => 0,
                'is_rejection' => 0,
                'has_been_rejected' => 0,
                'is_repeat' => 0,
                'is_modification' => 0,
                'repeated_job_id' => null,
                'modified_job_id' => null,
                'rejected_job_id' => null,
                'original_job_id' => null,
                'is_redo' => 0,
                'redone_job_id' => null,
                'is_set' => 0,
                'device_id' => self::DEVICE_IDS['pressing'],
                'is_active' => 1,
                'milling_build_id' => null,
                'printing_build_id' => null,
                'pressing_build_id' => null,
                'sintering_build_id' => null,
            ],
        ]);

        DB::table('invoices')->insert([
            [
                'status' => 1,
                'amount' => 150,
                'amount_before_discount' => 150,
                'case_id' => self::CASE_IDS['completed'],
                'doctor_id' => self::CLIENT_IDS['completed'],
                'discount_title' => null,
                'date_applied' => null,
                'rejection_invoice' => 0,
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'status' => 1,
                'amount' => 650,
                'amount_before_discount' => 650,
                'case_id' => self::CASE_IDS['printing'],
                'doctor_id' => self::CLIENT_IDS['printing'],
                'discount_title' => null,
                'date_applied' => null,
                'rejection_invoice' => 0,
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'status' => 1,
                'amount' => 350,
                'amount_before_discount' => 350,
                'case_id' => self::CASE_IDS['pressing'],
                'doctor_id' => self::CLIENT_IDS['pressing'],
                'discount_title' => null,
                'date_applied' => null,
                'rejection_invoice' => 0,
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ]);

        DB::table('case_logs')->insert([
            [
                'user_id' => self::USER_ID,
                'case_id' => self::CASE_IDS['completed'],
                'stage' => 2,
                'is_completion' => 1,
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'user_id' => self::USER_ID,
                'case_id' => self::CASE_IDS['printing'],
                'stage' => 3,
                'is_completion' => 0,
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ]);

        DB::table('failure_logs')->insert([
            [
                'case_id' => self::CASE_IDS['completed'],
                'original_case_id' => null,
                'failure_type' => null,
                'cause_id' => self::FAILURE_CAUSE_ID,
                'explanation' => 'Master report fixture failure',
                'done_by' => self::USER_ID,
                'old_delivery_date' => '2025-10-09 09:00:00',
                'deleted_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ]);
    }

    private function timestamp(): string
    {
        return Carbon::now()->toDateTimeString();
    }
}
