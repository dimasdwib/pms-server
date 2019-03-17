<?php

namespace Modules\Permission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Models\Acl\PermissionResource;
use App\Models\Acl\PermissionGroup;
use App\User;
use DB;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $permissions = Permission::paginate(20);
        return $permissions;
    }

    /**
     * Display all of the resource.
     * @return Response
     */
    public function all()
    {
        return Permission::all();
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
          'name' => 'required|max:255',
          'id_permission_group' => 'integer|nullable',
        ]);

        DB::beginTransaction();
        $permission = Permission::create(['name' => $request->name]);
        if ($permission) {

            if ($request->id_permission_group != null) {
                $permissionGroup = PermissionGroup::findOrFail($request->id_permission_group);
                $permissionGroup->assignPermission($permission->id);
            }

            $users = explode(',', $request->users);
            $roles = explode(',', $request->roles);

            if (!in_array('superadmin', $roles)) {
                $roles[] = 'superadmin';
            }
            $permission->syncRoles($roles);

            if (count($users) > 0) {
                $users = User::whereIn('id', $users)->get();
                foreach($users as $user) {
                  $user->givePermissionTo($permission->name);
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Permission has been created successfully',
                'permission' => new PermissionResource($permission),
            ]);
        }

        DB::rollBack();
        $this->response->errorInternal();
    }

    /**
     * Show the specified resource.
     * @param  Int $id
     * @return Response
     */
    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return new PermissionResource($permission);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
          'name' => 'required|max:255',
          'id_permission_group' => 'integer|nullable',
        ]);

        DB::beginTransaction();
        $permission = Permission::findById($id);
        $permission->name = $request->name;
        if ($permission->save()) {

            if ($request->id_permission_group != null) {
                $permissionGroup = PermissionGroup::findOrFail($request->id_permission_group);
                $permissionGroup->assignPermission($permission->id);
            }
            
            $users = explode(',', $request->users);
            $roles = explode(',', $request->roles);

            if (!in_array('superadmin', $roles)) {
                $roles[] = 'superadmin';
            }
            $permission->syncRoles($roles);

            if (count($users) > 0) {
                // remove permission from users;
                $users_with_permission = User::permission($permission->name)->get();
                foreach ($users_with_permission as $user) {
                    $user->revokePermissionTo($permission->name);
                }

                // assign permission to user
                $users = User::whereIn('id', $users)->get();
                foreach($users as $user) {
                    $user->givePermissionTo($permission->name);
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Permission has been updated successfully',
                'permission' => new PermissionResource($permission),
            ]);
        }

        DB::rollBack();
        $this->response->errorInternal();
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        $permission = Permission::findById($id);

        // remove permission from user
        $users = User::whereHas('permissions', function($q) use ($id) { $q->where("id", $id); })->get();
        foreach($users as $user) {
          $user->revokePermissionTo($permission);
        }

        // remove permission from role
        $permission->syncRoles([]);

        if ($permission->delete()) {
            DB::commit();
            return response()->json([
                'message' => 'Permission has been deleted successfully',
            ]);
        }
        DB::rollBack();
        return $this->response->errorInternal();
    }
}
