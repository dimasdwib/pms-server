<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {
        /**
     * Search
     * @param object $query
     * @param string $search
     * @param array $fields
     * @param array $specialFields => ex : array('is_active' => array(1 => 'active', 0 => 'inactive'))
     * @return object
     */
    public function scopeSearch($query, $search, array $fields = [], array $specialFields = [])
    {
        if ($search == '') {
            return $query;
        }

        $query->where(function($query) use($search, $fields, $specialFields) {
            if (count($fields)) {
                foreach ($fields as $key => $field) {
                    $query->orWhere($field, 'like', '%'.$search.'%');
                }
            }
            if (count($specialFields)) {
                foreach ($specialFields as $field => $values) {
                    if (count($values)) {
                        foreach ($values as $value => $name) {
                            if (str_contains(strtolower($name), strtolower($search))) {
                                $query->orWhere($field, '=', $value);
                            }
                        }
                    }
                }
            }
        });
        return $query;
    }

    public function scopeSearchByModel($query, $search, array $models = [])
    {
        if ($search == '') {
            return $query;
        }

        $query->where(function($query) use($search, $models) {
          foreach ($models as $model => $field) {
            if ($model === 'this') {
              foreach ($field as $key) {
                $query->orWhere($key, 'like', '%'.$search.'%');
              }
            } else {
              foreach ($field as $key) {
                $query->orWhereHas($model, function ($q) use ($field, $search, $key) {              
                    $q->where($key, 'like', '%'.$search.'%');
                });
              }
            }
          }
        });
        return $query;
    }
}