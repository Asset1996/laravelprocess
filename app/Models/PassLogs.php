<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Headers;
use App\Helpers\Paginator;
use App\Models\Device;

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
     * Связь с моделью Device.
     *
     * @return $this
     */
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    /**
     * Получние списка логов.
     *
     * @return array
     */
    public static function getList(): array
    {
        $result = array();
        
        $logs = self::with('user.roles')->with('device')
            ->user_id(request()->user_id)
            ->created_at(request()->created_at)
            ->orderBy('created_at', 'desc')
            ->paginate($_ENV['PAGE_NUM']);

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
                'device_id' => isset($log['device']) ? [
                    'key' => $log['device']['id'],
                    'value' => $log['device']['location'],
                ] : '',
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

    /**
     * Создание лога.
     *
     * @return array
     */
    public static function createLog(int $user_id, int $is_on_duty, $uploadedFile){
        
        return self::create([
            'user_id' => $user_id,
            'direction' => !$is_on_duty,
            'device_id' => request()->input('device_id'),
            'image_name' => $uploadedFile['fileName'],
            'image_path' => $uploadedFile['filePath'],
            'image_size' => $uploadedFile['fileSize']
        ])->save();
    }

    /**
     * Создание логов для всех сотрудников, которые забыли сделать выход.
     *
     * @return bool
     */
    public static function createLogForAll(array $user_ids){
        
        $uploadedFile = [
            'fileName' => 'SystemOut.jpeg',
            'filePath' => '/public/passphotos/SystemOut.jpeg',
            'fileSize' => 4000
        ];
        foreach ($user_ids as $user_id) {
            self::create([
                'user_id' => $user_id,
                'direction' => 0,
                'device_id' => 'CRON',
                'image_name' => $uploadedFile['fileName'],
                'image_path' => $uploadedFile['filePath'],
                'image_size' => $uploadedFile['fileSize']
            ])->save();
        }
        return true;
    }

    // /**
    //  * Получние списка логов.
    //  *
    //  * @return array
    //  */
    // public static function forceExitAll(): bool{


    //     return true;
    // }

    public function scopeUser_id($query, $user_id)
    {
        if (!is_null($user_id)) {
            return $query->where('user_id', $user_id);
        }

        return $query;
    }

    public function scopeCreated_at($query, $created_at)
    {
        if ($created_at) {
            $created_at = \Illuminate\Support\Str::limit($created_at, 15, $end='');
            $created_at = new \DateTime($created_at);
            return $query->whereDate('created_at', $created_at);
        }

        return $query;
    }
}
