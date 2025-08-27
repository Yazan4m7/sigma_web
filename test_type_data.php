<?php
/**
 * Simple script to test if Type data exists in jobs
 */

require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\job;

try {
    echo "Testing Type data in jobs...\n\n";
    
    // Get total job count
    $totalJobs = job::count();
    echo "Total jobs in database: {$totalJobs}\n";
    
    // Get jobs with type_id
    $jobsWithTypeId = job::whereNotNull('type_id')->count();
    echo "Jobs with type_id: {$jobsWithTypeId}\n";
    
    // Get jobs with valid type relationship
    $jobsWithValidType = job::whereHas('subType')->count();
    echo "Jobs with valid subType relationship: {$jobsWithValidType}\n\n";
    
    // Sample jobs with type data
    echo "Sample jobs with Type data:\n";
    $sampleJobs = job::with(['jobType', 'material', 'subType'])
        ->whereHas('subType')
        ->limit(5)
        ->get();
    
    foreach ($sampleJobs as $job) {
        echo "Job ID: {$job->id}\n";
        echo "  JobType: " . ($job->jobType ? $job->jobType->name : 'N/A') . "\n";
        echo "  Material: " . ($job->material ? $job->material->name : 'N/A') . "\n";
        echo "  Type: " . ($job->subType ? $job->subType->name : 'N/A') . "\n";
        echo "  type_id: " . ($job->type_id ?? 'NULL') . "\n\n";
    }
    
    // Check Type table
    echo "Types in database:\n";
    $types = DB::table('types')->get();
    echo "Total types: " . $types->count() . "\n";
    foreach ($types->take(5) as $type) {
        echo "  ID: {$type->id}, Name: {$type->name}, Material ID: {$type->material_id}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}