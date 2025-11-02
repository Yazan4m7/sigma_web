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

class CaseCreationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_new_case_with_jobs_and_material_types()
    {
        // 1. Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        $client = client::create(['name' => 'Test Doctor']);
        $material1 = material::create(['name' => 'Zirconia', 'price' => 100]);
        $material2 = material::create(['name' => 'PMMA', 'price' => 50]);
        $type1 = Type::create(['name' => 'Full Contour', 'material_id' => $material1->id]);
        $type2 = Type::create(['name' => 'Temporary Crown', 'material_id' => $material2->id]);

        // 2. Act
        $response = $this->post(route('new-case-post'), [
            'doctor' => $client->id,
            'patient_name' => 'New Patient',
            'caseId1' => 'newcase',
            'caseId2' => '45',
            'caseId3' => '6' ,
            'caseId4' => '',
            'delivery_date' => now()->addWeek()->format('Y-m-d'),
            'impression_type' => 1,

            'repeat' => [
                [
                    'units' => '11,12',
                    'jobType' => 1,
                    'material_id' => $material1->id,
                    'type_id' => $type1->id,
                    'color' => 'A1',
                    'style' => 'Bridge',
                ],
                [
                    'units' => '21',
                    'jobType' => 1,
                    'material_id' => $material2->id,
                    'type_id' => $type2->id,
                    'color' => 'A2',
                    'style' => 'Single',
                ]
            ],
        ]);

        // 3. Assert
        $response->assertRedirect('/operations-dashboard');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('s_cases', [
            'patient_name' => 'New Patient',
            'doctor_id' => $client->id,
        ]);

        $this->assertDatabaseHas('jobs', [
            'unit_num' => '11,12',
            'material_id' => $material1->id,
            'type_id' => $type1->id,
        ]);

        $this->assertDatabaseHas('jobs', [
            'unit_num' => '21',
            'material_id' => $material2->id,
            'type_id' => $type2->id,
        ]);
    }
}
