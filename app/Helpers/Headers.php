<?php

namespace App\Helpers;

use Exception;

class Headers
{
    /**
     * Returns headers in getHeaders returned array if exist.
     *
     * @return array|bool
     */
    public static function get($key){
        $array = static::getHeaders();
        
        foreach (explode('.', $key) as $segment) {
            
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $array;
            }
        }

        return $array;
    }

    /**
     * Returns headers.
     *
     * @return array
     */
    protected static function getHeaders(){
        return [
            'user' => [
                'list' => [
                    [
                        'key' => 'fio',
                        'value' => trans('base.headers.user.list.Full Name'),
                        'class' => 'uk-table-expand'
                    ],
                    [
                        'key' => 'iin',
                        'value' => trans('base.headers.user.list.iin'),
                        'class' => 'uk-table-expand'
                    ],
                    [
                        'key' => 'role',
                        'value' => trans('base.headers.user.list.role'),
                        'class' => 'uk-table-expand'
                    ],
                    [
                        'key' => 'is_on_duty',
                        'value' => trans('base.headers.user.list.is_on_duty'),
                        'class' => 'uk-table-expand'
                    ],
                ],
                'timingList' => [
                    [
                        'key' => 'day',
                        'value' => trans('base.headers.user.timingList.date'),
                        'class' => 'uk-table-small'
                    ],
                    [
                        'key' => 'minutes',
                        'value' => trans('base.headers.user.timingList.time'),
                        'class' => 'uk-table-expand'
                    ]
                ],
            ],
            'passLogs' => [
                'list' => [
                    [
                        'key' => 'fio',
                        'value' => trans('base.headers.passLogs.list.Full Name'),
                        'class' => 'uk-table-expand'
                    ],
                    [
                        'key' => 'direction',
                        'value' => trans('base.headers.passLogs.list.Action'),
                        'class' => 'uk-width-small'
                    ],
                    [
                        'key' => 'image_path',
                        'value' => trans('base.headers.passLogs.list.Photo'),
                        'class' => 'uk-width-small'
                    ],
                    [
                        'key' => 'created_at',
                        'value' => trans('base.headers.passLogs.list.DateTime'),
                        'class' => 'uk-table-shrink uk-text-nowrap'
                    ]
                ],
            ]
        ];
    }
}