<?php

namespace App\Http\Controllers\Admin\Admins;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Exception;

class RoleController extends Controller
{
    /**
     * Display a listing of roles and permissions.
     */
    public function index()
    {
        try {
            $roles = Role::with('permissions')->get();
            $permissions = Permission::all();

            return view('admin.admins.roles', compact('roles', 'permissions'));
        } catch (Exception $e) {
            Log::error("Error loading Roles Index: " . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load roles at this time.');
        }
    }

    /**
     * Store a newly created role with permissions.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255|unique:roles,name',
            'slug'          => 'required|string|max:255|unique:roles,slug',
            'permissions'   => 'nullable|array',
            'default_route' => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name'          => $request->name,
                'slug'          => $request->slug,
                'description'   => $request->description,
                'default_route' => $request->default_route,
            ]);

            $this->syncRolePermissions($role, $request->permissions);

            DB::commit();
            return redirect()->back()->with('success', 'Role created successfully with assigned permissions!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Role Creation Failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create role. Please try again.');
        }
    }

    /**
     * Fetch role data for AJAX edit modal.
     */
    public function edit(Role $role)
    {
        try {
            $permissions = $role->permissions->mapWithKeys(function ($perm) {
                return [
                    $perm->id => [
                        'view' => (bool) $perm->pivot->can_view,
                        'edit' => (bool) $perm->pivot->can_edit,
                    ]
                ];
            });

            return response()->json([
                'id'            => $role->id,
                'name'          => $role->name,
                'slug'          => $role->slug,
                'description'   => $role->description,
                'permissions'   => $permissions,
                'default_route' => $role->default_route,
            ]);
        } catch (Exception $e) {
            Log::error("Role Edit Fetch Failed: " . $e->getMessage());
            return response()->json(['error' => 'Could not fetch role data.'], 500);
        }
    }

    /**
     * Update an existing role and its permissions.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'          => 'required|string|max:255|unique:roles,name,' . $role->id,
            'slug'          => 'required|string|max:255|unique:roles,slug,' . $role->id,
            'permissions'   => 'nullable|array',
            'default_route' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $role->update([
                'name'          => $request->name,
                'slug'          => $request->slug,
                'description'   => $request->description,
                'default_route' => $request->default_route,
            ]);

            $this->syncRolePermissions($role, $request->permissions);

            DB::commit();
            return redirect()->back()->with('success', 'Role and permissions updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Role Update Failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred during the update.');
        }
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        try {
            // 1. Protection for core roles
            if ($role->slug === 'super_admin') {
                return redirect()->back()->with('error', 'The Super Admin role is a core system component and cannot be deleted.');
            }

            // 2. Prevent deletion if role is in use
            if ($role->admins()->exists()) {
                return redirect()->back()->with('error', 'Cannot delete this role because it is currently assigned to one or more admins.');
            }

            DB::beginTransaction();
            
            // Detach permissions first
            $role->permissions()->detach();
            $role->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Role deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Role Deletion Failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete the role.');
        }
    }

    /**
     * Helper method to process and sync permissions to a role.
     */
    private function syncRolePermissions(Role $role, ?array $requestPermissions)
    {
        $syncData = [];
        $allPermissions = Permission::all();

        foreach ($allPermissions as $perm) {
            $permValues = $requestPermissions[$perm->id] ?? [];
            
            $syncData[$perm->id] = [
                'can_view' => isset($permValues['view']) ? 1 : 0,
                'can_edit' => isset($permValues['edit']) ? 1 : 0,
            ];
        }

        $role->permissions()->sync($syncData);
    }
}