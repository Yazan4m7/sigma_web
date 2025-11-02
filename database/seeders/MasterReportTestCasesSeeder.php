<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\sCase;
use App\job;
use App\invoice;
use App\Build;
use App\caseLog;
use App\failureLog;
use Carbon\Carbon;

class MasterReportTestCasesSeeder extends Seeder
{
    /**
     * Run the database seeds for Master Report testing.
     * Creates 15 comprehensive test cases covering all filter combinations.
     *
     * @return void
     */
    public function run()
    {
        echo "Starting Master Report Test Cases Creation...\n\n";

        // Get required data
        $clients = \App\client::where('active', 1)->take(5)->get();
        if ($clients->count() < 5) {
            echo "Error: Need at least 5 active clients\n";
            return;
        }

        $materials = \App\material::all();
        $jobTypes = \App\JobType::all();
        $devices = \App\device::all();
        $users = \App\User::where('status', 1)->get();
        $abutments = \App\abutment::all();
        $implants = \App\implant::all();
        $failureCauses = \App\failureCause::all();

        echo "Found:\n";
        echo "- {$clients->count()} clients\n";
        echo "- {$materials->count()} materials\n";
        echo "- {$jobTypes->count()} job types\n";
        echo "- {$devices->count()} devices\n";
        echo "- {$users->count()} users\n\n";

        // Helper to find specific data
        $findMaterial = function($name) use ($materials) {
            return $materials->firstWhere('name', 'LIKE', "%{$name}%") ?? $materials->first();
        };

        $findJobType = function($id) use ($jobTypes) {
            return $jobTypes->firstWhere('id', $id) ?? $jobTypes->first();
        };

        $findDevice = function($type) use ($devices) {
            return $devices->where('type', $type)->first() ?? $devices->first();
        };

        $admin = $users->where('is_admin', 1)->first() ?? $users->first();

        // ========================================
        // CASE 1: Basic Completed Crown - All Stages
        // ========================================
        echo "Creating Case 1: Basic Completed Crown...\n";
        $case1 = $this->createCase([
            'doctor_id' => $clients[0]->id,
            'patient_name' => 'Test Patient A',
            'initial_delivery_date' => Carbon::now()->subDays(10),
            'actual_delivery_date' => Carbon::now()->subDays(2),
        ]);

        $job1 = $this->createJob([
            'case_id' => $case1->id,
            'type' => 1, // Crown
            'material_id' => $findMaterial('Zircon')->id,
            'unit_num' => '11',
            'stage' => -1, // Completed
            'assignee' => $admin->id,
        ]);

        $this->createInvoice($case1->id, 150);
        echo "✓ Case 1 created (ID: {$case1->id})\n\n";

        // ========================================
        // CASE 2: Bridge with Multiple Units - In Progress
        // ========================================
        echo "Creating Case 2: Bridge In-Progress...\n";
        $case2 = $this->createCase([
            'doctor_id' => $clients[1]->id,
            'patient_name' => 'Test Patient B',
            'initial_delivery_date' => Carbon::now()->addDays(5),
            'actual_delivery_date' => null,
        ]);

        $job2 = $this->createJob([
            'case_id' => $case2->id,
            'type' => 2, // Bridge
            'material_id' => $findMaterial('Emax')->id,
            'unit_num' => '21,22,23',
            'stage' => 3, // 3D Printing
            'assignee' => $admin->id,
        ]);

        $this->createInvoice($case2->id, 450);
        echo "✓ Case 2 created (ID: {$case2->id})\n\n";

        // ========================================
        // CASE 3: Implant Case with Abutments
        // ========================================
        echo "Creating Case 3: Implant with Abutments...\n";
        $case3 = $this->createCase([
            'doctor_id' => $clients[2]->id,
            'patient_name' => 'Test Patient C',
            'initial_delivery_date' => Carbon::now()->subDays(15),
            'actual_delivery_date' => Carbon::now()->subDays(3),
        ]);

        $abutment = $abutments->first();
        $implant = $implants->first();

        $job3 = $this->createJob([
            'case_id' => $case3->id,
            'type' => 6, // Implant (typical ID)
            'material_id' => $findMaterial('Zircon')->id,
            'unit_num' => '36',
            'stage' => -1,
            'abutment' => $abutment ? $abutment->id : null,
            'implant' => $implant ? $implant->id : null,
            'assignee' => $admin->id,
        ]);

        $this->createInvoice($case3->id, 200);
        echo "✓ Case 3 created (ID: {$case3->id})\n\n";

        // ========================================
        // CASE 4: Failed/Rejected Case
        // ========================================
        echo "Creating Case 4: Failed/Rejected Case...\n";
        $case4 = $this->createCase([
            'doctor_id' => $clients[0]->id,
            'patient_name' => 'Test Patient D',
            'initial_delivery_date' => Carbon::now()->subDays(20),
            'actual_delivery_date' => Carbon::now()->subDays(5),
        ]);

        $job4 = $this->createJob([
            'case_id' => $case4->id,
            'type' => 1, // Crown
            'material_id' => $findMaterial('Acrylic')->id,
            'unit_num' => '14',
            'stage' => -1,
            'is_rejection' => true,
            'has_been_rejected' => true,
            'assignee' => $admin->id,
        ]);

        if ($failureCauses->count() > 0) {
            failureLog::create([
                'case_id' => $case4->id,
                'cause_id' => $failureCauses->first()->id,
            ]);
        }

        $this->createInvoice($case4->id, 100);
        echo "✓ Case 4 created (ID: {$case4->id})\n\n";

        // ========================================
        // CASE 5: Repeat Case
        // ========================================
        echo "Creating Case 5: Repeat Case...\n";
        $case5 = $this->createCase([
            'doctor_id' => $clients[1]->id,
            'patient_name' => 'Test Patient E',
            'initial_delivery_date' => Carbon::now()->addDays(3),
            'actual_delivery_date' => null,
        ]);

        $job5 = $this->createJob([
            'case_id' => $case5->id,
            'type' => 1, // Crown
            'material_id' => $findMaterial('Telescopic')->id ?? $findMaterial('Zircon')->id,
            'unit_num' => '12',
            'stage' => 5, // Pressing
            'is_repeat' => true,
            'original_job_id' => $job4->id,
            'assignee' => $admin->id,
        ]);

        $this->createInvoice($case5->id, 120);
        echo "✓ Case 5 created (ID: {$case5->id})\n\n";

        // ========================================
        // CASE 6: Low Amount Case
        // ========================================
        echo "Creating Case 6: Low Amount Case...\n";
        $case6 = $this->createCase([
            'doctor_id' => $clients[3]->id ?? $clients[0]->id,
            'patient_name' => 'Test Patient F',
            'initial_delivery_date' => Carbon::now()->subDays(8),
            'actual_delivery_date' => Carbon::now()->subDays(1),
        ]);

        $job6 = $this->createJob([
            'case_id' => $case6->id,
            'type' => 1, // Crown
            'material_id' => $findMaterial('Acrylic')->id,
            'unit_num' => '15',
            'stage' => -1,
            'assignee' => $admin->id,
        ]);

        $this->createInvoice($case6->id, 50);
        echo "✓ Case 6 created (ID: {$case6->id})\n\n";

        // ========================================
        // CASE 7: High Amount Case
        // ========================================
        echo "Creating Case 7: High Amount Case...\n";
        $case7 = $this->createCase([
            'doctor_id' => $clients[4]->id ?? $clients[0]->id,
            'patient_name' => 'Test Patient G',
            'initial_delivery_date' => Carbon::now()->subDays(12),
            'actual_delivery_date' => Carbon::now()->subDays(4),
        ]);

        $job7 = $this->createJob([
            'case_id' => $case7->id,
            'type' => 2, // Bridge
            'material_id' => $findMaterial('Zircon')->id,
            'unit_num' => '11,12,13,14,15,16',
            'stage' => -1,
            'assignee' => $admin->id,
        ]);

        $this->createInvoice($case7->id, 900);
        echo "✓ Case 7 created (ID: {$case7->id})\n\n";

        // ========================================
        // CASE 8: Employee Assignment Test
        // ========================================
        echo "Creating Case 8: Employee Assignment Test...\n";
        $designUser = $users->filter(function($user) {
            return $user->permissions->contains('permission_id', 1);
        })->first() ?? $admin;

        $deliveryUser = $users->filter(function($user) {
            return $user->permissions->contains('permission_id', 8);
        })->first() ?? $admin;

        $case8 = $this->createCase([
            'doctor_id' => $clients[0]->id,
            'patient_name' => 'Test Patient H',
            'initial_delivery_date' => Carbon::now()->subDays(7),
            'actual_delivery_date' => Carbon::now()->subDay(),
        ]);

        $job8 = $this->createJob([
            'case_id' => $case8->id,
            'type' => 1, // Crown
            'material_id' => $findMaterial('Emax')->id,
            'unit_num' => '25',
            'stage' => -1,
            'assignee' => $designUser->id,
            'delivery_accepted' => $deliveryUser->id,
        ]);

        $this->createInvoice($case8->id, 180);
        echo "✓ Case 8 created (ID: {$case8->id})\n\n";

        // ========================================
        // CASE 9: Multiple Materials in One Case
        // ========================================
        echo "Creating Case 9: Multiple Materials...\n";
        $case9 = $this->createCase([
            'doctor_id' => $clients[2]->id,
            'patient_name' => 'Test Patient I',
            'initial_delivery_date' => Carbon::now()->addDays(7),
            'actual_delivery_date' => null,
        ]);

        $job9a = $this->createJob([
            'case_id' => $case9->id,
            'type' => 1,
            'material_id' => $findMaterial('Zircon')->id,
            'unit_num' => '31',
            'stage' => 2, // Milling
            'assignee' => $admin->id,
        ]);

        $job9b = $this->createJob([
            'case_id' => $case9->id,
            'type' => 1,
            'material_id' => $findMaterial('Emax')->id,
            'unit_num' => '32',
            'stage' => 3, // 3D Printing
            'assignee' => $admin->id,
        ]);

        $job9c = $this->createJob([
            'case_id' => $case9->id,
            'type' => 1,
            'material_id' => $findMaterial('Acrylic')->id,
            'unit_num' => '33',
            'stage' => 1, // Design
            'assignee' => $admin->id,
        ]);

        $this->createInvoice($case9->id, 380);
        echo "✓ Case 9 created (ID: {$case9->id})\n\n";

        // ========================================
        // CASE 10: Device-Specific Case (Milling)
        // ========================================
        echo "Creating Case 10: Milling Device Test...\n";
        $case10 = $this->createCase([
            'doctor_id' => $clients[1]->id,
            'patient_name' => 'Test Patient J',
            'initial_delivery_date' => Carbon::now()->addDays(4),
            'actual_delivery_date' => null,
        ]);

        $millDevice = $findDevice(2); // Type 2 = Milling
        $job10 = $this->createJob([
            'case_id' => $case10->id,
            'type' => 1,
            'material_id' => $findMaterial('Zircon')->id,
            'unit_num' => '17',
            'stage' => 2, // Milling
            'assignee' => $admin->id,
            'device_id' => $millDevice ? $millDevice->id : null,
        ]);

        // Note: Build creation skipped - can be added later if device filter testing needed

        $this->createInvoice($case10->id, 150);
        echo "✓ Case 10 created (ID: {$case10->id})\n\n";

        // ========================================
        // CASE 11: Device-Specific Case (3D Printing)
        // ========================================
        echo "Creating Case 11: 3D Printing Device Test...\n";
        $case11 = $this->createCase([
            'doctor_id' => $clients[3]->id ?? $clients[0]->id,
            'patient_name' => 'Test Patient K',
            'initial_delivery_date' => Carbon::now()->addDays(6),
            'actual_delivery_date' => null,
        ]);

        $printDevice = $findDevice(3); // Type 3 = 3D Printing
        $job11 = $this->createJob([
            'case_id' => $case11->id,
            'type' => 2, // Bridge
            'material_id' => $findMaterial('Acrylic')->id,
            'unit_num' => '41,42',
            'stage' => 3, // 3D Printing
            'assignee' => $admin->id,
            'device_id' => $printDevice ? $printDevice->id : null,
        ]);

        // Note: Build creation skipped - can be added later if device filter testing needed

        $this->createInvoice($case11->id, 220);
        echo "✓ Case 11 created (ID: {$case11->id})\n\n";

        // ========================================
        // CASE 12: Old Date Case (Last Month)
        // ========================================
        echo "Creating Case 12: Old Date Case...\n";
        $case12 = $this->createCase([
            'doctor_id' => $clients[4]->id ?? $clients[0]->id,
            'patient_name' => 'Test Patient L',
            'initial_delivery_date' => Carbon::now()->subDays(30),
            'actual_delivery_date' => Carbon::now()->subDays(25),
        ]);

        $job12 = $this->createJob([
            'case_id' => $case12->id,
            'type' => 1,
            'material_id' => $findMaterial('Telescopic')->id ?? $findMaterial('Emax')->id,
            'unit_num' => '18',
            'stage' => -1,
            'assignee' => $admin->id,
        ]);

        $this->createInvoice($case12->id, 160);
        echo "✓ Case 12 created (ID: {$case12->id})\n\n";

        // ========================================
        // CASE 13: Recent Case (Today)
        // ========================================
        echo "Creating Case 13: Recent Case (Today)...\n";
        $case13 = $this->createCase([
            'doctor_id' => $clients[0]->id,
            'patient_name' => 'Test Patient M',
            'initial_delivery_date' => Carbon::now(),
            'actual_delivery_date' => null,
        ]);

        $job13 = $this->createJob([
            'case_id' => $case13->id,
            'type' => 1,
            'material_id' => $findMaterial('Zircon')->id,
            'unit_num' => '26',
            'stage' => 1, // Design
            'assignee' => $admin->id,
        ]);

        // No invoice yet (too new)
        echo "✓ Case 13 created (ID: {$case13->id})\n\n";

        // ========================================
        // CASE 14: Modification Case
        // ========================================
        echo "Creating Case 14: Modification Case...\n";
        $case14 = $this->createCase([
            'doctor_id' => $clients[2]->id,
            'patient_name' => 'Test Patient N',
            'initial_delivery_date' => Carbon::now()->addDays(2),
            'actual_delivery_date' => null,
            'contains_modification' => true,
        ]);

        $job14 = $this->createJob([
            'case_id' => $case14->id,
            'type' => 1,
            'material_id' => $findMaterial('Emax')->id,
            'unit_num' => '35',
            'stage' => 6, // Finishing
            'is_modification' => true,
            'modified_job_id' => $job1->id,
            'assignee' => $admin->id,
        ]);

        $this->createInvoice($case14->id, 140);
        echo "✓ Case 14 created (ID: {$case14->id})\n\n";

        // ========================================
        // CASE 15: Redo Case
        // ========================================
        echo "Creating Case 15: Redo Case...\n";
        $case15 = $this->createCase([
            'doctor_id' => $clients[1]->id,
            'patient_name' => 'Test Patient O',
            'initial_delivery_date' => Carbon::now()->addDays(8),
            'actual_delivery_date' => null,
        ]);

        $job15 = $this->createJob([
            'case_id' => $case15->id,
            'type' => 1,
            'material_id' => $findMaterial('Zircon')->id,
            'unit_num' => '45',
            'stage' => 7, // QC
            'is_redo' => true,
            'redone_job_id' => $job4->id,
            'assignee' => $admin->id,
        ]);

        $this->createInvoice($case15->id, 170);
        echo "✓ Case 15 created (ID: {$case15->id})\n\n";

        echo "\n========================================\n";
        echo "✅ ALL 15 TEST CASES CREATED SUCCESSFULLY!\n";
        echo "========================================\n\n";

        echo "Test Case Summary:\n";
        echo "1. Basic Completed Crown (All stages) - Case ID: {$case1->id}\n";
        echo "2. Bridge In-Progress (3D Printing) - Case ID: {$case2->id}\n";
        echo "3. Implant with Abutments - Case ID: {$case3->id}\n";
        echo "4. Failed/Rejected Case - Case ID: {$case4->id}\n";
        echo "5. Repeat Case (Pressing) - Case ID: {$case5->id}\n";
        echo "6. Low Amount Case (50 JOD) - Case ID: {$case6->id}\n";
        echo "7. High Amount Case (900 JOD) - Case ID: {$case7->id}\n";
        echo "8. Employee Assignment Test - Case ID: {$case8->id}\n";
        echo "9. Multiple Materials - Case ID: {$case9->id}\n";
        echo "10. Milling Device Test - Case ID: {$case10->id}\n";
        echo "11. 3D Printing Device Test - Case ID: {$case11->id}\n";
        echo "12. Old Date Case (30 days ago) - Case ID: {$case12->id}\n";
        echo "13. Recent Case (Today) - Case ID: {$case13->id}\n";
        echo "14. Modification Case (Finishing) - Case ID: {$case14->id}\n";
        echo "15. Redo Case (QC) - Case ID: {$case15->id}\n";
    }

    /**
     * Create a case with given attributes
     */
    private function createCase($attributes)
    {
        return sCase::create(array_merge([
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ], $attributes));
    }

    /**
     * Create a job with given attributes
     */
    private function createJob($attributes)
    {
        return job::create(array_merge([
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'is_active' => true,
        ], $attributes));
    }

    /**
     * Create an invoice for a case
     */
    private function createInvoice($caseId, $amount)
    {
        return invoice::create([
            'case_id' => $caseId,
            'amount' => $amount,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
