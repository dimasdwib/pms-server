<?php

namespace App\Models\Acl;
use App\Models\BaseModel;
use DB;

class PermissionGroup extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permission_group';
    protected $primaryKey = 'id_permission_group';
    public $timestamps = true;

    public function assignPermission($permission_id)
    {
        if ($permission_id !== null) {
            DB::table('permission_group_has_permissions')
                ->updateOrInsert(
                ['permission_id' => $permission_id],
                [
                    'id_permission_group' => $this->attributes['id_permission_group'],
                    'permission_id' => $permission_id,
                ]);
        }
    }
}