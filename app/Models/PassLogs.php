<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Headers;
use App\Helpers\Paginator;

class PassLogs extends Model
{
    use HasFactory;
    protected $table = 'pass_logs';

    protected $fillable = [
        'user_id',
        'direction',
        'device_id',
        'image_name',
        'image_path',
        'image_size'
    ];

    /**
     * Правила валидаций данных.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'photo' => 'required',
            'user_id' => 'required',
        ];
    }

    protected $casts = [
        'updated_at' => 'datetime:d/M/Y H:i',
        'created_at' => 'datetime:d/M/Y H:i',
    ];

    /**
     * Связь с моделью User.
     *
     * @return $this
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Получние списка логов.
     *
     * @return array
     */
    public static function getList(): array
    {
        $result = array();
        $logs = PassLogs::with('user.roles')->orderBy('created_at', 'desc')->paginate($_ENV['PAGE_NUM']);

        $result['headers'] = Headers::get('passLogs.list');

        foreach($logs as $log){
            $result['body'][] = [
                'id' => $log['id'],
                'user_id' => $log['user_id'],
                'device_id' => $log['device_id'],
                'image_path' => $log['image_path'],
                'direction' => [
                    'key' => $log['direction'],
                    'value' => trans('base.direction.'.(string)$log['direction'])
                ],
                'iin' => $log['user']['iin'],
                'fio' => $log['user']['name'] . ' ' . $log['user']['surname'],
                'roles_id' => $log['user']['roles_id'],
                'role' => $log['user']['roles']['title'],
                'created_at' => $log['created_at']->format('d/M/Y H:i'),
            ];
        }
        $result['paginator'] = Paginator::get($logs);
        
        return $result;
    }
}
