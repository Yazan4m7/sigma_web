<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\JobType; // Assuming you have a JobType model. Adjust if namespace is different.
use App\Models\User; // Or your User model

class JobTypesReportTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // It's highly recommended to use a specific seeder for your tests
        // to ensure a consistent state.
        // $this->seed(YourMainSeeder::class);

        // You might need to authenticate a user to access the report page.
        // $user = User::factory()->create();
        // $this->actingAs($user);
    }

    /** @test */
    public function it_loads_the_job_types_report_without_filters()
    {
        // First, ensure some data exists to be loaded
        JobType::factory()->count(5)->create();

        $response = $this->get(route('job-types-report'));

        $response->assertStatus(200);
        $response->assertViewIs('reports.jobTypes');
        $response->assertViewHas('clients');
        $response->assertViewHas('jobTypes');
        $response->assertViewHas('selectedJobTypes');
    }

    /** @test */
    public function it_filters_by_a_specific_job_type()
    {
        // Create a few job types
        $jobTypeToFilter = JobType::factory()->create(['name' => 'Crown']);
        $otherJobType = JobType::factory()->create(['name' => 'Veneer']);

        // Assuming your client model has a method to calculate units/cases
        // and you have cases/jobs seeded that link to these job types.
        // The logic here is simplified to just checking the selected types.

        $response = $this->get(route('job-types-report', ['jobTypesInput' => [$jobTypeToFilter->id]]));

        $response->assertStatus(200);
        $response->assertViewIs('reports.jobTypes');

        // Assert that only the 'Crown' job type is in the selected collection
        $response->assertViewHas('selectedJobTypes', function ($selectedJobTypes) use ($jobTypeToFilter) {
            return $selectedJobTypes->count() === 1 && $selectedJobTypes->first()->id === $jobTypeToFilter->id;
        });

        // A more advanced test would assert the calculated totals.
        // For example:
        // $response->assertViewHas('grandTotals', function ($grandTotals) use ($jobTypeToFilter) {
        //     // Logic to check if the totals for $jobTypeToFilter->id are correct
        //     return $grandTotals[$jobTypeToFilter->id] === YOUR_EXPECTED_TOTAL;
        // });
    }
}
