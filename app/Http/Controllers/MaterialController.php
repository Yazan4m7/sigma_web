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
        $types = \App\Type::with('material:id,name')
            ->enabled()
            ->whereHas('material')
            ->get();
        return view('material.create',compact('jobTypes', 'types'));
    }
    public function create(Request $request)
    {
        $this->validate($request, [
            'name'     => 'mat_name|max:30',
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
            $material->save();

        foreach($request->jobTypes as $jobType){
          $jt = new materialJobtype();
          $jt->material_id= $material->id;
          $jt->jobtype_id  = $jobType;
          $jt->save();
         }

        // Handle material types if provided
        if ($request->has('materialTypes') && is_array($request->materialTypes)) {
            foreach($request->materialTypes as $typeId) {
                // Create a relationship between this material and the selected types
                // This would typically be done through a pivot table if needed
                // For now, we'll just ensure the types exist and are linked to materials
                $type = \App\Type::find($typeId);
                if ($type) {
                    // You could add additional logic here if needed
                    // For example, creating a material-type relationship table
                }
            }
        }

            return back()->with('success', 'Material has been successfully created');

    }
    public function returnUpdate($id)
    {
        $jobTypes =  JobType::all();
        $material = material::findOrFail($id);
        $matJobTypes =$material->jobtypes->pluck("jobtype_id")->toArray();
        $types = \App\Type::with('material:id,name')
            ->enabled()
            ->whereHas('material')
            ->get();

        return view('material.edit',compact('material','matJobTypes','jobTypes', 'types'));
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

            return back()->with('success', 'Material has been successfully updated');

    }

    public function getTypes($id)
    {
        $material = material::find($id);
        if (!$material) {
            return response()->json(['error' => 'Material not found'], 404);
        }

        $types = \App\Type::where('material_id', $id)
            ->enabled()
            ->get(['id', 'name']);

        return response()->json($types);
    }
}