<?php

namespace App\Helpers;
use Illuminate\Support\Facades\DB;

use Exception;

class Handbook
{
    /**
     * Returns headers in getHeaders returned array if exist.
     *
     * @return array|bool
     */
    public static function getHandbooks(array $fields){
        foreach($fields as $key=>$field){
            if($field['type'] == 'dropdown'){
                $handbook = self::get(rtrim($key, "_id"));
                $fields[$key]['value'] = $handbook[0]->id;
                $fields[$key]['handbook'] = $handbook;
            }
        }
        return $fields;
    }

    /**
     * Returns headers in getHeaders returned array if exist.
     *
     * @return array|bool
     */
    protected static function get(string $key){
        return DB::table($key)->select('id', 'title')->get()->toArray();
    }
}
