<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Material;
use App\JobType;

class MaterialJobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if relationships already exist
        $existingCount = DB::table('material_jobtypes')->count();
        if ($existingCount > 0) {
            echo "Found {$existingCount} existing relationships. Skipping seeding.\n";
            return;
        }

        // Get all materials and job types
        $materials = Material::all();
        $jobTypes = JobType::all();

        // Define logical relationships between materials and job types
        $relationships = [
            // Crown compatible materials
            'Crown' => [
                'Zirconia', 'E-max', 'Metal', 'PFM', 'Composite', 'PMMA', 'Ceramic'
            ],
            // Bridge compatible materials  
            'Bridge' => [
                'Zirconia', 'E-max', 'Metal', 'PFM', 'Ceramic'
            ],
            // Abutment compatible materials
            'Abutment' => [
                'Titanium', 'Zirconia', 'PEEK', 'Metal'
            ],
            // Implant compatible materials
            'Implant' => [
                'Titanium', 'Zirconia'
            ],
            // Inlay/Onlay compatible materials
            'Inlay' => [
                'E-max', 'Composite', 'Ceramic', 'Zirconia'
            ],
            'Onlay' => [
                'E-max', 'Composite', 'Ceramic', 'Zirconia'
            ],
            // Veneer compatible materials
            'Veneer' => [
                'E-max', 'Composite', 'Ceramic'
            ],
            // Denture compatible materials
            'Partial Denture' => [
                'PMMA', 'Metal', 'Composite'
            ],
            'Full Denture' => [
                'PMMA', 'Composite'
            ],
            // Orthodontic compatible materials
            'Retainer' => [
                'PMMA', 'PETG'
            ],
            'Night Guard' => [
                'PMMA', 'EVA'
            ],
            // Surgical guide materials
            'Surgical Guide' => [
                'PMMA', 'Resin'
            ],
            // Temporary materials
            'Temporary Crown' => [
                'PMMA', 'Composite', 'Resin'
            ],
            'Temporary Bridge' => [
                'PMMA', 'Composite', 'Resin'
            ]
        ];

        $insertData = [];
        
        foreach ($relationships as $jobTypeName => $materialNames) {
            // Find the job type
            $jobType = $jobTypes->where('name', $jobTypeName)->first();
            if (!$jobType) {
                // Try partial matching for job types
                $jobType = $jobTypes->filter(function($jt) use ($jobTypeName) {
                    return stripos($jt->name, $jobTypeName) !== false || stripos($jobTypeName, $jt->name) !== false;
                })->first();
            }
            
            if ($jobType) {
                foreach ($materialNames as $materialName) {
                    // Find the material
                    $material = $materials->where('name', $materialName)->first();
                    if (!$material) {
                        // Try partial matching for materials
                        $material = $materials->filter(function($m) use ($materialName) {
                            return stripos($m->name, $materialName) !== false || stripos($materialName, $m->name) !== false;
                        })->first();
                    }
                    
                    if ($material) {
                        $insertData[] = [
                            'material_id' => $material->id,
                            'jobtype_id' => $jobType->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }
            }
        }

        // If no specific matches found, create some basic relationships for existing data
        if (empty($insertData)) {
            echo "No specific matches found, creating basic relationships...\n";
            
            // Get first few materials and job types and create basic relationships
            $firstMaterials = $materials->take(5);
            $firstJobTypes = $jobTypes->take(5);
            
            foreach ($firstJobTypes as $jobType) {
                foreach ($firstMaterials as $material) {
                    $insertData[] = [
                        'material_id' => $material->id,
                        'jobtype_id' => $jobType->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
        }

        // Insert the relationships
        if (!empty($insertData)) {
            DB::table('material_jobtypes')->insert($insertData);
            echo "Inserted " . count($insertData) . " material-jobtype relationships.\n";
        } else {
            echo "No relationships to insert.\n";
        }

        // Display summary
        echo "Materials found: " . $materials->count() . "\n";
        echo "Job Types found: " . $jobTypes->count() . "\n";
        echo "Relationships created: " . count($insertData) . "\n";
    }
}