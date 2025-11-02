<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            // Zirconia Types (material_id: 1)
            ['name' => 'Full Contour', 'description' => 'Full contour zirconia crown', 'material_id' => 1],
            ['name' => 'Layered', 'description' => 'Layered zirconia with porcelain', 'material_id' => 1],
            ['name' => 'Monolithic', 'description' => 'Monolithic zirconia crown', 'material_id' => 1],
            
            // PMMA Types (material_id: 2)
            ['name' => 'Temporary Crown', 'description' => 'Temporary crown made from PMMA', 'material_id' => 2],
            ['name' => 'Surgical Guide', 'description' => 'PMMA surgical guide', 'material_id' => 2],
            ['name' => 'Try-in', 'description' => 'PMMA try-in prosthetic', 'material_id' => 2],
            
            // Lithium Disilicate Types (material_id: 3)
            ['name' => 'Pressed', 'description' => 'Pressed lithium disilicate crown', 'material_id' => 3],
            ['name' => 'CAD/CAM', 'description' => 'CAD/CAM milled lithium disilicate', 'material_id' => 3],
            ['name' => 'Stained', 'description' => 'Stained lithium disilicate', 'material_id' => 3],
            
            // Metal Types (material_id: 4)
            ['name' => 'Cast', 'description' => 'Cast metal crown/bridge', 'material_id' => 4],
            ['name' => 'Milled', 'description' => 'CNC milled metal', 'material_id' => 4],
            ['name' => '3D Printed', 'description' => '3D printed metal prosthetic', 'material_id' => 4],
        ];

        foreach ($types as $type) {
            \App\Type::create($type);
        }
    }
}
