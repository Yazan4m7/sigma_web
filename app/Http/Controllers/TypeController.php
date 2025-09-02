<?php

namespace App\Http\Controllers;

use App\Type;
use App\material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    /**
     * Display a listing of types
     */
    public function index()
    {
        $types = Type::with('material')->orderBy('material_id')->orderBy('name')->get();
        $materials = material::orderBy('name')->get();
        
        return view('admin.types.index', compact('types', 'materials'));
    }

    /**
     * Show the form for creating a new type
     */
    public function create()
    {
        $materials = material::orderBy('name')->get();
        return view('admin.types.create', compact('materials'));
    }

    /**
     * Store a newly created type
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'material_id' => 'required|exists:materials,id',
            'is_enabled' => 'boolean',
        ]);

        if ($validator->fails()) {
            // Return JSON for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $type = Type::create([
            'name' => $request->name,
            'description' => $request->description,
            'material_id' => $request->material_id,
            'is_enabled' => $request->has('is_enabled') ? true : false,
        ]);

        // Return JSON for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Type created successfully.',
                'type' => $type->load('material')
            ]);
        }

        return redirect()->route('types.index')
            ->with('success', 'Type created successfully.');
    }

    /**
     * Show the form for editing a type
     */
    public function edit(Type $type)
    {
        $materials = material::orderBy('name')->get();
        return view('admin.types.edit', compact('type', 'materials'));
    }

    /**
     * Update the specified type
     */
    public function update(Request $request, Type $type)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'material_id' => 'required|exists:materials,id',
            'is_enabled' => 'boolean',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = ['name' => $request->name];
        
        if ($request->has('description')) {
            $updateData['description'] = $request->description;
        }
        
        if ($request->has('material_id')) {
            $updateData['material_id'] = $request->material_id;
        }
        
        if ($request->has('is_enabled')) {
            $updateData['is_enabled'] = $request->has('is_enabled') ? true : false;
        }

        $type->update($updateData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Type updated successfully',
                'type' => $type
            ]);
        }

        return redirect()->route('types.index')
            ->with('success', 'Type updated successfully.');
    }

    /**
     * Remove the specified type
     */
    public function destroy(Request $request, Type $type)
    {
        $type->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Type deleted successfully'
            ]);
        }
        
        return redirect()->route('types.index')
            ->with('success', 'Type deleted successfully.');
    }

    /**
     * Toggle enable/disable status of a type
     */
    public function toggleStatus(Type $type)
    {
        $type->update([
            'is_enabled' => !$type->is_enabled
        ]);

        $status = $type->is_enabled ? 'enabled' : 'disabled';
        
        return redirect()->route('types.index')
            ->with('success', "Type {$status} successfully.");
    }

    /**
     * API endpoint to get enabled types by material ID
     */
    public function getTypesByMaterial($materialId)
    {
        $types = Type::where('material_id', $materialId)
            ->enabled()
            ->orderBy('name')
            ->get();
        return response()->json($types);
    }
}