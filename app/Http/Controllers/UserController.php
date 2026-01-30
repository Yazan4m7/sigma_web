<?php /** @noinspection IssetArgumentExistenceInspection */

namespace App\Http\Controllers;

use App\GalleryMedia;
use App\Http\Traits\helperTrait;
use Illuminate\Http\Request;
use App\User;
use App\UserPermission;
use App\Permission;
use DB;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use helperTrait;

    public function impersonate($userId)
    {
        // optional: check if current user is admin
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        // store the admin’s ID so you can return later
        session(['impersonator_id' => auth()->id()]);

        // log in as the target user
        Auth::loginUsingId($userId);

        return redirect('/home'); // Redirect to home after impersonation
    }

    public function stopImpersonate()
    {
        // Check if currently impersonating
        if (!session()->has('impersonator_id')) {
            abort(403, 'Not currently impersonating');
        }

        $adminId = session('impersonator_id');
        session()->forget('impersonator_id');
        Auth::loginUsingId($adminId);

        return redirect('/')->with('success', 'Returned to admin account');
    }


    public function index(Request $request)
    {

        $status = $request->status != null ? $request->status : 1;
        if($status == 0){
            if(isset($users))
                $users = $users->where('status', 0);
            else
                $users = User::where('status', 0);
        }
        if($status == 1) {
            if(isset($users))
                $users = $users->where('status', 1);
            else
                $users = User::where('status', 1);

        }

        $users = $users->paginate(20)->appends(['status' => $status, 'search' => $request->search]);

return view('users.index')->with('users', $users)->with('status',$status)->with('search', $request->search);
    }

        public function returnCreate()
    {
        $permissions = Permission::all();
        return view('users.create')->with('permissions', $permissions);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users,username',
            'first_name'     => 'required',
            'last_name'    => 'required',
            'password' => 'required|confirmed|min:1',
            'password_confirmation' => 'required',
            'phone'    => 'required',
            'permission' => 'required_if:is_admin,null',
            'permission.*' => 'exists:permissions,id',

                'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:12288', // 12MB input
        'driver_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $transaction = DB::transaction(function ()  use ($request) {
            $admin = $request->is_admin ? 1 : 0;
            $hasPhoto = $request->hasFile('photo') ? 1 : 0;

            // Prepare data for user creation
            $userData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'username' => $request->username,
                'is_admin' => $admin,
                'name_initials' => $request->name_initials,
                'has_photo' => $hasPhoto
            ];

            // Handle driver image for delivery personnel
            if ($request->hasFile('driver_image') && !$admin && in_array(131, $request->permission ?? [])) {
                $driverImage = $request->file('driver_image');
                $imageName = 'driver_' . time() . '.' . $driverImage->getClientOriginalExtension();
                $imagePath = 'users/drivers/' . $imageName;

                // Make sure directory exists
                $directory = public_path('users/drivers');
                if (!file_exists($directory)) {
                    if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
                    }
                }

                // Move the file
                $driverImage->move($directory, $imageName);

                // Add image path to user data
                $userData['img'] = $imagePath;
            }

            $users = User::create($userData);

            // Handle profile image upload
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                \Log::info('===== CREATE USER PHOTO UPLOAD START =====');
                \Log::info('Photo file detected in create user request', [
                    'user_id' => $users->id,
                    'original_name' => $photo->getClientOriginalName(),
                    'size_bytes' => $photo->getSize(),
                    'size_mb' => round($photo->getSize() / 1024 / 1024, 2),
                    'mime_type' => $photo->getMimeType(),
                    'extension' => $photo->getClientOriginalExtension(),
                    'temp_path' => $photo->getPathname(),
                    'is_valid' => $photo->isValid()
                ]);

                $path = public_path('/users/' . $users->id);
                \Log::info('Creating user directory', [
                    'path' => $path,
                    'exists' => file_exists($path),
                    'is_dir' => is_dir($path)
                ]);

                if (!is_dir($path)) {
                    $mkdirResult = mkdir($path, 0755, true);
                    if (!$mkdirResult || !is_dir($path)) {
                        \Log::error('Failed to create user directory', [
                            'path' => $path,
                            'mkdir_result' => $mkdirResult
                        ]);
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
                    }
                    \Log::info('User directory created successfully', ['path' => $path]);
                }

                $dest = $path . '/profile_picture.webp';
                \Log::info('Starting image compression for new user', [
                    'source' => $photo->getPathname(),
                    'destination' => $dest
                ]);

                try {
                    $this->saveImageUnder2MBAsWebp($photo->getPathname(), $dest, 12 * 1024 * 1024);

                    if (file_exists($dest)) {
                        \Log::info('Create user image upload SUCCESS', [
                            'destination' => $dest,
                            'size' => filesize($dest)
                        ]);
                    } else {
                        \Log::error('Create user image upload FAILED - file not created', [
                            'destination' => $dest
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Create user image compression exception', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }

                \Log::info('===== CREATE USER PHOTO UPLOAD END =====');
            }

            if (!$request->is_admin && $request->permission) {
                foreach ($request->permission as $permission) {
                    $perm = new UserPermission();
                    $perm->user_id = $users->id;
                    $perm->permission_id = $permission;
                    $perm->save();
                }
            }
            return $users;
        });
        if ($transaction == true) {
            return back()->with('success', 'The user has been successfully created');
        } else {
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function edit($id)
    {
        $user = User::with('permissions')->where('id', $id)->first();
        if (!$user) {
            abort(404);
        }
        $permissions = Permission::all();

        return view('users.edit')->with('user', $user)->with('permissions', $permissions);
    }


    public function block($id){
        $user = User::where('id', $id)->first();
        if (!$user) {
            abort(404);
        }
        if($user->status){
            $user->status = 0;
        } else {
            $user->status = 1;
        }

        $user->save();
        return back()->with('success', 'User has been updated');
    }

    public function update(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if (!$user) {
            abort(404);
        }
        \Log::info('===== UPDATE USER REQUEST STARTED =====', [
            'user_id' => $request->id,
            'has_photo_file' => $request->hasFile('photo'),
            'request_files' => array_keys($request->allFiles()),
            'photo_in_request' => $request->has('photo'),
            'photo_file_object' => $request->file('photo') ? 'exists' : 'null',
            'photo_error' => $request->file('photo') ? $request->file('photo')->getError() : 'no file',
            'photo_error_message' => $request->file('photo') ? $request->file('photo')->getErrorMessage() : 'no file',
            'max_upload_size_ini' => ini_get('upload_max_filesize'),
            'max_post_size_ini' => ini_get('post_max_size'),
            'memory_limit_ini' => ini_get('memory_limit'),
            'loaded_php_ini' => php_ini_loaded_file(),
            'post_content_length' => $_SERVER['CONTENT_LENGTH'] ?? 'unknown'
        ]);

        $this->validate($request, [
            'id'    => 'required',
            'first_name'     => 'required',
            'last_name'     => 'required',
            'phone' => 'required',
            'permission' => 'required_if:is_admin,null',
            'permission.*' => 'exists:permissions,id',
            'status' => 'nullable',
            'password_confirmation' => 'min:1|max:200|nullable',
            'driver_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:12288', // Made nullable for edit
        ]);

        try {
            $transaction = DB::transaction(function ()  use ($request, $user) {
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->name_initials = $request->name_initials;

            ////////////// Profile Image Part
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                \Log::info('===== PHOTO UPLOAD START =====');
                \Log::info('Photo file detected in request', [
                    'original_name' => $photo->getClientOriginalName(),
                    'size_bytes' => $photo->getSize(),
                    'size_mb' => round($photo->getSize() / 1024 / 1024, 2),
                    'mime_type' => $photo->getMimeType(),
                    'extension' => $photo->getClientOriginalExtension(),
                    'temp_path' => $photo->getPathname(),
                    'is_valid' => $photo->isValid(),
                    'error' => $photo->getError()
                ]);

                // Check if file size exceeds expected limits
                if ($photo->getSize() > 12 * 1024 * 1024) {
                    \Log::warning('Photo size exceeds 12MB limit', ['size_mb' => round($photo->getSize() / 1024 / 1024, 2)]);
                }

                // Validate (allow common types; input can be >12MB because we will compress)
                try {
                    $request->validate([
                        'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:12288', // KB
                    ]);
                    \Log::info('Photo validation passed');
                } catch (\Exception $e) {
                    \Log::error('Photo validation failed', [
                        'error' => $e->getMessage(),
                        'file_info' => [
                            'size' => $photo->getSize(),
                            'mime' => $photo->getMimeType()
                        ]
                    ]);
                    throw $e;
                }

                // Create directory if it doesn't exist
                $path = public_path('/users/' . $user->id);
                \Log::info('Checking directory: ' . $path, [
                    'exists' => file_exists($path),
                    'is_dir' => is_dir($path),
                    'is_writable' => is_writable($path)
                ]);

                if (!file_exists($path)) {
                    $mkdirResult = mkdir($path, 0755, true);
                    \Log::info('Created directory', [
                        'path' => $path,
                        'success' => $mkdirResult,
                        'now_exists' => file_exists($path),
                        'now_writable' => is_writable($path)
                    ]);

                    if (!$mkdirResult || !file_exists($path)) {
                        \Log::error('Failed to create directory: ' . $path);
                        throw new \RuntimeException('Failed to create user image directory');
                    }
                }

                // Destination file (overwrite is fine; no need to unlink first)
                $dest = $path . '/profile_picture.webp';
                \Log::info('Destination path: ' . $dest, [
                    'dest_dir_writable' => is_writable(dirname($dest)),
                    'dest_exists_before' => file_exists($dest)
                ]);

                // Check if GD library is available
                if (!extension_loaded('gd')) {
                    \Log::error('GD library is not loaded - image processing will fail!');
                    throw new \RuntimeException('GD library not available for image processing');
                }
                \Log::info('GD library loaded', ['gd_info' => gd_info()]);

                // Check available memory
                $memoryLimit = ini_get('memory_limit');
                \Log::info('PHP memory limit: ' . $memoryLimit);

                // Convert + compress under 12MB (your helper)
                try {
                    \Log::info('Starting image compression', [
                        'source' => $photo->getPathname(),
                        'destination' => $dest,
                        'max_size_bytes' => 12 * 1024 * 1024,
                        'max_size_mb' => 12
                    ]);

                    $this->saveImageUnder2MBAsWebp(
                        $photo->getPathname(),
                        $dest,
                        12 * 1024 * 1024
                    );

                    // Verify the file was created
                    if (file_exists($dest)) {
                        $destSize = filesize($dest);
                        \Log::info('Image compression SUCCESS', [
                            'destination' => $dest,
                            'output_size_bytes' => $destSize,
                            'output_size_mb' => round($destSize / 1024 / 1024, 2),
                            'file_exists' => true
                        ]);
                    } else {
                        \Log::error('Image compression FAILED - destination file does not exist', [
                            'destination' => $dest,
                            'source_existed' => file_exists($photo->getPathname())
                        ]);
                        throw new \RuntimeException('Image compression failed - output file not created');
                    }
                } catch (\Exception $e) {
                    \Log::error('Image compression threw exception', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }

                // Update user photo flag
                $user->has_photo = 1;
                \Log::info('Updated user has_photo flag to 1');
                \Log::info('===== PHOTO UPLOAD END =====');

            } else {
                \Log::info('No photo file in request');
            }

            // Handle driver image for delivery personnel
            if ($request->hasFile('driver_image') && !$request->is_admin &&
                (is_array($request->permission) && in_array(131, $request->permission))) {

                $driverImage = $request->file('driver_image');
                $imageName = 'driver_' . $user->id . '_' . time() . '.' . $driverImage->getClientOriginalExtension();
                $imagePath = 'users/drivers/' . $imageName;

                // Make sure directory exists
                $directory = public_path('users/drivers');
                if (!file_exists($directory)) {
                    if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
                    }
                }

                // Delete old driver image if exists
                if (!empty($user->img) && file_exists(public_path($user->img))) {
                    unlink(public_path($user->img));
                }

                // Move the file
                $driverImage->move($directory, $imageName);

                // Update user's img field
                $user->img = $imagePath;
            }



            if ($request->permission) {
                UserPermission::where('user_id', $request->id)->delete();
                if (!$request->is_admin && count($request->permission)) {
                    foreach ($request->permission as $permission) {
                        $perm = new UserPermission();
                        $perm->user_id = $request->id;
                        $perm->permission_id = $permission;
                        $perm->save();
                    }
                    $user->is_admin = false;
                    $permissions =  UserPermission::where('user_id', $request->id)->get();
                    Cache::forget('user'.$request->id);
                    Cache::forever('user'.$user->id,$permissions);
                }
            }
            $new_password      = $request->get('password_confirmation');
            if ($new_password) {
                User::where('id', $request->id)->update([
                    'password' => Hash::make($new_password)
                ]);
            }
            $user->status = $request->status == 'on' ? 1 : 0;
            if ($request->is_admin) {
                UserPermission::where('user_id', $request->id)->delete();
                $user->is_admin = true;
            }

            // Save user and verify
            $saveResult = $user->save();
            \Log::info('User save result', [
                'user_id' => $user->id,
                'save_result' => $saveResult,
                'has_photo' => $user->has_photo,
                'photo_file_exists' => file_exists(public_path('/users/' . $user->id . '/profile_picture.webp'))
            ]);

            return $saveResult;
            });

            if ($transaction == true) {
                \Log::info('Transaction completed successfully', [
                    'user_id' => $user->id,
                    'final_has_photo' => $user->has_photo
                ]);
                return back()->with('success', 'The user has been updated successfully');
            } else {
                \Log::error('Transaction failed', ['user_id' => $request->id]);
                return back()->with('error', 'Something went wrong!');
            }
        } catch (\Exception $e) {
            \Log::error('===== UPDATE USER EXCEPTION =====', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    public function softDelete($id)
    {
        try {
            $user = User::where('id', $id)->first();
            if (!$user) {
                return back()->with('error', 'User not found');
            }
            $user->delete();
            return back()->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
