<?php

namespace Tests\Feature;

use App\client;
use App\sCase;
use App\job;
use App\material;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_correct_number_of_units_in_the_report_with_filters()
    {
        // 1. Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        $doctor1 = client::create(['name' => 'Dr. Smith']);
        $doctor2 = client::create(['name' => 'Dr. Jones']);

        $material1 = material::create(['name' => 'Zirconia', 'price' => 100]);
        $material2 = material::create(['name' => 'PMMA', 'price' => 50]);

        // Cases for Dr. Smith
        $case1 = sCase::create([
            'patient_name' => 'Patient A',
            'doctor_id' => $doctor1->id,
            'case_id' => 'case1',
            'initial_delivery_date' => now(),
            'actual_delivery_date' => now(),
        ]);
        job::create([
            'case_id' => $case1->id,
            'type' => 1,
            'material_id' => $material1->id,
            'unit_num' => '11,12', // 2 units
        ]);

        // Cases for Dr. Jones
        $case2 = sCase::create([
            'patient_name' => 'Patient B',
            'doctor_id' => $doctor2->id,
            'case_id' => 'case2',
            'initial_delivery_date' => now(),
            'actual_delivery_date' => now(),
        ]);
        job::create([
            'case_id' => $case2->id,
            'type' => 1,
            'material_id' => $material2->id,
            'unit_num' => '21,22,23', // 3 units
        ]);

        // Case from last month (should be excluded by date filter)
        $case3 = sCase::create([
            'patient_name' => 'Patient C',
            'doctor_id' => $doctor1->id,
            'case_id' => 'case3',
            'initial_delivery_date' => now()->subMonth(),
            'actual_delivery_date' => now()->subMonth(),
        ]);
        job::create([
            'case_id' => $case3->id,
            'type' => 1,
            'material_id' => $material1->id,
            'unit_num' => '31', // 1 unit
        ]);

        // 2. Act
        $response = $this->get(route('num-of-units-report', [
            'from' => now()->startOfMonth()->format('Y-m-d'),
            'to' => now()->endOfMonth()->format('Y-m-d'),
            'doctor' => [$doctor1->id],
            'material' => [$material1->id],
        ]));

        // 3. Assert
        $response->assertStatus(200);
        $response->assertSee('Dr. Smith');
        $response->assertDontSee('Dr. Jones');
        $response->assertSee('Zirconia');
        $response->assertDontSee('PMMA');

        // This is a bit brittle, but we can check for the expected unit count in the HTML.
        // A better approach would be to have a dedicated API endpoint for the report data.
        // For now, we will check if the view contains the expected values.
        $response->assertSeeInOrder(['Dr. Smith', '2']); // 2 units of Zirconia for Dr. Smith
    }
}
