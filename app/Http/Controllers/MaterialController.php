<?php
/**
 * User: Yazan
 * Date: 10/4/2021
 * Time: 8:36 PM
 */
namespace App\Http\Controllers;
use App\materialJobtype;
use Illuminate\Http\Request;
use App\material;
use App\JobType;


class MaterialController extends Controller
{
    public function index(){
        $materials = material::all();
        return view('material.index',compact("materials"));
    }

    public function returnCreate()
    {
        $jobTypes =  JobType::all();
        $types = \App\Type::enabled()->get();
        return view('material.create',compact('jobTypes', 'types'));
    }
    public function create(Request $request)
    {
        $this->validate($request, [
            'mat_name' => 'required|max:30',
            'price'    => 'required|numeric',
        ]);

        $material = new material();


            $material->name = $request->mat_name;
            $material->price = $request->price;
            $material->design = isset($request->design) ? 1 : 0;
            $material->mill = $request->manufacturing == 2  ? 1 : 0;
            $material->print_3d = $request->manufacturing == 3  ? 1 : 0;
            $material->sinter_furnace = $request->furnace == 4  ? 1 : 0;
            $material->press_furnace = $request->furnace == 5 ? 1 : 0;
            $material->finish = isset($request->finishing) ? 1 : 0;
            $material->qc = isset($request->qc) ? 1 : 0;
            $material->delivery = isset($request->delivery) ? 1 : 0;
        $material->count_as_unit = isset($request->count_as_unit) ? 1 : 0;
        $material->default_type_id = $request->default_type_id;
            $material->save();

        foreach($request->jobTypes as $jobType){
          $jt = new materialJobtype();
          $jt->material_id= $material->id;
          $jt->jobtype_id  = $jobType;
          $jt->save();
         }

        // Handle material types if provided
        if ($request->has('materialTypes') && is_array($request->materialTypes)) {
            $material->types()->sync($request->materialTypes);
        }

            return back()->with('success', 'Material has been successfully created');

    }
    public function returnUpdate($id)
    {
        $jobTypes =  JobType::all();
        $material = material::findOrFail($id);
        $matJobTypes =$material->jobtypes->pluck("jobtype_id")->toArray();
        $types = \App\Type::enabled()->get();
        $selectedTypes = $material->types->pluck('id')->toArray();

        return view('material.edit',compact('material','matJobTypes','jobTypes', 'types', 'selectedTypes'));
    }
    public function update(Request $request)
    {


        $material = material::where('id', $request->mat_id)->first();
        if (!$material) {
            return back()->with('Material Not found');
        }

            $material->name = $request->mat_name;
            $material->price = $request->price;
            $material->design = isset($request->design) ? 1 : 0;
            $material->mill = $request->manufacturing == 2  ? 1 : 0;
            $material->print_3d = $request->manufacturing == 3  ? 1 : 0;
            $material->sinter_furnace = $request->furnace == 4  ? 1 : 0;
            $material->press_furnace = $request->furnace == 5 ? 1 : 0;
            $material->finish = isset($request->finishing) ? 1 : 0;
            $material->qc = isset($request->qc) ? 1 : 0;
            $material->delivery = isset($request->delivery) ? 1 : 0;
            $material->count_as_unit = isset($request->count_as_unit) ? 1 : 0;
            $material->default_type_id = $request->default_type_id;
            $material->save();

        foreach($material->jobTypes as $jobTypeRelation){
            materialJobtype::findOrFail($jobTypeRelation->id)->delete();
        }

            if (isset($request->jobTypes))
            foreach($request->jobTypes as $jobType){
                $jt = new materialJobtype();
                $jt->material_id= $material->id;
                $jt->jobtype_id  = $jobType;
                $jt->save();
            }

            // Handle material types update
            if ($request->has('materialTypes') && is_array($request->materialTypes)) {
                $material->types()->sync($request->materialTypes);
            } else {
                $material->types()->detach(); // Remove all types if none selected
            }



            return redirect()->route('material-index')->with('success', 'Material has been successfully updated');

    }

    public function getTypes($id = null)
    {
        if ($id) {
            $material = material::find($id);
            if (!$material) {
                return response()->json(['error' => 'Material not found'], 404);
            }
            $types = $material->types()->enabled()->get(['id', 'name']);
        } else {
            $types = \App\Type::enabled()->get(['id', 'name']);
        }

        return response()->json($types);
    }

    public function getAllTypes()
    {
        try {
            $types = \App\Type::with('material:id,name')
                ->where('is_enabled', true)
                ->get(['id', 'name', 'material_id'])
                ->map(function($type) {
                    return [
                        'id' => $type->id,
                        'name' => $type->name,
                        'material_id' => $type->material_id,
                        'material_name' => $type->material ? $type->material->name : 'Unknown Material'
                    ];
                });

            return response()->json([
                'success' => true,
                'types' => $types
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting material types: ' . $e->getMessage()
            ], 500);
        }
    }


    public function createType(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'material_id' => 'required|integer|exists:materials,id'
            ]);

            // Check if type name already exists for this material
            $existingType = \App\Type::where('name', $request->name)
                ->where('material_id', $request->material_id)
                ->first();
            
            if ($existingType) {
                return response()->json([
                    'success' => false,
                    'message' => 'A material type with this name already exists for this material'
                ], 422);
            }

            // Create new material type
            $type = new \App\Type();
            $type->name = $request->name;
            $type->material_id = $request->material_id;
            $type->is_enabled = true;
            $type->save();

            // Return the created type with material relationship
            $type->load('material');

            return response()->json([
                'success' => true,
                'message' => 'Material type created successfully',
                'type' => [
                    'id' => $type->id,
                    'name' => $type->name,
                    'material_id' => $type->material_id,
                    'material' => $type->material
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating material type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMaterialTypesForCase(Request $request)
    {
        try {
            $materialId = $request->input('material_id');

            if (!$materialId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Material ID is required'
                ]);
            }

            // Try to find material by ID first, then by name if ID fails
            $material = material::find($materialId);

            if (!$material) {
                // Try finding by name in case the frontend is passing material name instead of ID
                $material = material::where('name', $materialId)->first();
            }

            if (!$material) {
                return response()->json([
                    'success' => false,
                    'message' => "Material not found for ID/name: {$materialId}"
                ]);
            }

            // Get all types for this material - specify table names to avoid ambiguity
            $allTypes = $material->types()->get(['types.id', 'types.name', 'types.is_enabled']);

            // Get the default type from the material's default_type_id column
            $defaultType = null;
            if ($material->default_type_id) {
                $defaultType = $allTypes->firstWhere('id', $material->default_type_id);
            }

            return response()->json([
                'success' => true,
                'types' => $allTypes->map(function($type) {
                    return [
                        'id' => $type->id,
                        'name' => $type->name,
                        'is_enabled' => $type->is_enabled
                    ];
                }),
                'default_type_id' => $material->default_type_id,
                'default_type' => $defaultType ? [
                    'id' => $defaultType->id,
                    'name' => $defaultType->name,
                    'is_enabled' => $defaultType->is_enabled
                ] : null,
                'material_id' => $materialId,
                'material_name' => $material->name
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching material types: ' . $e->getMessage()
            ], 500);
        }
    }
}