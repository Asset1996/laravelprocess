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
                'createData' => [
                    'name' => [
                        'label' => trans('base.headers.user.createData.name'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'surname' => [
                        'label' => trans('base.headers.user.createData.surname'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'lastname' => [
                        'label' => trans('base.headers.user.createData.lastname'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'iin' => [
                        'label' => trans('base.headers.user.list.iin'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'roles_id' => [
                        'label' => trans('base.headers.user.createData.roles_id'),
                        'type' => 'dropdown',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'email' => [
                        'label' => trans('base.headers.user.createData.email'),
                        'type' => 'email',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'positions_id' => [
                        'label' => trans('base.headers.user.createData.positions_id'),
                        'type' => 'dropdown',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'pin_code' => [
                        'label' => trans('base.headers.user.createData.pin_code'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'pin_code_confirmation' => [
                        'label' => trans('base.headers.user.createData.pin_code_repeat'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'password' => [
                        'label' => trans('base.headers.user.createData.password'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                    'password_confirmation' => [
                        'label' => trans('base.headers.user.createData.password_repeat'),
                        'type' => 'text',
                        'class' => 'uk-table-expand',
                        'value' => null
                    ],
                ],
                // 'createData' => [
                //     [
                //         'key' => 'name',
                //         'value' => trans('base.headers.user.createData.name'),
                //         'type' => 'text',
                //         'class' => 'uk-table-expand'
                //     ],
                //     [
                //         'key' => 'surname',
                //         'value' => trans('base.headers.user.createData.surname'),
                //         'type' => 'text',
                //         'class' => 'uk-table-expand'
                //     ],
                //     [
                //         'key' => 'lastname',
                //         'value' => trans('base.headers.user.createData.lastname'),
                //         'type' => 'text',
                //         'class' => 'uk-table-expand'
                //     ],
                //     [
                //         'key' => 'iin',
                //         'value' => trans('base.headers.user.list.iin'),
                //         'type' => 'text',
                //         'class' => 'uk-table-expand'
                //     ],
                //     [
                //         'key' => 'roles_id',
                //         'value' => trans('base.headers.user.createData.roles_id'),
                //         'type' => 'dropdown',
                //         'class' => 'uk-table-expand'
                //     ],
                //     [
                //         'key' => 'email',
                //         'value' => trans('base.headers.user.createData.email'),
                //         'type' => 'email',
                //         'class' => 'uk-table-expand'
                //     ],
                //     [
                //         'key' => 'positions_id',
                //         'value' => trans('base.headers.user.createData.positions_id'),
                //         'type' => 'dropdown',
                //         'class' => 'uk-table-expand'
                //     ],
                //     [
                //         'key' => 'pin_code',
                //         'value' => trans('base.headers.user.createData.pin_code'),
                //         'type' => 'text',
                //         'class' => 'uk-table-expand'
                //     ],
                //     [
                //         'key' => 'pin_code_repeat',
                //         'value' => trans('base.headers.user.createData.pin_code_repeat'),
                //         'type' => 'text',
                //         'class' => 'uk-table-expand'
                //     ],
                //     [
                //         'key' => 'password',
                //         'value' => trans('base.headers.user.createData.password'),
                //         'type' => 'text',
                //         'class' => 'uk-table-expand'
                //     ],
                //     [
                //         'key' => 'password_repeat',
                //         'value' => trans('base.headers.user.createData.password_repeat'),
                //         'type' => 'text',
                //         'class' => 'uk-table-expand'
                //     ],
                // ],
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
