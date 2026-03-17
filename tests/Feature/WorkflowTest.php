<?php

namespace Tests\Feature;

use App\client;
use App\sCase;
use App\job;
use App\material;
use App\User;
use App\caseLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_case_can_progress_through_the_workflow_stages()
    {
        // 1. Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        $client = client::create(['name' => 'Test Doctor']);
        $material = material::create([
            'name' => 'Zirconia',
            'price' => 100,
            'design' => 1,
            'mill' => 1,
            'finish' => 1,
            'qc' => 1,
            'delivery' => 1,
        ]);

        $case = sCase::create([
            'patient_name' => 'Test Patient',
            'doctor_id' => $client->id,
            'case_id' => 'workflowcase123',
            'initial_delivery_date' => now()->addWeek(),
        ]);

        $job = job::create([
            'case_id' => $case->id,
            'type' => 1, // JobType ID
            'material_id' => $material->id,
            'unit_num' => '11',
            'stage' => 1, // Start at Design stage
        ]);

        // 2. Act & Assert

        // Stage 1: Design
        $this->get(route('assign-to-me', ['caseId' => $case->id, 'stage' => 1]));
        $this->assertDatabaseHas('jobs', ['id' => $job->id, 'assignee' => $user->id]);

        $this->get(route('finish-case', ['caseId' => $case->id, 'stage' => 1]));
        $this->assertDatabaseHas('jobs', ['id' => $job->id, 'stage' => 2]);
        $this->assertDatabaseHas('case_logs', ['case_id' => $case->id, 'stage' => 1, 'is_completion' => 1]);

        // Stage 2: Milling
        $this->get(route('assign-to-me', ['caseId' => $case->id, 'stage' => 2]));
        $this->get(route('finish-case', ['caseId' => $case->id, 'stage' => 2]));
        $this->assertDatabaseHas('jobs', ['id' => $job->id, 'stage' => 6]); // Skips to finishing
        $this->assertDatabaseHas('case_logs', ['case_id' => $case->id, 'stage' => 2.3, 'is_completion' => 1]);

        // Stage 6: Finishing
        $this->get(route('assign-to-me', ['caseId' => $case->id, 'stage' => 6]));
        $this->get(route('finish-case', ['caseId' => $case->id, 'stage' => 6]));
        $this->assertDatabaseHas('jobs', ['id' => $job->id, 'stage' => 7]);
        $this->assertDatabaseHas('case_logs', ['case_id' => $case->id, 'stage' => 6, 'is_completion' => 1]);

        // Stage 7: QC
        $this->get(route('assign-to-me', ['caseId' => $case->id, 'stage' => 7]));
        $this->get(route('finish-case', ['caseId' => $case->id, 'stage' => 7]));
        $this->assertDatabaseHas('jobs', ['id' => $job->id, 'stage' => 8]);
        $this->assertDatabaseHas('case_logs', ['case_id' => $case->id, 'stage' => 7, 'is_completion' => 1]);

        // Stage 8: Delivery
        $this->get(route('assign-to-me', ['caseId' => $case->id, 'stage' => 8]));
        $this->get(route('finish-case', ['caseId' => $case->id, 'stage' => 8]));
        $this->assertDatabaseHas('jobs', ['id' => $job->id, 'stage' => -1]); // Finished
        $this->assertDatabaseHas('case_logs', ['case_id' => $case->id, 'stage' => 8.3, 'is_completion' => 1]);
    }
}
