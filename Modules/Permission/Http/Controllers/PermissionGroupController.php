<?php

namespace Modules\Permission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Acl\PermissionGroupResource;
use App\Models\Acl\PermissionGroup;
use App\Models\Acl\Permission;
use Illuminate\Support\Arr;
use DB;

class PermissionGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $permissionGroup = PermissionGroup::all();
        return PermissionGroupResource::collection($permissionGroup);
    }

    /**
     * Create new permission group
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request) {
        $request->validate([
            'group' => 'required|unique:permission_group|max:100',
            'id_parent' => 'nullable|integer',  
        ]);

        DB::beginTransaction();
        $group = new PermissionGroup;
        $group->group = $request->group;
        $group->id_parent = $request->id_parent;
        
        if ($group->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Permission group has been created successfully',
                'group' => new PermissionGroupResource($group),
            ]);
        }

        DB::rollBack();
        $this->response->errorInternal();
    }

    /**
     * Delete permission group
     * @param $id id permission group
     * 
     */
    public function destroy($id) {
        DB::beginTransaction();
        $permissionGroup = PermissionGroup::findOrFail($id);
        $id_parent = $permissionGroup->id_parent;
        
        // detach child
        DB::table('permission_group')
            ->where('id_parent', $id)
            ->update([
                'id_parent' => $id_parent
            ]);
        if ($permissionGroup->delete()) {
            DB::commit();
            return response()->json([
                'message' => 'Permission group has been deleted successfully',
            ]);
        }
        DB::rollBack();
        return $this->response->errorInternal();
    }

    /**
     * Update permission group
     * @param Request $request
     * @param $id id permission group
     */
    public function update(Request $request, $id) {
        $request->validate([
          'group' => 'required|max:100',
          'id_parent' => 'nullable|integer',
        ]);
        
        if ($request->id_parent === $id) {
            return $this->response->errorInternal('Invalid id parent');
        }

        $permissionGroup = PermissionGroup::findOrFail($id);

        $permissionGroup->group = $request->group;
        $permissionGroup->id_parent = $request->id_parent;
        
        DB::beginTransaction();
        if ($permissionGroup->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Permission group has been updated successfully',
                'group' => $permissionGroup,  
            ]);
        }

        DB::rollBack();
        return $this->response->errorInternal();
    }

    /**
     * Show Permission group
     * @param $id id permission group
     */
    public function show($id) {
        $permissionGroup = PermissionGroup::findOrFail($id);
        return new PermissionGroupResource($permissionGroup);
    }


    private function loop_child($id, $groups, $permissions) {
        $child = [];
        foreach ($groups as $g) {
            if ($g->id_parent === $id) {
                $child[] = [
                  'key'  => 'group_'.$g->id_permission_group,
                  'key_parent' => 'group_'.$id,
                  'id'   => $g->id_permission_group,
                  'id_parent' => $id,
                  'title' => $g->group,
                  'type' => 'group',
                  'children' => $this->loop_child($g->id_permission_group, $groups, $permissions),
                ];
            }
          }

        foreach ($permissions as $p) {
          $permissionGroup = $p->getPermissionGroup();
          if ($permissionGroup !== null && $id == $permissionGroup->id) {
              $child[] = [
                'key'  => 'permission_'.$p->id,
                'key_parent' => 'group_'.$id,
                'id'   => $p->id,
                'id_parent'   => $id,
                'title' => $p->name,
                'type' => 'permission',
              ];
          }
        }

        return $child;
    }

    /**
     * Display permission tree
     */
    public function group_tree() {
        $groups = PermissionGroup::all();
        $permissions = Permission::all();
        $tree = [];
        foreach($groups as $g) {
            if ($g->id_parent == null) {
                $tree[] = [
                    'key'  => 'group_'.$g->id_permission_group,
                    'key_parent' => null,
                    'id'   => $g->id_permission_group,
                    'id_parent' => null,
                    'title' => $g->group,
                    'type' => 'group',
                    'children' => $this->loop_child($g->id_permission_group, $groups, $permissions),
                ];
            }
        }
        foreach ($permissions as $p) {
            if ($p->getPermissionGroup() == null) {
                $tree[] = [
                  'key'  => 'permission_'.$p->id,
                  'key_parent' => null,
                  'id'   => $p->id,
                  'id_parent' => null,
                  'title' => $p->name,
                  'type' => 'permission',
                ];
            }
        }

        return response()->json($tree);
    }
}