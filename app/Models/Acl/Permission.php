<?php

namespace App\Models\Acl;

use Spatie\Permission\Models\Permission as SpatiePermission;
use App\Models\Acl\PermissionGroup;
use DB;

class Permission extends SpatiePermission
{
    public function getPermissionGroup() {
        $group = DB::table('permission_group_has_permissions')
                            ->select('permission_group.id_permission_group as id', 'group')
                            ->join('permission_group', 'permission_group.id_permission_group', '=', 'permission_group_has_permissions.id_permission_group')
                            ->where('permission_id', $this->id)
                            ->first();
        return $group;
    }
}