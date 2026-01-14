<?php

namespace App\Http\Controllers\Admin\Admins;

use App\Models\Role;
use App\Models\Admin;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class AdminController extends Controller
{
    /**
     * Display a listing of the admins.
     */
    public function index()
    {
        try {
            $admins = Admin::with('role')->latest()->get();
            $permissions = Permission::all();
            $roles = Role::all();

            $superAdminRole = $roles->where('slug', 'super_admin')->first();
            $superAdminExists = (bool)$superAdminRole;
            $superAdminAssigned = $superAdminRole && $superAdminRole->admins()->exists();

            return view('admin.admins.index', compact(
                'admins', 
                'permissions', 
                'roles', 
                'superAdminExists', 
                'superAdminAssigned'
            ));
        } catch (Exception $e) {
            Log::error("Error loading Admins Index: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load admins list.');
        }
    }

 
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:admins,email',
            'password'    => 'required|string|min:6',
            'role_id'     => 'required|exists:roles,id',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $adminData = $request->only(['name', 'email', 'role_id']);
            $adminData['password'] = bcrypt($request->password);

            $admin = Admin::create($adminData);

            // Handle Profile Picture
            if ($request->hasFile('profile_pic')) {
                $file = $request->file('profile_pic');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = "uploads/admins/{$admin->id}";
                
                $fullPath = $file->storeAs($path, $filename, 'public');
                $admin->update(['profile_pic' => $fullPath]);
            }

            if ($admin->role && $admin->role->permissions) {
                $admin->permissions()->sync($admin->role->permissions->pluck('id'));
            }

            DB::commit();
            return redirect()->back()->with('success', 'Admin created and permissions synced successfully!');
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Admin Creation Failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while creating the admin.');
        }
    }

 
    public function edit(Admin $admin)
    {
        return response()->json([
            'id'          => $admin->id,
            'name'        => $admin->name,
            'email'       => $admin->email,
            'role_id'     => $admin->role_id,
            'profile_pic' => $admin->profile_pic ? asset('storage/' . $admin->profile_pic) : null,
        ]);
    }


    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:admins,email,' . $admin->id,
            'password'    => 'nullable|string|min:6',
            'role_id'     => 'required|exists:roles,id',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $oldRoleId = $admin->role_id;
            
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->role_id = $request->role_id;

            if ($request->filled('password')) {
                $admin->password = bcrypt($request->password);
            }

            if ($request->hasFile('profile_pic')) {
                if ($admin->profile_pic) {
                    Storage::disk('public')->delete($admin->profile_pic);
                }

                $file = $request->file('profile_pic');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = "uploads/admins/{$admin->id}";
                $admin->profile_pic = $file->storeAs($path, $filename, 'public');
            }

            $admin->save();

            // If role changed, re-sync permissions
            if ($oldRoleId != $request->role_id) {
                $admin->permissions()->sync($admin->role->permissions->pluck('id'));
            }

            DB::commit();
            return redirect()->back()->with('success', 'Admin updated successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Admin Update Failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update admin details.');
        }
    }

    public function forceLogout(Admin $admin)
    {
        try {
            DB::table('sessions')
                ->where('user_id', $admin->id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => "Admin {$admin->name} has been forced to logout."
            ]);
        } catch (Exception $e) {
            Log::error("Force Logout Failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to force logout.'], 500);
        }
    }


    public function destroy(Admin $admin)
    {
        try {
            if (auth('admin')->check() && auth('admin')->id() === $admin->id) {
                return redirect()->back()->with('error', 'You cannot delete your own account!');
            }

            if ($admin->role && $admin->role->slug === 'super_admin') {
                return redirect()->back()->with('error', 'The Super Admin account is protected and cannot be deleted!');
            }

            DB::beginTransaction();

            Storage::disk('public')->deleteDirectory('uploads/admins/' . $admin->id);

            $admin->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Admin and all associated data deleted successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Admin Deletion Failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while trying to delete the admin.');
        }
    }
}