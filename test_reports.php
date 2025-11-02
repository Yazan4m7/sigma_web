<?php
// SIGMA Reports Testing Script
// Run this to test report functionality

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\sCase;
use App\job;
use App\client;
use App\material;
use App\implant;
use App\abutment;
use App\JobType;
use App\failureLog;
use App\failureCause;
use Carbon\Carbon;

echo "🔬 SIGMA Reports Testing Script\n";
echo "================================\n\n";

try {
    // 1. Test Number of Units Report Logic
    echo "📊 Testing Number of Units Report\n";
    echo "---------------------------------\n";
    
    // Get a sample client
    $client = client::first();
    if ($client) {
        echo "Testing client: {$client->name}\n";
        
        // Test for current month
        $currentMonth = Carbon::now()->format('Y-m');
        $materials = material::limit(3)->get();
        
        foreach ($materials as $material) {
            $units = $client->numOfUnitsByMaterial($material->id, $currentMonth);
            echo "- {$material->name}: {$units} units\n";
        }
    }
    echo "\n";

    // 2. Test Repeats Report Logic
    echo "🔄 Testing Repeats Report\n";
    echo "-------------------------\n";
    
    if ($client) {
        $failureTypes = [0 => "Rejection", 1 => "Repeat", 2 => "Modification", 3 => "Redo", 4 => "Successful"];
        
        foreach ($failureTypes as $type => $name) {
            $unitsCount = $client->getFailedUnitsCount($currentMonth, $type);
            $casesCount = $client->getFailedCasesCount($currentMonth, $type);
            $unitsPercentage = $client->getFailedUnitsPercentage($currentMonth, $type);
            $casesPercentage = $client->getFailedCasesPercentage($currentMonth, $type);
            
            echo "- {$name}: Units({$unitsCount}) Cases({$casesCount}) Units%({$unitsPercentage}%) Cases%({$casesPercentage}%)\n";
        }
    }
    echo "\n";

    // 3. Test Basic Counts
    echo "📈 Database Overview\n";
    echo "-------------------\n";
    echo "Cases: " . sCase::count() . "\n";
    echo "Jobs: " . job::count() . "\n";
    echo "Clients: " . client::count() . "\n";
    echo "Materials: " . material::count() . "\n";
    echo "Implants: " . implant::count() . "\n";
    echo "Abutments: " . abutment::count() . "\n";
    echo "Job Types: " . JobType::count() . "\n";
    echo "Failure Logs: " . failureLog::count() . "\n";
    echo "Failure Causes: " . failureCause::count() . "\n";
    echo "\n";

    // 4. Test Recent Data
    echo "📅 Recent Activity (Last 30 Days)\n";
    echo "----------------------------------\n";
    $recentCases = sCase::where('actual_delivery_date', '>=', Carbon::now()->subDays(30))->count();
    $recentJobs = job::whereHas('case', function($q) {
        $q->where('actual_delivery_date', '>=', Carbon::now()->subDays(30));
    })->count();
    
    echo "Recent Cases: {$recentCases}\n";
    echo "Recent Jobs: {$recentJobs}\n";
    echo "\n";

    // 5. Test Sample Case Creation (optional - commented out to avoid data pollution)
    /*
    echo "🏗️ Creating Sample Test Data\n";
    echo "-----------------------------\n";
    
    // Create sample client
    $testClient = client::create([
        'name' => 'Dr. Test Example',
        'email' => 'test@example.com',
        'phone' => '123-456-7890'
    ]);
    
    // Create sample case
    $testCase = sCase::create([
        'doctor_id' => $testClient->id,
        'patient_name' => 'Test Patient',
        'actual_delivery_date' => Carbon::now()->format('Y-m-d'),
    ]);
    
    // Create sample jobs
    $materials = material::limit(2)->get();
    foreach ($materials as $material) {
        job::create([
            'case_id' => $testCase->id,
            'material_id' => $material->id,
            'unit_num' => '1,2,3',  // 3 units
            'type' => 1,  // Crown
            'is_rejection' => 0,
            'is_repeat' => 0,
            'is_modification' => 0,
            'is_redo' => 0,
        ]);
    }
    
    echo "✅ Created test client: {$testClient->name}\n";
    echo "✅ Created test case: {$testCase->id}\n";
    echo "✅ Created test jobs: " . job::where('case_id', $testCase->id)->count() . "\n";
    */
    
    echo "✅ Report testing completed successfully!\n";

} catch (Exception $e) {
    echo "❌ Error during testing: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>