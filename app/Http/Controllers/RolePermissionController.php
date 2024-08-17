<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function __construct() {}

    function create(Request $req)
    {
        $allPermissions = Permission::select('name', 'id')->get();
        $allRoles = Role::with('permissions')->withCount('users')->get();

        return response()->json([
            "permissions" => $allPermissions,
            "roles" => $allRoles
        ], Response::HTTP_OK);
    }

    function index_role(Request $req)
    {
        $roles = Role::with('permissions')->paginate();
        return response()->json([
            "data" => $roles
        ], Response::HTTP_OK);
    }

    function store_role(Request $req)
    {
        $req->validate([
            'name' => 'required|string|unique:roles,name',
            "permission_ids" => "required|array",
            "permission.*" => "required|numeric|exists:permissions,id"
        ]);
        $newRole = Role::create([
            'name' => strtolower($req->input('name'))
        ]);

        $syncRole = $newRole->syncPermissions($req->permission_ids);

        return response()->json([
            "msg" => "permissions created successfully",
            "data" => $syncRole->with('permissions')
        ], Response::HTTP_CREATED);
    }

    function show_role($id)
    {
        $selected_role = Role::with('permission', 'users')->find($id);

        return response()->json([
            "data" => $selected_role
        ], Response::HTTP_OK);
    }

    function update_role(Request $req, $id)
    {
        $selected_role = Role::findOrFail($id);

        $req->validate([
            'role_name' => 'required|string|unique:roles,role_name,' . $id,
        ]);

        $selected_role->role_name = $req->input('role_name');
        $selected_role->save();

        return response()->json([
            "msg" => $selected_role->role_name . " saved successfully",
            "data" => $selected_role
        ], Response::HTTP_CREATED);
    }

    function destroy_role($id)
    {
        $selected_role = Role::withCount('permissions')->findOrFail($id);
        if ($selected_role->permissions_count > 0) {
            return response()->json([
                "msg" => $selected_role->role_name . " can't be deleted it has some permissions assigned",
            ], Response::HTTP_UNAUTHORIZED);
        }
        $selected_role->delete();
        return response()->json([
            "msg" => "selected role has been deletes successfully",
        ], Response::HTTP_OK);
    }


    function index_permission(Request $req)
    {
        $permissions = Permission::with('roles')->withCount('users')->get();
        return response()->json([
            $permissions
        ], Response::HTTP_OK);
    }

    function store_permission(Request $req, Response $res)
    {
        $req->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'required|string|unique:permissions,name',
        ]);

        $newPermissions = [];

        foreach ($req->permissions as $key => $permission) {
            $permissions = [];

            $permissions['guard_name'] = strtolower("api");
            $permissions['name'] = strtolower($permission);
            $permissions['created_at'] = now();
            $permissions['updated_at'] = now();

            array_push($newPermissions, $permissions);
        }

        Permission::insert($newPermissions);

        return response()->json([
            "msg" => "permissions created successfully",
            "data" => $newPermissions
        ], Response::HTTP_CREATED);
    }

    function show_permission($id)
    {
        $selected_permission = Permission::with('roles', 'users')->find($id);

        return response()->json([
            "data" => $selected_permission
        ], Response::HTTP_OK);
    }

    function update_permission(Request $req, $id)
    {
        $selected_permission = Permission::findOrFail($id);

        $req->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
        ]);

        $selected_permission->name = $req->input('name');
        $selected_permission->save();

        return response()->json([
            "msg" => $selected_permission->name . " saved successfully",
            "data" => $selected_permission
        ], Response::HTTP_CREATED);
    }

    function destroy_permission($id)
    {
        $selected_permission = Permission::withCount('permissions')->findOrFail($id);
        if ($selected_permission->roles_count > 0) {
            return response()->json([
                "msg" => $selected_permission->name . " can't be deleted it assigned to a role",
            ], Response::HTTP_UNAUTHORIZED);
        }
        $selected_permission->delete();
        return response()->json([
            "msg" => "selected permission has been deletes successfully",
        ], Response::HTTP_OK);
    }
}
