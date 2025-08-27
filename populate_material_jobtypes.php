<?php
/**
 * Simple script to populate material_jobtypes table
 * Run this with: php populate_material_jobtypes.php
 */

require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Checking database connection...\n";
    
    // Test connection
    DB::connection()->getPdo();
    echo "Database connected successfully!\n";
    
    // Check current state
    $existingCount = DB::table('material_jobtypes')->count();
    echo "Current material_jobtypes relationships: {$existingCount}\n";
    
    if ($existingCount > 0) {
        echo "Relationships already exist. To re-populate, run: DELETE FROM material_jobtypes;\n";
        exit;
    }
    
    // Get materials and job types
    $materials = DB::table('materials')->get();
    $jobTypes = DB::table('job_types')->get();
    
    echo "Found {$materials->count()} materials and {$jobTypes->count()} job types\n";
    
    // Create basic relationships (every material compatible with every job type for now)
    $insertData = [];
    
    foreach ($jobTypes as $jobType) {
        foreach ($materials as $material) {
            $insertData[] = [
                'material_id' => $material->id,
                'jobtype_id' => $jobType->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
    }
    
    if (!empty($insertData)) {
        // Insert in chunks to avoid memory issues
        $chunks = array_chunk($insertData, 100);
        $totalInserted = 0;
        
        foreach ($chunks as $chunk) {
            DB::table('material_jobtypes')->insert($chunk);
            $totalInserted += count($chunk);
            echo "Inserted {$totalInserted} relationships...\n";
        }
        
        echo "Successfully created {$totalInserted} material-jobtype relationships!\n";
        echo "Now the material dropdowns should work when selecting job types.\n";
    } else {
        echo "No data to insert.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Make sure your database is running and properly configured.\n";
}