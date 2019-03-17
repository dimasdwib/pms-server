<?php

namespace App\Models\Acl;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;
use App\User;


class PermissionGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id_permission_group,
            "group" => $this->group,
            "id_parent" => $this->id_parent,
            "created_at"  => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
