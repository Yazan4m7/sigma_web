<?php

namespace App\Http\Controllers;
use App\device;
use App\file;
use Exception;
use Illuminate\Http\Request;
use App\abutment;
use DB;


class DevicesController extends Controller
{

    public function index(){
        $devices = device::all();
        return view('devices.index',compact("devices"));
    }


    public function returnCreate()
    {
        return view('devices.create');
    }
    public function create(Request $request)
    {
        $this->validate($request, [
            'device_name'     => 'required|max:50',

        ]);
    //    dd($request);

        $device = new device();

        try {
            $device->name = $request->device_name;
            $device->type = $request->device_type;
            $device->sorting_order = 0;
            $device->save();

            if ($request->hasFile('device_image')) {

                $file = $request->file('device_image');
                    $name = $file->getClientOriginalName();
                    $file->move('devicesImages/' . $device->id . '/', $name);
                    $newFile = new file();
                    $newFile->path = 'devicesImages/' . $device->id . '/' . $name;
                    $newFile->case_id = $device->id;
                    $newFile->added_by = Auth()->user()->id;
                    $newFile->save();
                $device->img =  $newFile->path;
                $device->save();

            }
            return redirect('/device/index')->with('success', 'Device has been successfully created');
        } catch (Exception $e) {
            return back()->with('error', $e);
        }
    }
    public function returnUpdate($id)
    {

        $device = device::findOrFail($id);
        $devices_of_same_type = device::where('type', $device->type)->orderBy('sorting_order')->get();
        return view('devices.edit2',compact('device', 'devices_of_same_type'));
    }
    public function update(Request $request)
    {
       // dd($request);
        try {
            $device = device::where('id', $request->device_id)->first();
            if (!$device) {
//                dd("$--device22"  .  $device);
                return back()->with('Device Not found');
            }
            $device->name = $request->device_name;
            $device->type = $request->device_type;
            $device->save();
            if ($request->hasFile('device_image')) {
                try {
                    $file = $request->file('device_image');
                    
                    // Validate file
                    $request->validate([
                        'device_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                    ]);
                    
                    // Create directory if it doesn't exist
                    $directory = public_path('devicesImages/' . $device->id);
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    
                    // Generate unique filename to avoid conflicts
                    $extension = $file->getClientOriginalExtension();
                    $filename = 'device_' . $device->id . '_' . time() . '.' . $extension;
                    
                    // Move file to destination
                    $file->move($directory, $filename);
                    
                    // Delete old file record if exists
                    $oldFile = file::where('case_id', $device->id)->where('path', 'LIKE', 'devicesImages/' . $device->id . '/%')->first();
                    if ($oldFile) {
                        // Delete old physical file
                        $oldFilePath = public_path($oldFile->path);
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                        $oldFile->delete();
                    }
                    
                    // Create new file record
                    $newFile = new file();
                    $newFile->path = 'devicesImages/' . $device->id . '/' . $filename;
                    $newFile->case_id = $device->id;
                    $newFile->added_by = Auth()->user()->id;
                    $newFile->save();
                    
                    // Update device image path
                    $device->img = $newFile->path;
                    $device->save();
                    
                } catch (\Exception $imageException) {
                    // Log the specific image upload error but continue with device update
                    \Log::error('Device image upload failed: ' . $imageException->getMessage());
                    return back()->with('warning', 'Device updated successfully but image upload failed: ' . $imageException->getMessage());
                }
            }

           //s dd("======"  .  $device);
          //  dd("Device has been successfully updated");
            return redirect('/device/index')->with('success', 'Device has been successfully updated');
        } catch (Exception $e) {
        //    dd($e);
            return back()->with('error', $e);
        }
    }

    public function toggleVisibility($id)
    {
        try {
            $device = device::where('id', $id)->first();
            if (!$device) {
                return back()->with('Implant Not found');
            }
            if($device->hidden == 1)
                $device->hidden = 0;
            else
                $device->hidden = 1;

            $device->save();


            return back()->with('success', 'Device updated successfully');
        } catch (Exception $e) {
            return back()->with('error', $e);
        }
    }
    public function delete($id){
        device::where('id', $id)->first()->delete();
        return back()->with('success', 'Device deleted successfully');
    }

    public function softDelete($id){
        try {
            $device = device::where('id', $id)->first();
            if (!$device) {
                return back()->with('error', 'Device not found');
            }
            $device->delete();
            return back()->with('success', 'Device deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function updateDeviceOrder(Request $request)
    {
        $deviceIds = $request->input('device_ids');

        foreach ($deviceIds as $index => $deviceId) {
            device::where('id', $deviceId)->update(['sorting_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function getDevicesByType($type)
    {
        $devices = device::where('type', $type)->orderBy('sorting_order')->get();
        return response()->json($devices);
    }

}
