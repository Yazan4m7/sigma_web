<?php
/**
 * User: Yazan
 * Date: 10/4/2021
 * Time: 8:36 PM
 */
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\JobType;
use App\material;
use App\materialJobtype;
class JobTypeController extends Controller
{
    public function index(){
        $jobTypes = JobType::all();
        return view('jobType.index',compact("jobTypes"));
    }

    public function returnCreate()
    {
       return view('jobType.create');
    }
    public function create(Request $request)
    {
        $this->validate($request, [
            'jobtype_name'     => 'required|max:30',
            'teeth_or_jaw' =>'required|numeric'
        ]);

        $jobType = new jobtype();

        try {
            $jobType->name = $request->jobtype_name;
            $jobType->teeth_or_jaw = $request->teeth_or_jaw;

            $jobType->save();

            return back()->with('success', 'Job Type has been successfully created');
        } catch (Exception $e) {
            return back()->with('error', $e);
        }
    }
    public function returnUpdate($id)
    {

        $jobType = JobType::findOrFail($id);

        $allowedMaterialIds = materialJobtype::where('jobtype_id', $jobType->id)
            ->pluck('material_id');

        $allowedMaterials = material::whereIn('id', $allowedMaterialIds)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('jobType.edit', compact('jobType', 'allowedMaterials'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'jobtype_id' => 'required|integer|exists:job_types,id',
            'jobtype_name' => 'required|max:30',
            'teeth_or_jaw' => 'required|numeric',
            'default_material_id' => 'nullable|integer|exists:materials,id',
        ]);

        try {
        $jobType = JobType::where('id', $request->jobtype_id)->first();
        if (!$jobType) {
            return back()->with('error', 'Job Type Not found');
        }

        $defaultMaterialId = $request->filled('default_material_id')
            ? (int) $request->input('default_material_id')
            : null;

        if ($defaultMaterialId !== null) {
            $isLinked = materialJobtype::where('jobtype_id', $jobType->id)
                ->where('material_id', $defaultMaterialId)
                ->exists();

            if (!$isLinked) {
                return back()->with('error', 'Default material must be linked to this job type.');
            }
        }

        $jobType->name = $request->jobtype_name;
        $jobType->teeth_or_jaw = $request->teeth_or_jaw;
        $jobType->default_material_id = $defaultMaterialId;
        $jobType->save();


        return back()->with('success', 'Job Type has been successfully updated');
    } catch (Exception $e) {
        return back()->with('error', $e);
}
    }
}
