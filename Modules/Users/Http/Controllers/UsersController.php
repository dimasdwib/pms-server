<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Permission;
use App\Models\Role;
use App\Http\Resources\User as UserResource;
use DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $limit = null;
        if ($request->limit != null) {
            $limit = (Int) $request->limit;
        }
        
        $users = User::paginate($limit);

        foreach($users as $key => $user) {
            $users[$key]->permissions = $user->getDirectPermissions();
            $users[$key]->roles = $user->getRoleNames();
        }
        return response()->json($users);
    }

    /**
     * Display all resource
     * @return Response
     */
    public function all() {
        return User::all();
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
            'username' => 'required|unique:users|max:100',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        DB::beginTransaction();
        if ($user->save()) {
            if ($request->permission != '') {
                $user->permission = $user->givePermissionTo(explode(',', $request->permission));
            }
            $user->role = $user->assignRole(explode(',', $request->role));

            DB::commit();
            return response()->json([
                'message' => 'User has been created successfully',
                'user' => new UserResource($user),
            ]);
        }

        DB::rollBack();
        $this->response->errorInternal();
    }

    /**
     * Show the specified resource.
     * @param  Request $request
     * @return Response
     */
    public function show($id)
    {
        return new UserResource(User::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @param  Int $id User Id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|unique:users,username,'.$id.'|max:100',
            'email' => 'required|unique:users,email,'.$id,
            'role' => 'required'
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        if ($request->password !== '') {
            $user->password = bcrypt($request->password);
        }

        DB::beginTransaction();
        if ($user->save()) {
            // update direct permission
            if ($request->permission != '') {
                $user->syncPermissions(explode(',', $request->permission));
            } else {
                $user->syncPermissions([]);
            }

            // update role
            // push superadmin if user id = 1;
            $roles = explode(',', $request->role);
            if ($id == 1 && !in_array('superadmin', $roles)) {
                $roles[] = 'superadmin';
            }
            $user->syncRoles($roles);

            DB::commit();
            return response()->json([
                'message' => 'User has been updated successfully',
                'user' => new UserResource($user),
            ]);
        }

        DB::rollBack();
        return $this->response->errorInternal('Opps, something wrong');
    }

    /**
     * Remove the specified resource from storage.
     * @param  int $id User Id
     * @return Response
     */
    public function destroy($id)
    {
        if ($id == 1) {
            return $this->response->errorInternal('You cant delete superadmin');
        }
        if ($id == $this->getAuthUser()->id) {
            return $this->response->errorInternal('You cant delete current user');
        }
        DB::beginTransaction();
        $user = User::findOrFail($id);
        $user->syncPermissions([]);
        $user->syncRoles([]);
        if ($user->delete()) {
            DB::commit();
            return response()->json([
                'message' => 'User has been deleted successfully',
            ]);
        }
        DB::rollBack();
        return $this->response->errorInternal();
    }
}
