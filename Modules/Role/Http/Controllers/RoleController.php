<?php

namespace Modules\Role\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Spatie\Permission\Models\Role;
use App\Models\Acl\RoleResource;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return Role::paginate(10);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function all()
    {
        return Role::all();
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
            'permissions' => 'required',
        ]);

        $data = [
          'name' => $request->name,
        ];
        DB::beginTransaction();
        $role = Role::create($data);
        if ($role) {
          $permissions = explode(',', $request->permissions);
          $role->syncPermissions($permissions);

          $users = explode(',', $request->users);
          if (count($users) > 0) {
            $users = User::whereIn('id', $users)->get();
            foreach($users as $user) {
              $user->assignRole($role->name);
            }
          }

          DB::commit();
          return response()->json([
            'message' => 'Role has been created successfully',
            'role' => new RoleResource($role),
          ]);
        }

        DB::rollBack();
        $this->response->errorInternal();
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        return new RoleResource($role);
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
            'permissions' => 'required',
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;

        DB::beginTransaction();
        if ($role->save()) {

            $permissions = explode(',', $request->permissions);
            $role->syncPermissions($permissions);

            $users = explode(',', $request->users);
            // if role is superadmin force assign to Administrator
            if ($id == 1 && !in_array(1, $users)) { // role:admin
              $users[] = 1;
            }
            if (count($users) > 0) {
                $all_users = User::all();
                foreach($all_users as $user) {
                  if (in_array($user->id, $users)) {
                    if (!$user->hasRole($role->name)) {
                      $user->assignRole($role->name);
                    }
                  } else {
                    if ($user->hasRole($role->name)) {
                      $user->removeRole($role->name);
                    }
                  }
                }
            }

            DB::commit();
            return response()->json([
              'message' => 'Role has been updated successfully',
              'role' => new RoleResource($role),
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
        if ($id == 1) {
            return $this->response->errorInternal('You cant delete superadmin');
        }

        DB::beginTransaction();
        $role = Role::findById($id);

        // remove role from user
        $users = User::whereHas('roles', function($q) use ($id) { $q->where("id", $id); })->get();
        foreach($users as $user) {
          $user->removeRole($role);
        }

        // remove role from permission
        $role->syncPermissions([]);

        if ($role->delete()) {
            DB::commit();
            return response()->json([
                'message' => 'Role has been deleted successfully',
            ]);
        }
        DB::rollBack();
        return $this->response->errorInternal();
    }
}
