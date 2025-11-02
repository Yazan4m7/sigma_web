<?php

namespace Tests\Unit;

use App\Http\Controllers\TypeController;
use App\material;
use App\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class TypeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_the_correct_types_for_a_given_material()
    {
        // 1. Arrange
        $material1 = material::create(['name' => 'Zirconia', 'price' => 100]);
        $material2 = material::create(['name' => 'PMMA', 'price' => 50]);

        $type1 = Type::create(['name' => 'Full Contour', 'material_id' => $material1->id]);
        $type2 = Type::create(['name' => 'Layered', 'material_id' => $material1->id]);
        $type3 = Type::create(['name' => 'Temporary Crown', 'material_id' => $material2->id]);

        $controller = new TypeController();

        // 2. Act
        $response = $controller->getTypesByMaterial($material1->id);

        // 3. Assert
        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertCount(2, $content);
        $this->assertEquals($type1->name, $content[0]['name']);
        $this->assertEquals($type2->name, $content[1]['name']);
    }
}
