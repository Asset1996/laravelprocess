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
                        'value' => trans('base.headers.user.Full Name'),
                        'class' => 'uk-table-expand'
                    ],
                    [
                        'key' => 'iin',
                        'value' => trans('base.headers.user.iin'),
                        'class' => 'uk-table-expand'
                    ],
                    [
                        'key' => 'role',
                        'value' => trans('base.headers.user.role'),
                        'class' => 'uk-table-expand'
                    ],
                    [
                        'key' => 'position',
                        'value' => trans('base.headers.user.position'),
                        'class' => 'uk-table-expand'
                    ],
                    [
                        'key' => 'is_on_duty',
                        'value' => trans('base.headers.user.is_on_duty'),
                        'class' => 'uk-table-expand'
                    ],
                ],
                'createData' => [
                    'name' => [
                        'label' => trans('base.headers.user.name'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'surname' => [
                        'label' => trans('base.headers.user.surname'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'lastname' => [
                        'label' => trans('base.headers.user.lastname'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'iin' => [
                        'label' => trans('base.headers.user.iin'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'roles_id' => [
                        'label' => trans('base.headers.user.roles_id'),
                        'type' => 'dropdown',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'email' => [
                        'label' => trans('base.headers.user.email'),
                        'type' => 'email',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'positions_id' => [
                        'label' => trans('base.headers.user.positions_id'),
                        'type' => 'dropdown',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'pin_code' => [
                        'label' => trans('base.headers.user.pin_code'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'pin_code_confirmation' => [
                        'label' => trans('base.headers.user.pin_code_confirmation'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'password' => [
                        'label' => trans('base.headers.user.password'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'password_confirmation' => [
                        'label' => trans('base.headers.user.password_confirmation'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                ],
                'profileIndex' => [
                    'fio' => [
                        'value' => trans('base.headers.user.Full Name'),
                        'class' => 'uk-table-expand'
                    ],
                    'photo' => [
                        'value' => trans('base.headers.user.photo'),
                        'class' => 'uk-table-expand'
                    ],
                    'email' => [
                        'value' => trans('base.headers.email'),
                        'class' => 'uk-table-expand'
                    ],
                    'iin' => [
                        'value' => trans('base.headers.user.iin'),
                        'class' => 'uk-table-expand'
                    ],
                    'roles_id' => [
                        'value' => trans('base.headers.user.role'),
                        'class' => 'uk-table-expand'
                    ],
                    'positions_id' => [
                        'value' => trans('base.headers.user.position'),
                        'class' => 'uk-table-expand'
                    ],
                    'is_on_duty' => [
                        'value' => trans('base.headers.user.is_on_duty'),
                        'class' => 'uk-table-expand'
                    ],
                ],
                'timingList' => [
                    [
                        'key' => 'day',
                        'value' => trans('base.headers.user.date'),
                        'class' => 'uk-table-small'
                    ],
                    [
                        'key' => 'minutes',
                        'value' => trans('base.headers.user.time'),
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
                        'key' => 'device_id',
                        'value' => trans('base.headers.passLogs.list.device_id'),
                        'class' => 'uk-table-shrink uk-text-nowrap'
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
