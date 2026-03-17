<?php

namespace Tests\Feature;

use App\client;
use App\sCase;
use App\job;
use App\material;
use App\Type;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CaseEditTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_update_an_existing_job_and_add_a_new_job_with_material_types()
    {
        // 1. Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        $client = client::create(['name' => 'Test Doctor']);
        $material = material::create(['name' => 'Zirconia', 'price' => 100]);
        $type1 = Type::create(['name' => 'Full Contour', 'material_id' => $material->id]);
        $type2 = Type::create(['name' => 'Layered', 'material_id' => $material->id]);

        $case = sCase::create([
            'patient_name' => 'Test Patient',
            'doctor_id' => $client->id,
            'case_id' => 'testcase123',
            'initial_delivery_date' => now()->addWeek(),
        ]);

        $existingJob = job::create([
            'case_id' => $case->id,
            'type' => 1, // JobType ID
            'material_id' => $material->id,
            'unit_num' => '11',
        ]);

        // 2. Act
        $response = $this->post(route('edit-case'), [
            'id' => $case->id,
            'doctor' => $client->id,
            'patient_name' => 'Test Patient Updated',
            'caseId1' => 'testcase',
            'caseId2' => '12',
            'caseId3' => '3',
            'caseId4' => '',
            'delivery_date' => now()->addWeek()->format('Y-m-d'),

            // Update existing job
            'repeat' => [
                [
                    'job_id' => $existingJob->id,
                    'units' . $existingJob->id => '11,12',
                    'jobType' . $existingJob->id => 1,
                    'material_id' . $existingJob->id => $material->id,
                    'type_id' . $existingJob->id => $type1->id,
                    'color' . $existingJob->id => 'A1',
                    'style' . $existingJob->id => 'Single',
                ]
            ],

            // Add new job
            'repeat2' => [
                [
                    'units' => '21',
                    'jobType' => 1,
                    'material_id' => $material->id,
                    'type_id' => $type2->id,
                    'color' => 'A2',
                    'style' => 'Single',
                ]
            ],
        ]);

        // 3. Assert
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('jobs', [
            'id' => $existingJob->id,
            'unit_num' => '11,12',
            'type_id' => $type1->id,
        ]);

        $this->assertDatabaseHas('jobs', [
            'case_id' => $case->id,
            'unit_num' => '21',
            'type_id' => $type2->id,
        ]);
    }
}
